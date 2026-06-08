<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id('id_pengajuan'); // Primary Key
            
            // Foreign Key ke tabel users (Mahasiswa)
            // Sesuaikan 'id_user' dan nama tabel 'users' jika di proyekmu berbeda
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            // Foreign Key ke tabel jadwal_kuliahs
            $table->unsignedBigInteger('id_jadwal');
            $table->foreign('id_jadwal')
                  ->references('id_jadwal')
                  ->on('jadwal_kuliahs')
                  ->onDelete('cascade');

            // Data esensial pengajuan
            $table->date('tanggal_absen'); // Tanggal mhs tidak masuk kelas
            $table->enum('jenis_izin', ['Izin', 'Sakit']);
            $table->string('surat_dokumen'); // Untuk menyimpan nama/path file (PDF/JPG)
            $table->text('keterangan')->nullable(); // Alasan tambahan mahasiswa

            // Status Approval Dosen
            $table->enum('status_acc', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->text('catatan_dosen')->nullable(); // Alasan jika dosen menolak surat
            
            $table->timestamps(); // Menghasilkan created_at (waktu apply) dan updated_at (waktu di-acc)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};