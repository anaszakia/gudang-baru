<?php

namespace App\Models;

use App\Models\User;
use App\Models\BarangKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $fillable = [
        'barang_keluar_id',
        'driver_id',
        'metode_pengiriman',
        'status_pengiriman',
        'catatan',
        'waktu_mulai',
        'waktu_selesai'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Get the barang keluar associated with this pengiriman
     */
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    /**
     * Get the driver for this pengiriman
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    
    /**
     * Get formatted status name
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'belum_dikirim' => 'Belum Dikirim',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'istirahat' => 'Istirahat',
            'selesai' => 'Selesai'
        ];
        
        return $statuses[$this->status_pengiriman] ?? 'Unknown';
    }
    
    /**
     * Get duration of delivery in hours and minutes
     */
    public function getDurationAttribute()
    {
        if (!$this->waktu_mulai) {
            return 'Belum dimulai';
        }
        
        $endTime = $this->waktu_selesai ?? now();
        $diffInMinutes = $this->waktu_mulai->diffInMinutes($endTime);
        
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        
        return sprintf('%d jam %d menit', $hours, $minutes);
    }
}
