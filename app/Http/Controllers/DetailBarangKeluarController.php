<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailBarangKeluar;
use Illuminate\Support\Facades\DB;

class DetailBarangKeluarController extends Controller
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
     * Tampilkan form untuk tambah detail barang keluar.
     */
    public function create(Request $request, $barangKeluarId)
    {
        $barangKeluar = BarangKeluar::findOrFail($barangKeluarId);
        $produks = Produk::orderBy('nama')->get();

        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';

        return view($viewPrefix . '.barang-keluar.detail.create', compact('barangKeluar', 'produks'));
    }

    /**
     * Simpan detail barang keluar ke database - Single item version
     */
    public function store(Request $request, $barangKeluarId)
    {
        // Validasi tanpa harga_satuan karena diambil dari tabel produks
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barangKeluar = BarangKeluar::findOrFail($barangKeluarId);
        $produk = Produk::findOrFail($request->produk_id);

        // Ambil harga dari tabel produks
        $hargaSatuan = $produk->harga;

        // Validasi stok
        if ($produk->stok < $request->jumlah) {
            return back()->with('error', "Stok {$produk->nama} tidak mencukupi. Stok tersedia: {$produk->stok}")
                        ->withInput();
        }

        DB::beginTransaction();
        try {
            $subtotal = $request->jumlah * $hargaSatuan;

            // Cek apakah produk sudah ada di transaksi ini
            $existing = DetailBarangKeluar::where('barang_keluar_id', $barangKeluarId)
                ->where('produk_id', $request->produk_id)
                ->first();
                
            if ($existing) {
                // Update jumlah jika produk sudah ada
                $totalJumlah = $existing->jumlah + $request->jumlah;
                
                // Validasi stok untuk total jumlah
                if ($produk->stok < $totalJumlah - $existing->jumlah) {
                    return back()->with('error', "Stok {$produk->nama} tidak mencukupi untuk total jumlah yang diminta!")
                                ->withInput();
                }
                
                $existing->jumlah = $totalJumlah;
                $existing->subtotal = $existing->harga_satuan * $existing->jumlah;
                $existing->save();
                
                // Kurangi stok hanya untuk jumlah tambahan
                $produk->decrement('stok', $request->jumlah);
            } else {
                // Tambah produk baru dengan harga dari tabel produks
                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'produk_id' => $request->produk_id,
                    'jumlah' => $request->jumlah,
                    'harga_satuan' => $hargaSatuan, // Menggunakan harga dari tabel produks
                    'subtotal' => $subtotal,
                ]);
                
                // Kurangi stok
                $produk->decrement('stok', $request->jumlah);
            }

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.detail', $barangKeluarId)
                            ->with('success', 'Detail barang keluar berhasil ditambahkan dan stok telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
}

    /**
     * Simpan multiple detail barang keluar ke database - Batch version
     */
    public function storeBatch(Request $request, $barangKeluarId)
    {
        $request->validate([
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
            // Hapus validasi harga_satuan karena diambil dari tabel produks
        ]);

        $barangKeluar = BarangKeluar::findOrFail($barangKeluarId);

        DB::beginTransaction();
        try {
            foreach ($request->produk_id as $index => $produkId) {
                $jumlah = $request->jumlah[$index];
                
                // Ambil harga dari tabel produks
                $produk = Produk::findOrFail($produkId);
                $harga = $produk->harga; // Menggunakan harga dari tabel produks
                $subtotal = $jumlah * $harga;

                // Validasi stok
                if ($produk->stok < $jumlah) {
                    DB::rollback();
                    return back()->with('error', "Stok {$produk->nama} tidak mencukupi. Stok tersedia: {$produk->stok}")
                                ->withInput();
                }

                // Simpan detail barang keluar
                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga, // Menggunakan harga dari tabel produks
                    'subtotal' => $subtotal,
                ]);
                
                // Kurangi stok
                $produk->decrement('stok', $jumlah);
            }

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.detail', $barangKeluarId)
                            ->with('success', 'Detail barang keluar berhasil ditambahkan dan stok telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Hapus detail barang keluar
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $detail = DetailBarangKeluar::findOrFail($id);
            $barangKeluarId = $detail->barang_keluar_id;
            
            // Kembalikan stok produk
            $produk = Produk::findOrFail($detail->produk_id);
            $produk->increment('stok', $detail->jumlah);
            
            $detail->delete();

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.detail', $barangKeluarId)
                             ->with('success', 'Detail barang keluar berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Edit detail barang keluar
     */
    public function edit(Request $request, $id)
    {
        $detail = DetailBarangKeluar::with(['barangKeluar', 'produk'])->findOrFail($id);
        $produks = Produk::orderBy('nama')->get();

        $role = $request->user()->role;
        $viewPrefix = $role === 'admin-gudang' ? 'admin-gudang' : 'admin-super';

        return view($viewPrefix . '.barang-keluar.detail.edit', compact('detail', 'produks'));
    }

    /**
     * Update detail barang keluar
     */
    public function update(Request $request, $id)
    {
        // Validasi tanpa harga_satuan
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $detail = DetailBarangKeluar::findOrFail($id);
            $oldProdukId = $detail->produk_id;
            $oldJumlah = $detail->jumlah;
            $produkLama = Produk::findOrFail($oldProdukId);
            
            // Ambil harga dari produk baru
            $produkBaru = Produk::findOrFail($request->produk_id);
            $hargaSatuan = $produkBaru->harga;
            
            // Jika produk berubah
            if ($oldProdukId != $request->produk_id) {
                // Kembalikan stok produk lama
                $produkLama->increment('stok', $oldJumlah);
                
                // Validasi stok produk baru
                if ($produkBaru->stok < $request->jumlah) {
                    DB::rollback();
                    return back()->with('error', "Stok {$produkBaru->nama} tidak mencukupi. Stok tersedia: {$produkBaru->stok}")
                                ->withInput();
                }
                
                // Kurangi stok produk baru
                $produkBaru->decrement('stok', $request->jumlah);
            } else {
                // Jika produk sama, hitung selisih jumlah
                $selisihJumlah = $request->jumlah - $oldJumlah;
                
                if ($selisihJumlah > 0) {
                    // Jika jumlah bertambah, validasi stok
                    if ($produkLama->stok < $selisihJumlah) {
                        DB::rollback();
                        return back()->with('error', "Stok {$produkLama->nama} tidak mencukupi. Stok tersedia: {$produkLama->stok}")
                                    ->withInput();
                    }
                    // Kurangi stok
                    $produkLama->decrement('stok', $selisihJumlah);
                } elseif ($selisihJumlah < 0) {
                    // Jika jumlah berkurang, tambahkan stok
                    $produkLama->increment('stok', abs($selisihJumlah));
                }
            }
            
            // Update detail dengan harga dari tabel produks
            $detail->update([
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $hargaSatuan, // Menggunakan harga dari tabel produks
                'subtotal' => $request->jumlah * $hargaSatuan,
            ]);

            DB::commit();
            
            $routePrefix = $this->getRoutePrefix($request);
            
            return redirect()->route($routePrefix . '.barang-keluar.detail', $detail->barang_keluar_id)
                            ->with('success', 'Detail barang keluar berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }
}