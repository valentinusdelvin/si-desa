<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi ke data warga (opsional, satu user bisa terhubung ke satu data warga)
    public function citizen()
    {
        return $this->hasOne(Citizen::class);
    }

    // Helper: cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}