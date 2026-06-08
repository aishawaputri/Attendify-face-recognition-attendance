<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit
    protected $table = 'pengajuan_surat';

    // 2. Tentukan primary key sesuai dengan migration Anda
    protected $primaryKey = 'id_pengajuan';

    // 3. Daftarkan kolom yang boleh diisi
    protected $fillable = [
        'id_user',
        'id_jadwal',
        'tanggal_absen',
        'jenis_izin',
        'surat_dokumen',
        'keterangan',
        'status_acc',
        'catatan_dosen'
    ];

    /**
     * Relasi ke tabel Users (sebagai Mahasiswa)
     * Parameter: (ModelTarget, ForeignKey_DiTabelIni, OwnerKey_DiTabelUser)
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke tabel Jadwal Kuliah
     */
    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal', 'id_jadwal');
    }
}