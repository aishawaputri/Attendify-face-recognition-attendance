<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'nomerIdentitas',
        'prodi',
        'angkatan',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi: Dosen bisa memiliki banyak Jadwal.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: Mahasiswa bisa memiliki banyak data Absensi.
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_user', 'id_user');
    }

    // Relasi User ke KRS (Mahasiswa punya banyak jadwal/KRS)
    public function krs()
    {
        return $this->hasMany(Krs::class, 'id_user', 'id_user');
    }
    /**
     * Relasi: User memiliki satu data Token Wajah.
     */
    public function faceToken(): HasOne
    {
        return $this->hasOne(FaceToken::class, 'id_user', 'id_user');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'id_user', 'id_user');
    }
}
