<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\Penawaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPenawaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'penawaran_id',
        'produk_id',
        'jumlah',
        'harga',
        'subtotal',
    ];

    /**
     * Get the penawaran that owns the detail.
     */
    public function penawaran(): BelongsTo
    {
        return $this->belongsTo(Penawaran::class);
    }

    /**
     * Get the produk for this detail.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Calculate the subtotal for this detail.
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->jumlah * $this->harga;
        return $this->subtotal;
    }
}
