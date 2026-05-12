<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained('citizens')->onDelete('cascade');
            $table->string('keterangan');
            $table->decimal('nominal', 10, 2);
            $table->enum('status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->date('tanggal_bayar')->nullable();
            $table->string('bulan'); // contoh: "Januari 2025"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dues');
    }
};