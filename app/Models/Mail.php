<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = [
        'citizen_id',
        'jenis_surat',
        'keperluan',
        'status',
        'nomor_surat',
        'catatan',
    ];

    // Relasi ke warga
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}