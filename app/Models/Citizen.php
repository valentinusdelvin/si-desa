<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'status_perkawinan',
        'user_id',
    ];

    // Relasi ke user (akun login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke iuran
    public function dues()
    {
        return $this->hasMany(Due::class);
    }

    // Relasi ke aduan
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Relasi ke persuratan
    public function mails()
    {
        return $this->hasMany(Mail::class);
    }
}