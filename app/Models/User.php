<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Penawaran;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get the penawarans created by the user.
     */
    public function penawarans()
    {
        return $this->hasMany(Penawaran::class);
    }
    
    /**
     * Get the penawarans approved by the user.
     */
    public function approvedPenawarans()
    {
        return $this->hasMany(Penawaran::class, 'approved_by');
    }
    
    /**
     * Get all pengiriman assigned to this driver
     */
    public function pengirimanList()
    {
        return $this->hasMany(Pengiriman::class, 'driver_id');
    }
    
    /**
     * Get active pengiriman for this driver (not completed)
     */
    public function pengirimanAktif()
    {
        return $this->hasMany(Pengiriman::class, 'driver_id')
            ->where('status_pengiriman', '!=', 'selesai');
    }
}
