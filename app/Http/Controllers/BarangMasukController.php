<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DetailBarangMasuk;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangMasukExport;
use App\Exports\DetailBarangMasukExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BarangMasukController extends Controller
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
        $barangMasuks = BarangMasuk::with(['details.produk'])
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('nomor_transaksi', 'like', "%{$keyword}%")
                       ->orWhere('supplier', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends(['search' => $keyword]);

        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-masuk.index', compact('barangMasuks', 'keyword'));
    }

    public function create(Request $request)
    {
        $nomorTransaksi = $this->generateNomorTransaksi();
        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-masuk.create', compact('nomorTransaksi'));
    }

    /**
     * Generate Nomor Transaksi MSK-YYYYMMDD-XX
     */
    private function generateNomorTransaksi()
    {
        $tanggal = Carbon::now()->format('Ymd');

        // Ambil transaksi terakhir hari ini
        $last = BarangMasuk::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            // Ambil 2 digit terakhir
            $lastNumber = (int) substr($last->nomor_transaksi, -2);
            $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = "01";
        }

        return "MSK-" . $tanggal . "-" . $nextNumber;
    }

    /**
     * Simpan Header Transaksi Barang Masuk
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'supplier'      => 'required|string|max:150',
        ]);

        DB::beginTransaction();
        try {
            $barangMasuk = BarangMasuk::create([
                'nomor_transaksi' => $this->generateNomorTransaksi(),
                'tanggal_masuk'   => $request->tanggal_masuk,
                'supplier'        => $request->supplier,
            ]);

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-masuk.detail', $barangMasuk->id)
                ->with('success', 'Header transaksi berhasil disimpan. Silakan tambahkan produk.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Tampilkan halaman detail barang masuk untuk menambah produk
     */
    public function detail(Request $request, $id)
    {
        $barangMasuk = BarangMasuk::with(['details.produk'])->findOrFail($id);
        $produks = Produk::orderBy('nama')->get();
        
        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-masuk.detail', compact('barangMasuk', 'produks'));
    }
    
    /**
     * Tambah detail produk ke transaksi barang masuk
     */
    public function storeDetail(Request $request, $id)
    {
        // Tentukan produk_id berdasarkan metode input
        if ($request->input('input_method') === 'code') {
            $produkId = $request->input('produk_id_by_code');
            if (!$produkId) {
                return back()->with('error', 'Kode produk tidak valid');
            }
        } else {
            $produkId = $request->input('produk_id');
        }
        
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);
        
        if (empty($produkId)) {
            return back()->with('error', 'Harap pilih produk');
        }

        DB::beginTransaction();
        try {
            $barangMasuk = BarangMasuk::findOrFail($id);
            $produk = Produk::findOrFail($produkId);
            $hargaSatuan = $produk->harga;
            $subtotal = $hargaSatuan * $request->jumlah;
            
            // Cek apakah produk sudah ada di transaksi ini
            $existing = DetailBarangMasuk::where('barang_masuk_id', $id)
                ->where('produk_id', $produkId)
                ->first();
                
            if ($existing) {
                // Update jumlah jika produk sudah ada
                $existing->jumlah += $request->jumlah;
                $existing->subtotal = $existing->harga_satuan * $existing->jumlah;
                $existing->save();
            } else {
                // Tambah produk baru
                DetailBarangMasuk::create([
                    'barang_masuk_id' => $id,
                    'produk_id'       => $produkId,
                    'jumlah'          => $request->jumlah,
                    'harga_satuan'    => $hargaSatuan,
                    'subtotal'        => $subtotal,
                ]);
            }
            
            // Tambah stok produk
            $produk->increment('stok', $request->jumlah);

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-masuk.detail', $id)
                ->with('success', 'Produk berhasil ditambahkan ke transaksi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus item detail dari transaksi
     */
    public function destroyDetail(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $detail = DetailBarangMasuk::findOrFail($id);
            $barangMasukId = $detail->barang_masuk_id;
            
            // Kurangi stok produk karena item dibatalkan
            $produk = Produk::findOrFail($detail->produk_id);
            $produk->decrement('stok', $detail->jumlah);
            
            $detail->delete();
            
            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-masuk.detail', $barangMasukId)
                ->with('success', 'Item berhasil dihapus dari transaksi dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Selesaikan transaksi barang masuk
     */
    public function finalize(Request $request, $id)
    {
        $barangMasuk = BarangMasuk::with('details')->findOrFail($id);
        
        if ($barangMasuk->details->isEmpty()) {
            $routePrefix = $this->getRoutePrefix($request);
            return redirect()->route($routePrefix . '.barang-masuk.detail', $id)
                ->with('error', 'Tidak dapat menyelesaikan transaksi kosong. Tambahkan minimal 1 produk.');
        }
        
        // Stok sudah diperbarui saat menambahkan produk ke transaksi
        // Kita bisa menambahkan logika tambahan di sini jika diperlukan
        
        $routePrefix = $this->getRoutePrefix($request);
        
        return redirect()->route($routePrefix . '.barang-masuk.index')
            ->with('success', 'Transaksi barang masuk berhasil diselesaikan dan stok telah diperbarui.');
    }

    public function invoice(Request $request, BarangMasuk $barangMasuk)
    {
        // Load relasi yang diperlukan
        $barangMasuk->load(['details.produk']);
        
        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
        
        return view($viewPrefix . '.barang-masuk.invoice', compact('barangMasuk'));
    }

    /**
     * Hapus transaksi barang masuk beserta detail dan penyesuaian stok
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $barangMasuk = BarangMasuk::with('details.produk')->findOrFail($id);
            
            // Kurangi stok produk untuk setiap detail sebelum menghapus
            foreach ($barangMasuk->details as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    // Pastikan stok tidak menjadi negatif
                    $newStok = max(0, $produk->stok - $detail->jumlah);
                    $produk->update(['stok' => $newStok]);
                }
            }
            
            // Hapus semua detail terlebih dahulu
            $barangMasuk->details()->delete();
            
            // Hapus header transaksi
            $barangMasuk->delete();
            
            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-masuk.index')
                ->with('success', 'Transaksi barang masuk berhasil dihapus dan stok telah disesuaikan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }
    
    /**
     * Export data barang masuk ke Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            // Periksa apakah ada data untuk diexport
            $checkData = BarangMasuk::whereBetween('tanggal_masuk', [$startDate, $endDate])->count();
            
            if ($checkData == 0) {
                return back()->with('error', 'Tidak ada data untuk rentang tanggal tersebut.');
            }
            
            $filename = 'Laporan_Barang_Masuk_' . date('d-m-Y') . '.xlsx';
        
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
                        new BarangMasukExport($this->request),
                        new DetailBarangMasukExport($this->request),
                    ];
                }
            }, $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
        }
    }
}