<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penawaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_penawaran',
        'user_id',
        'nama_pelanggan',
        'alamat_pelanggan',
        'telepon_pelanggan',
        'email_pelanggan',
        'tanggal_penawaran',
        'catatan',
        'total_harga',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * Get the user that created the penawaran.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that approved the penawaran.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the details for the penawaran.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailPenawaran::class);
    }
    
    /**
     * Get the barang keluar generated from this penawaran.
     */
    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Generate a unique code for the penawaran.
     */
    public static function generateKode(): string
    {
        $prefix = 'PNW-';
        $date = now()->format('Ymd');
        
        // Get the last code
        $lastPenawaran = self::where('kode_penawaran', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_penawaran', 'desc')
            ->first();
        
        if (!$lastPenawaran) {
            return $prefix . $date . '-001';
        }
        
        // Extract the numeric part
        $lastNumber = intval(substr($lastPenawaran->kode_penawaran, -3));
        
        // Increment and pad
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        
        return $prefix . $date . '-' . $newNumber;
    }

    /**
     * Check if penawaran can be edited
     */
    public function canEdit(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Scope a query to only include pending penawarans.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved penawarans.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected penawarans.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
