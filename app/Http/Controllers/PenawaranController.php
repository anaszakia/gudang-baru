<?php

namespace App\Http\Controllers;

use App\Exports\DetailPenawaranExport;
use App\Exports\PenawaranExport;
use App\Models\Penawaran;
use App\Models\Produk;
use App\Models\DetailPenawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penawarans = Penawaran::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('sales.penawaran.index', compact('penawarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales.penawaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'email_pelanggan' => 'nullable|email|max:255',
            'tanggal_penawaran' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $penawaran = new Penawaran([
                'kode_penawaran' => Penawaran::generateKode(),
                'user_id' => Auth::id(),
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'telepon_pelanggan' => $request->telepon_pelanggan,
                'email_pelanggan' => $request->email_pelanggan,
                'tanggal_penawaran' => $request->tanggal_penawaran,
                'catatan' => $request->catatan,
                'status' => 'pending',
            ]);
            
            $penawaran->save();
            
            DB::commit();
            
            return redirect()->route('sales.penawaran.detail', $penawaran)
                ->with('success', 'Penawaran berhasil dibuat. Silakan tambahkan detail produk.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $details = DetailPenawaran::with('produk.kategori')
            ->where('penawaran_id', $penawaran->id)
            ->get();
            
        return view('sales.penawaran.show', compact('penawaran', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is still editable (pending)
        if (!$penawaran->canEdit()) {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Penawaran yang sudah diproses tidak dapat diubah.');
        }
        
        return view('sales.penawaran.edit', compact('penawaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is still editable (pending)
        if (!$penawaran->canEdit()) {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Penawaran yang sudah diproses tidak dapat diubah.');
        }

        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'email_pelanggan' => 'nullable|email|max:255',
            'tanggal_penawaran' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $penawaran->update([
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'telepon_pelanggan' => $request->telepon_pelanggan,
                'email_pelanggan' => $request->email_pelanggan,
                'tanggal_penawaran' => $request->tanggal_penawaran,
                'catatan' => $request->catatan,
            ]);
            
            DB::commit();
            
            return redirect()->route('sales.penawaran.index')
                ->with('success', 'Penawaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is still editable (pending)
        if (!$penawaran->canEdit()) {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Penawaran yang sudah diproses tidak dapat dihapus.');
        }

        DB::beginTransaction();
        
        try {
            // Delete details and penawaran
            $penawaran->details()->delete();
            $penawaran->delete();
            
            DB::commit();
            
            return redirect()->route('sales.penawaran.index')
                ->with('success', 'Penawaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the detail page for penawaran.
     */
    public function detail(Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $details = DetailPenawaran::with('produk.kategori')
            ->where('penawaran_id', $penawaran->id)
            ->get();
            
        $produks = Produk::with('kategori')
            ->get();
            
        return view('sales.penawaran.detail', compact('penawaran', 'details', 'produks'));
    }

    /**
     * Store a new detail for penawaran.
     */
    public function storeDetail(Request $request, Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is still editable (pending)
        if (!$penawaran->canEdit()) {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Penawaran yang sudah diproses tidak dapat diubah.');
        }

        // Validate basic requirements
        $rules = [
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ];
        
        // Check which input method was used
        if ($request->has('produk_id_by_code') && $request->produk_id_by_code) {
            $rules['produk_id_by_code'] = 'required|exists:produks,id';
            $productIdField = 'produk_id_by_code';
        } else {
            $rules['produk_id'] = 'required|exists:produks,id';
            $productIdField = 'produk_id';
        }
        
        $request->validate($rules);

        DB::beginTransaction();
        
        try {
            // Get the product ID based on which input method was used
            $produkId = $request->has('produk_id_by_code') && $request->produk_id_by_code 
                ? $request->produk_id_by_code 
                : $request->produk_id;
            
            $produk = Produk::findOrFail($produkId);
            
            // Calculate subtotal
            $subtotal = $request->jumlah * $request->harga;
            
            // Create detail
            $detail = new DetailPenawaran([
                'penawaran_id' => $penawaran->id,
                'produk_id' => $produkId,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
                'subtotal' => $subtotal,
            ]);
            
            $detail->save();
            
            // Update total harga on penawaran
            $totalHarga = DetailPenawaran::where('penawaran_id', $penawaran->id)->sum('subtotal');
            $penawaran->update(['total_harga' => $totalHarga]);
            
            DB::commit();
            
            return redirect()->route('sales.penawaran.detail', $penawaran)
                ->with('success', 'Detail produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove a detail from penawaran.
     */
    public function destroyDetail(DetailPenawaran $detailPenawaran)
    {
        $penawaran = Penawaran::findOrFail($detailPenawaran->penawaran_id);
        
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is still editable (pending)
        if (!$penawaran->canEdit()) {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Penawaran yang sudah diproses tidak dapat diubah.');
        }

        DB::beginTransaction();
        
        try {
            // Delete detail
            $detailPenawaran->delete();
            
            // Update total harga on penawaran
            $totalHarga = DetailPenawaran::where('penawaran_id', $penawaran->id)->sum('subtotal');
            $penawaran->update(['total_harga' => $totalHarga]);
            
            DB::commit();
            
            return redirect()->route('sales.penawaran.detail', $penawaran)
                ->with('success', 'Detail produk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Submit penawaran for approval.
     */
    public function submit(Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is still editable (pending)
        if (!$penawaran->canEdit()) {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Penawaran yang sudah diproses tidak dapat diubah.');
        }
        
        // Check if penawaran has details
        if ($penawaran->details()->count() == 0) {
            return redirect()->route('sales.penawaran.detail', $penawaran)
                ->with('error', 'Penawaran harus memiliki minimal 1 detail produk.');
        }
        
        return redirect()->route('sales.penawaran.index')
            ->with('success', 'Penawaran berhasil diajukan untuk persetujuan.');
    }

    /**
     * Generate and display the invoice.
     */
    public function invoice(Penawaran $penawaran)
    {
        // Check if the penawaran belongs to the current user
        if ($penawaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the penawaran is approved
        if ($penawaran->status !== 'approved') {
            return redirect()->route('sales.penawaran.index')
                ->with('error', 'Hanya penawaran yang sudah disetujui yang dapat dicetak invoicenya.');
        }

        $details = DetailPenawaran::with('produk.kategori')
            ->where('penawaran_id', $penawaran->id)
            ->get();
            
        return view('sales.penawaran.invoice', compact('penawaran', 'details'));
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
        $userId = Auth::id();
        
        return Excel::download(
            new PenawaranExport($startDate, $endDate, $userId), 
            'penawaran_' . date('YmdHis') . '.xlsx'
        );
    }
    
    /**
     * Get product data by ID for AJAX request.
     */
    public function getProduct(Produk $produk)
    {
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $produk->id,
                'kode' => $produk->kode,
                'nama' => $produk->nama,
                'harga_jual' => $produk->harga_jual
            ]
        ]);
    }
    
    /**
     * Get product data by code for AJAX request.
     */
    public function getProductByCode($kode)
    {
        $produk = Produk::where('kode', $kode)->first();
        
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $produk->id,
                'kode' => $produk->kode,
                'nama' => $produk->nama,
                'harga_jual' => $produk->harga_jual
            ]
        ]);
    }
}
