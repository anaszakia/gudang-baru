<?php

namespace App\Http\Controllers;

use App\Exports\PenawaranExport;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\DetailPenawaran;
use App\Models\Penawaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminPenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penawarans = Penawaran::with(['user', 'approver'])
            ->latest()
            ->paginate(10);
            
        return view('admin.penawaran.index', compact('penawarans'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Penawaran $penawaran)
    {
        $details = DetailPenawaran::with('produk.kategori')
            ->where('penawaran_id', $penawaran->id)
            ->get();
            
        return view('admin.penawaran.show', compact('penawaran', 'details'));
    }

    /**
     * Show the approval form.
     */
    public function showApprovalForm(Penawaran $penawaran)
    {
        // Only show if status is still pending
        if ($penawaran->status !== 'pending') {
            return redirect()->route('admin-super.penawaran.index')
                ->with('error', 'Penawaran ini sudah diproses sebelumnya.');
        }

        $details = DetailPenawaran::with(['produk.kategori'])
            ->where('penawaran_id', $penawaran->id)
            ->get();
            
        // Check product stock
        $stockIssues = [];
        foreach ($details as $detail) {
            $produk = $detail->produk;
            $currentStock = $produk->stokTersedia();
            if ($currentStock < $detail->jumlah) {
                $stockIssues[] = [
                    'produk' => $produk->nama_produk,
                    'requested' => $detail->jumlah,
                    'available' => $currentStock
                ];
            }
        }
            
        return view('admin.penawaran.approve', compact('penawaran', 'details', 'stockIssues'));
    }

    /**
     * Process approval.
     */
    public function processApproval(Request $request, Penawaran $penawaran)
    {
        // Only process if status is still pending
        if ($penawaran->status !== 'pending') {
            return redirect()->route('admin-super.penawaran.index')
                ->with('error', 'Penawaran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'approval_action' => 'required|in:approve,reject',
            'approval_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            if ($request->approval_action === 'approve') {
                // Check product stock
                $details = DetailPenawaran::with('produk')
                    ->where('penawaran_id', $penawaran->id)
                    ->get();
                
                foreach ($details as $detail) {
                    $produk = $detail->produk;
                    $currentStock = $produk->stokTersedia();
                    if ($currentStock < $detail->jumlah) {
                        throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi.");
                    }
                }
                
                // Create BarangKeluar
                $barangKeluar = new BarangKeluar([
                    'kode_barang_keluar' => $this->generateKodeBarangKeluar(),
                    'tanggal_keluar' => now(),
                    'penerima' => $penawaran->nama_pelanggan,
                    'alamat_penerima' => $penawaran->alamat_pelanggan,
                    'telepon_penerima' => $penawaran->telepon_pelanggan,
                    'total_harga' => $penawaran->total_harga,
                    'catatan' => $request->approval_notes ?? $penawaran->catatan,
                    'user_id' => Auth::id(),
                    'penawaran_id' => $penawaran->id,
                ]);
                
                $barangKeluar->save();
                
                // Create DetailBarangKeluar records
                foreach ($details as $detail) {
                    $detailBarangKeluar = new DetailBarangKeluar([
                        'barang_keluar_id' => $barangKeluar->id,
                        'produk_id' => $detail->produk_id,
                        'jumlah' => $detail->jumlah,
                        'harga' => $detail->harga,
                        'subtotal' => $detail->subtotal,
                    ]);
                    
                    $detailBarangKeluar->save();
                }
                
                // Update penawaran status
                $penawaran->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
                
                // Finalize the barang keluar (update stock)
                $this->finalizeBarangKeluar($barangKeluar);
                
                $successMessage = 'Penawaran berhasil disetujui dan Barang Keluar telah dibuat.';
            } else {
                // Reject penawaran
                $penawaran->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'catatan' => $penawaran->catatan . "\n\nAlasan Ditolak: " . ($request->approval_notes ?? 'Tidak ada alasan yang diberikan.'),
                ]);
                
                $successMessage = 'Penawaran berhasil ditolak.';
            }
            
            DB::commit();
            
            return redirect()->route(Auth::user()->role . '.penawaran.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Finalize barang keluar and update stock.
     */
    private function finalizeBarangKeluar(BarangKeluar $barangKeluar)
    {
        $details = DetailBarangKeluar::with('produk')
            ->where('barang_keluar_id', $barangKeluar->id)
            ->get();
            
        foreach ($details as $detail) {
            // Update product stock logic happens in the model event listeners
        }
        
        $barangKeluar->update([
            'status' => 'finalized',
            'finalized_at' => now(),
        ]);
    }

    /**
     * Export penawaran data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        return Excel::download(
            new PenawaranExport($startDate, $endDate), 
            'penawaran_' . date('YmdHis') . '.xlsx'
        );
    }
    
    /**
     * Generate nomor transaksi otomatis dengan format BK-YYMMDD-XXX
     */
    private function generateKodeBarangKeluar()
    {
        $today = now()->format('Ymd');
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
