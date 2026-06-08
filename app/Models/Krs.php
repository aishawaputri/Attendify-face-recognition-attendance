<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';
    protected $primaryKey = 'id_krs';
    protected $guarded = []; // Mengizinkan semua kolom diisi (mass assignment)

    // Relasi: 1 baris KRS milik 1 Mahasiswa (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: 1 baris KRS mengarah ke 1 Jadwal Kuliah
    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal', 'id_jadwal');
    }
}