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
    public function index(Request $request)
    {
        $keyword = $request->query("search");

        // Ambil data + eager load details & produk untuk hindari N+1
        $barangKeluars = BarangKeluar::with(["detailBarangKeluars.produk"])
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where("nomor_transaksi", "like", "%{$keyword}%")
                       ->orWhere("penerima", "like", "%{$keyword}%");
                });
            })
            ->orderBy("created_at", "asc")
            ->paginate(10)
            ->appends(["search" => $keyword]);

        $role = $request->user()->role;
        $viewPrefix = $role === "admin-gudang" ? "admin-gudang" : "admin-super";
        
        return view($viewPrefix . ".barang-keluar.index", compact("barangKeluars", "keyword"));
    }
}
