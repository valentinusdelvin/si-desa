<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; // tambah ini

class User extends Authenticatable implements JWTSubject // tambah implements
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    // Wajib ada 2 method ini untuk JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function citizen()
    {
        return $this->hasOne(Citizen::class);
    }
}