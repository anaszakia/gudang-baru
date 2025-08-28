<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\BarangMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailBarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_masuk_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
