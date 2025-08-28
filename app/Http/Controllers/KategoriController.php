<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        //search and pagination
        $keyword = $request->query('search');

        $kategoris = Kategori::query()
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('nama',  'like', "%{$keyword}%")
                       ->orWhere('kode', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends(['search' => $keyword]);

        return view('admin-super.kategori.index', compact('kategoris', 'keyword'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|max:255',
        ]);

        // generate kode unik
        do {
            $kode = 'KTG-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (Kategori::where('kode', $kode)->exists());

        $validated['kode'] = $kode;

        Kategori::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $rules = [
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|max:255',
        ];

        $validated = $request->validate($rules);

        $kategori->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}