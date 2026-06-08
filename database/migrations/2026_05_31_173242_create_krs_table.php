<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            // Sesuai dengan kebiasaan struktur databasemu (menggunakan id_...)
            $table->id('id_krs');
            
            // Kolom Relasi (Tipe datanya harus unsignedBigInteger agar cocok dengan id_user dan id_jadwal)
            $table->unsignedBigInteger('id_user');   // Untuk Mahasiswa
            $table->unsignedBigInteger('id_jadwal'); // Untuk Jadwal Kuliah
            
            $table->timestamps();

            // Aturan Foreign Key (Jika jadwal/user dihapus, data krs ikut terhapus)
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_kuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};