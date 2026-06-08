<?php

namespace App\Models;

use App\Models\JadwalKuliah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensis';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_user',
        'id_jadwal',
        'status',
        'foto_bukti'
    ];

    /**
     * Relasi: Absensi ini milik seorang User (Mahasiswa/Dosen).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: Absensi ini tercatat pada Jadwal tertentu.
     */
    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal');
    }
}
