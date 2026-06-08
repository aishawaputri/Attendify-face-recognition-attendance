<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    // Nama tabel sesuai database
    protected $table = 'mata_kuliahs';

    // Primary Key kustom sesuai keinginan Anda
    protected $primaryKey = 'id_matkul';

    // Kolom yang boleh diisi
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester'
    ];

    /**
     * Relasi: Satu Mata Kuliah bisa memiliki banyak Jadwal.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'id_matkul', 'id_matkul');
    }
}