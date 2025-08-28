<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Models\DetailBarangMasuk;

class DetailBarangMasukController extends Controller
{
    /**
     * Tampilkan form untuk tambah detail barang masuk.
     */
    public function create($barangMasukId)
    {
        $barangMasuk = BarangMasuk::findOrFail($barangMasukId);
        $produks = Produk::all();

        return view('barang_masuk.detail.create', compact('barangMasuk', 'produks'));
    }

    /**
     * Simpan detail barang masuk ke database.
     */
    public function store(Request $request, $barangMasukId)
    {
        $request->validate([
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'numeric|min:0',
        ]);

        $barangMasuk = BarangMasuk::findOrFail($barangMasukId);

        foreach ($request->produk_id as $index => $produkId) {
            $jumlah = $request->jumlah[$index];
            $harga = $request->harga_satuan[$index];
            $subtotal = $jumlah * $harga;

            DetailBarangMasuk::create([
                'barang_masuk_id' => $barangMasuk->id,
                'produk_id' => $produkId,
                'jumlah' => $jumlah,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ]);

            // Update stok produk
            $produk = Produk::find($produkId);
            $produk->increment('stok', $jumlah);
        }

        return redirect()->route('barang-masuk.show', $barangMasukId)
                         ->with('success', 'Detail barang masuk berhasil ditambahkan.');
    }

    /**
     * Hapus detail barang masuk
     */
    public function destroy($id)
    {
        $detail = DetailBarangMasuk::findOrFail($id);

        // Kembalikan stok produk
        $produk = $detail->produk;
        $produk->decrement('stok', $detail->jumlah);

        $detail->delete();

        return back()->with('success', 'Detail barang masuk berhasil dihapus.');
    }
}
