<?php

namespace App\Models;

use App\Models\Absensi;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliahs';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_matkul',
        'id_user',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'tahun_akademik',
        'tipe_semester',
        'is_buka'
    ];

    /**
     * PERBAIKAN UTAMA: Tambahkan relasi ke KRS agar query 'whereHas' di Controller bekerja
     */
    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class, 'id_jadwal', 'id_jadwal');
    }
    /**
     * Relasi: Jadwal ini dimiliki oleh satu Mata Kuliah.
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    /**
     * Relasi: Satu jadwal memiliki banyak catatan Absensi.
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pengajuanSurat()
{
    return $this->hasMany(PengajuanSurat::class, 'id_jadwal', 'id_jadwal');
}
}
