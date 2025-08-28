<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\DetailBarangMasuk;
use App\Models\DetailBarangKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'harga',
        'stok',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detailBarangMasuks()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    public function detailBarangKeluars()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }
    
    /**
     * Get available stock for this product
     * Calculates by summing all incoming items and subtracting outgoing items
     * 
     * @return int
     */
    public function stokTersedia()
    {
        // Get total items that came in from DetailBarangMasuk
        $totalMasuk = $this->detailBarangMasuks()
            ->sum('jumlah');
            
        // Get total items that went out from DetailBarangKeluar
        $totalKeluar = $this->detailBarangKeluars()
            ->sum('jumlah');
            
        // Available stock is total in minus total out
        return $totalMasuk - $totalKeluar;
    }
}
