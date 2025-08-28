<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DetailBarangKeluar;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangKeluarExport;
use App\Exports\DetailBarangKeluarExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BarangKeluarController extends Controller
{
    /**
     * Get route prefix based on user role
     */
    private function getRoutePrefix(Request $request)
    {
        $role = $request->user()->role;
        return $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
    }

    public function index(Request $request)
    {
        $keyword = $request->query('search');

        // Ambil data + eager load details & produk untuk hindari N+1
        $barangKeluars = BarangKeluar::with(['detailBarangKeluars.produk'])
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('kode_barang_keluar', 'like', "%{$keyword}%")
                       ->orWhere('penerima', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends(['search' => $keyword]);

        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-keluar.index', compact('barangKeluars', 'keyword'));
    }
    
    public function create(Request $request)
    {
        $kodeBarangKeluar = $this->generateNomorTransaksi();
        
        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-keluar.create', compact('kodeBarangKeluar'));
    }

    /**
     * Simpan Header Transaksi Barang Keluar
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'penerima' => 'required|string|max:150',
            'alamat_penerima' => 'nullable|string|max:255',
            'telepon_penerima' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:255',
            'payment' => 'required|in:cash,transfer,piutang',
        ]);

        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::create([
                'kode_barang_keluar' => $this->generateNomorTransaksi(),
                'tanggal_keluar' => $request->tanggal_keluar,
                'penerima' => $request->penerima,
                'alamat_penerima' => $request->alamat_penerima,
                'telepon_penerima' => $request->telepon_penerima,
                'catatan' => $request->catatan,
                'payment' => $request->payment,
                'user_id' => $request->user()->id,
                'status' => 'draft',
            ]);

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.detail', $barangKeluar->id)
                ->with('success', 'Header transaksi berhasil disimpan. Silakan tambahkan produk.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Tampilkan halaman detail barang keluar untuk menambah produk
     */
    public function detail(Request $request, $id)
    {
        $barangKeluar = BarangKeluar::with(['detailBarangKeluars.produk'])->findOrFail($id);
        $produks = Produk::orderBy('nama')->get();
        
        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-keluar.detail', compact('barangKeluar', 'produks'));
    }
    
    /**
     * Simpan detail produk ke transaksi
     */
    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barangKeluar = BarangKeluar::findOrFail($id);
        $produk = Produk::findOrFail($request->produk_id);

        // Validasi stok
        if ($produk->stok < $request->jumlah) {
            return back()->with('error', 'Stok produk tidak mencukupi!');
        }

        DB::beginTransaction();
        try {
            // Cek apakah produk sudah ada di transaksi ini
            $existing = DetailBarangKeluar::where('barang_keluar_id', $id)
                ->where('produk_id', $request->produk_id)
                ->first();
                
            if ($existing) {
                // Update jumlah jika produk sudah ada
                $totalJumlah = $existing->jumlah + $request->jumlah;
                
                // Validasi stok untuk total jumlah
                if ($produk->stok < $totalJumlah) {
                    return back()->with('error', 'Stok produk tidak mencukupi untuk total jumlah yang diminta!');
                }
                
                $existing->jumlah = $totalJumlah;
                $existing->subtotal = $existing->harga_satuan * $existing->jumlah;
                $existing->save();
            } else {
                // Tambah produk baru
                $harga_satuan = $produk->harga;
                $subtotal = $request->jumlah * $harga_satuan;
                
                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'produk_id' => $request->produk_id,
                    'jumlah' => $request->jumlah,
                    'harga_satuan' => $harga_satuan,
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.detail', $barangKeluar->id)
                ->with('success', 'Produk berhasil ditambahkan ke transaksi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus detail produk dari transaksi
     */
    public function destroyDetail(Request $request, $id)
    {
        $detail = DetailBarangKeluar::findOrFail($id);
        $barangKeluarId = $detail->barang_keluar_id;

        $detail->delete();
        
        $routePrefix = $this->getRoutePrefix($request);

        return redirect()->route($routePrefix . '.barang-keluar.detail', $barangKeluarId)
            ->with('success', 'Produk berhasil dihapus dari transaksi.');
    }

    /**
     * Finalisasi transaksi barang keluar dan update stok
     */
    public function finalize(Request $request, $id)
    {
        $barangKeluar = BarangKeluar::with('detailBarangKeluars.produk')->findOrFail($id);
        
        // Validasi: Transaksi harus memiliki minimal 1 produk
        if ($barangKeluar->detailBarangKeluars->count() === 0) {
            return back()->with('error', 'Tidak dapat menyelesaikan transaksi. Belum ada produk yang ditambahkan!');
        }
        
        DB::beginTransaction();
        try {
            // Calculate total price
            $totalHarga = 0;
            
            foreach ($barangKeluar->detailBarangKeluars as $detail) {
                $produk = $detail->produk;
                
                // Validasi stok lagi sebagai double-check
                if ($produk->stok < $detail->jumlah) {
                    throw new \Exception("Stok {$produk->nama} tidak mencukupi!");
                }
                
                // Kurangi stok
                $produk->stok -= $detail->jumlah;
                $produk->save();
                
                $totalHarga += $detail->subtotal;
            }
            
            // Update status transaksi dan harga total
            $barangKeluar->status = 'finalized';
            $barangKeluar->total_harga = $totalHarga;
            $barangKeluar->finalized_at = now();
            $barangKeluar->save();
            
            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.invoice', $barangKeluar->id)
                ->with('success', 'Transaksi barang keluar berhasil diselesaikan dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function invoice(Request $request, BarangKeluar $barangKeluar)
    {
        // Load relasi yang diperlukan
        $barangKeluar->load(['detailBarangKeluars.produk']);
        
        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-keluar.invoice', compact('barangKeluar'));
    }

    /**
     * Hapus transaksi barang keluar beserta detail (jika belum finalized)
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::with('detailBarangKeluars')->findOrFail($id);
            
            // Jika transaksi sudah diselesaikan, kembalikan stok
            if ($barangKeluar->status === 'finalized') {
                foreach ($barangKeluar->detailBarangKeluars as $detail) {
                    $produk = Produk::find($detail->produk_id);
                    if ($produk) {
                        $produk->increment('stok', $detail->jumlah);
                    }
                }
            }
            
            // Hapus semua detail terlebih dahulu
            $barangKeluar->detailBarangKeluars()->delete();
            
            // Hapus header transaksi
            $barangKeluar->delete();
            
            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.index')
                ->with('success', 'Transaksi barang keluar berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            // Periksa apakah ada data untuk diexport - hanya yang sudah finalized
            $checkData = BarangKeluar::where('status', 'finalized')
                ->whereBetween('tanggal_keluar', [$startDate, $endDate])
                ->count();
            
            if ($checkData == 0) {
                return back()->with('error', 'Tidak ada data untuk rentang tanggal tersebut.');
            }

            $filename = 'Laporan_Barang_Keluar_' . date('d-m-Y') . '.xlsx';
            
            // Buat file Excel dengan multiple sheet
            return Excel::download(new class($request) implements WithMultipleSheets {
                protected $request;
                
                public function __construct(Request $request)
                {
                    $this->request = $request;
                }
                
                public function sheets(): array
                {
                    return [
                        new BarangKeluarExport($this->request),
                        new DetailBarangKeluarExport($this->request),
                    ];
                }
            }, $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate nomor transaksi otomatis dengan format BK-YYMMDD-XXX
     */
    private function generateNomorTransaksi()
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = "BK-{$today}-";
        
        // Cari nomor terakhir dengan prefix hari ini
        $lastRecord = BarangKeluar::where('kode_barang_keluar', 'like', "{$prefix}%")
            ->orderBy('kode_barang_keluar', 'desc')
            ->first();
            
        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->kode_barang_keluar, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format dengan leading zeros
        $suffix = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return "{$prefix}{$suffix}";
    }
}