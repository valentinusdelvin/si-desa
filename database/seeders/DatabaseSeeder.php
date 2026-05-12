<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Citizen;
use App\Models\Due;
use App\Models\Complaint;
use App\Models\Mail;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun admin
        $admin = User::create([
            'name' => 'Admin RT',
            'email' => 'admin@desa.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Buat akun warga
        $userWarga1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'warga',
        ]);

        $userWarga2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'warga',
        ]);

        // Buat data warga dan hubungkan ke user
        $warga1 = Citizen::create([
            'nik' => '3578011234560001',
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Mawar No. 10, RT 01',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-05-15',
            'no_hp' => '081234567890',
            'status_perkawinan' => 'Kawin',
            'user_id' => $userWarga1->id,
        ]);

        $warga2 = Citizen::create([
            'nik' => '3578011234560002',
            'nama' => 'Siti Aminah',
            'alamat' => 'Jl. Melati No. 5, RT 01',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '1992-08-20',
            'no_hp' => '081298765432',
            'status_perkawinan' => 'Belum Kawin',
            'user_id' => $userWarga2->id,
        ]);

        // Buat contoh data iuran
        Due::create([
            'citizen_id' => $warga1->id,
            'keterangan' => 'Iuran Kebersihan',
            'nominal' => 25000,
            'status' => 'lunas',
            'tanggal_bayar' => '2025-01-05',
            'bulan' => 'Januari 2025',
        ]);

        Due::create([
            'citizen_id' => $warga2->id,
            'keterangan' => 'Iuran Kebersihan',
            'nominal' => 25000,
            'status' => 'belum_lunas',
            'tanggal_bayar' => null,
            'bulan' => 'Januari 2025',
        ]);

        // Buat contoh aduan
        Complaint::create([
            'citizen_id' => $warga1->id,
            'judul' => 'Lampu Jalan Mati',
            'isi_aduan' => 'Lampu jalan di depan RT 01 sudah mati selama 3 hari, mohon segera diperbaiki.',
            'kategori' => 'infrastruktur',
            'status' => 'menunggu',
        ]);

        Complaint::create([
            'citizen_id' => $warga2->id,
            'judul' => 'Sampah Menumpuk',
            'isi_aduan' => 'Ada tumpukan sampah di dekat got yang belum diambil petugas.',
            'kategori' => 'kebersihan',
            'status' => 'diproses',
            'catatan_admin' => 'Sudah dilaporkan ke dinas kebersihan, akan segera ditangani.',
        ]);

        // Buat contoh permohonan surat
        Mail::create([
            'citizen_id' => $warga1->id,
            'jenis_surat' => 'Surat Pengantar KTP',
            'keperluan' => 'Pembuatan KTP baru karena KTP lama rusak',
            'status' => 'selesai',
            'nomor_surat' => 'RT01/SK/I/2025/001',
        ]);

        Mail::create([
            'citizen_id' => $warga2->id,
            'jenis_surat' => 'Surat Keterangan Domisili',
            'keperluan' => 'Keperluan melamar pekerjaan',
            'status' => 'menunggu',
        ]);
    }
}