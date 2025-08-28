<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk; // pastikan model Produk sudah dibuat

class ProdukController extends Controller
{
    /**
     * Get route prefix based on user role
     */
    private function getRoutePrefix(Request $request)
    {
        $role = $request->user()->role;
        return $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
    }

    /**
     * Get view prefix based on user role
     */
    private function getViewPrefix(Request $request)
    {
        $role = $request->user()->role;
        return $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';
    }

    public function index(Request $request)
    {
        // search + pagination
        $keyword = $request->query('search');

        $produks = Produk::query()
            ->with('kategori') // eager load kategori untuk menghindari N+1 query
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('nama', 'like', "%{$keyword}%")
                       ->orWhere('kode', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends(['search' => $keyword]);

        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        // Tentukan view berdasarkan role
        $viewPrefix = $this->getViewPrefix($request);
        
        return view($viewPrefix . '.produk.index', compact('produks', 'keyword', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
            'deskripsi'  => 'nullable|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga'      => 'required|numeric|min:0',
        ]);

        // generate kode unik
        do {
            $kode = 'PRD-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (Produk::where('kode', $kode)->exists());

        $validated['kode'] = $kode;
        $validated['stok'] = 0; // set default stok 0 untuk produk baru

        Produk::create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Produk $produk)
    {
        $rules = [
            'nama'       => 'required|string|max:100',
            'deskripsi'  => 'nullable|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga'      => 'required|numeric|min:0',
        ];

        $validated = $request->validate($rules);

        $produk->update($validated);

        return back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Request $request, Produk $produk)
    {
        // Cek apakah produk masih memiliki stok
        if ($produk->stok > 0) {
            return back()->with('error', 'Tidak dapat menghapus produk yang masih memiliki stok!');
        }

        // Cek apakah produk pernah digunakan dalam transaksi
        $hasTransactions = false;
        
        // Cek di detail barang masuk
        if ($produk->detailBarangMasuks()->exists()) {
            $hasTransactions = true;
        }
        
        // Cek di detail barang keluar
        if ($produk->detailBarangKeluars()->exists()) {
            $hasTransactions = true;
        }

        if ($hasTransactions) {
            return back()->with('error', 'Tidak dapat menghapus produk yang sudah pernah digunakan dalam transaksi!');
        }

        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Show product details (jika diperlukan)
     */
    public function show(Request $request, Produk $produk)
    {
        $produk->load('kategori');
        
        $viewPrefix = $this->getViewPrefix($request);
        
        return view($viewPrefix . '.produk.show', compact('produk'));
    }

    /**
     * API endpoint untuk pencarian produk (untuk AJAX)
     */
    public function search(Request $request)
    {
        $keyword = $request->query('q');
        
        $produks = Produk::query()
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('kode', 'like', "%{$keyword}%");
            })
            ->where('stok', '>', 0) // hanya produk yang ada stoknya
            ->orderBy('nama')
            ->limit(10)
            ->get(['id', 'nama', 'kode', 'harga', 'stok']);

        return response()->json($produks);
    }
}