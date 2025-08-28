<?php

namespace App\Models;

use App\Models\DetailBarangKeluar;
use App\Models\Penawaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_barang_keluar',
        'tanggal_keluar',
        'penerima',
        'alamat_penerima',
        'telepon_penerima',
        'total_harga',
        'catatan',
        'status',
        'user_id',
        'finalized_at',
        'payment',
        'penawaran_id'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_keluar' => 'date',
        'total_harga' => 'decimal:2',
        'finalized_at' => 'datetime',
    ];

    /**
     * Get the details for this barang keluar
     */
    public function detailBarangKeluars()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }
    
    /**
     * Get the penawaran associated with this barang keluar
     */
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class);
    }
    
    /**
     * Get the user who finalized this barang keluar
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
