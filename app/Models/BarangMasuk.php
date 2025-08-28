<?php

namespace App\Models;

use App\Models\DetailBarangMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_masuk',
        'supplier',
        'nomor_transaksi',
    ];

    public function details()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }
}
