<?php

namespace Database\Seeders;

use App\Models\JadwalKuliah;
use App\Models\Krs;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. SEEDER USER (ADMIN, DOSEN, MAHASISWA)
        // ==========================================
        
        // Admin
        User::create([
            'nama' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nomerIdentitas' => 'ADM001',
        ]);

        // Dosen
        $dosen1 = User::create([
            'nama' => 'Dr. Budi Santoso',
            'email' => 'dosen1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nomerIdentitas' => 'DOS001',
        ]);

        $dosen2 = User::create([
            'nama' => 'Siti Aminah, M.Kom',
            'email' => 'dosen2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nomerIdentitas' => 'DOS002',
        ]);

        // Mahasiswa
        $mhs1 = User::create([
            'nama' => 'Rizky Pratama',
            'email' => 'mhs1@mhs.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nomerIdentitas' => '2024001',
            'prodi' => 'Teknik Informatika',
        ]);

        $mhs2 = User::create([
            'nama' => 'Anisa Rahma',
            'email' => 'mhs2@mhs.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nomerIdentitas' => '2024002',
            'prodi' => 'Sistem Informasi',
        ]);

        // ==========================================
        // 2. SEEDER MATA KULIAH
        // ==========================================
        $mk1 = MataKuliah::create(['kode_mk' => 'INF101', 'nama_mk' => 'Dasar Pemrograman', 'sks' => 3, 'semester' => 1]);
        $mk2 = MataKuliah::create(['kode_mk' => 'INF202', 'nama_mk' => 'Basis Data Lanjut', 'sks' => 3, 'semester' => 2]);
        $mk3 = MataKuliah::create(['kode_mk' => 'INF303', 'nama_mk' => 'Pemrograman Laravel', 'sks' => 4, 'semester' => 4]);
        $mk4 = MataKuliah::create(['kode_mk' => 'GEN101', 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1]);

        // ==========================================
        // 3. SEEDER JADWAL KULIAH (Assign Dosen ke Matkul)
        // ==========================================
        $j1 = JadwalKuliah::create([
            'id_matkul' => $mk1->id_matkul,
            'id_user'   => $dosen1->id_user,
            'hari'      => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '11:00:00',
            'ruangan'   => 'Lab Komputer 01',
            'tahun_akademik' => '2023/2024', // Kolom baru
            'tipe_semester'  => 'Genap',     // Kolom baru
            'is_buka'        => true,        // Kolom baru (Absensi dibuka/tidak)
        ]);

        $j2 = JadwalKuliah::create([
            'id_matkul' => $mk2->id_matkul, // Basis Data
            'id_user'   => $dosen2->id_user,
            'hari'      => 'Selasa',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:30:00',
            'ruangan'   => 'Ruang Teori A',
            'tahun_akademik' => '2023/2024', // Kolom baru
            'tipe_semester'  => 'Genap',     // Kolom baru
            'is_buka'        => true,        // Kolom baru (Absensi dibuka/tidak)
        ]);

        $j3 = JadwalKuliah::create([
            'id_matkul' => $mk1->id_matkul, // Dasar Pemrog
            'id_user'   => $dosen1->id_user,
            'hari'      => 'Rabu',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'ruangan'   => 'Lab 02',
            'tahun_akademik' => '2023/2024', // Kolom baru
            'tipe_semester'  => 'Genap',     // Kolom baru
            'is_buka'        => true,        // Kolom baru (Absensi dibuka/tidak)
        ]);

        // ==========================================
        // 4. SEEDER KRS (Mapping Mahasiswa ke Jadwal)
        // ==========================================
        
        // Rizky mengambil Laravel dan Basis Data
        Krs::create(['id_user' => $mhs1->id_user, 'id_jadwal' => $j1->id_jadwal]);
        Krs::create(['id_user' => $mhs1->id_user, 'id_jadwal' => $j2->id_jadwal]);

        // Anisa mengambil Basis Data dan Dasar Pemrograman
        Krs::create(['id_user' => $mhs2->id_user, 'id_jadwal' => $j2->id_jadwal]);
        Krs::create(['id_user' => $mhs2->id_user, 'id_jadwal' => $j3->id_jadwal]);
    }
}
