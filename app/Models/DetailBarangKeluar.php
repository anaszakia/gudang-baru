<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\BarangKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailBarangKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'barang_keluar_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
