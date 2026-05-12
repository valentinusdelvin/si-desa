<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    protected $fillable = [
        'citizen_id',
        'keterangan',
        'nominal',
        'status',
        'tanggal_bayar',
        'bulan',
    ];

    // Relasi ke warga
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}