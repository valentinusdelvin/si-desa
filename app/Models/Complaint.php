<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'citizen_id',
        'judul',
        'isi_aduan',
        'kategori',
        'status',
        'catatan_admin',
    ];

    // Relasi ke warga
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}