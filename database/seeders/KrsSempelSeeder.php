<?php

namespace Database\Seeders;

use App\Models\JadwalKuliah;
use App\Models\Krs;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KrsSempelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1. User Mahasiswa
        $mhs = User::updateOrCreate(
            ['nomerIdentitas' => '20240001'],
            ['nama' => 'Aishawa Putri Daviorda', 'email' => 'mhs3@mhs.com', 'password' => Hash::make('password'), 'role' => 'mahasiswa']
        );

        // 2. Daftar Dosen
        $dosen1 = User::updateOrCreate(['nomerIdentitas' => 'D001'], ['nama' => 'Dr. Aris Munandar', 'role' => 'dosen', 'email' => 'aris@example.com', 'password' => Hash::make('password')]);
        $dosen2 = User::updateOrCreate(['nomerIdentitas' => 'D002'], ['nama' => 'Putri Rahayu, M.T.', 'role' => 'dosen', 'email' => 'putri@example.com', 'password' => Hash::make('password')]);
        $dosen3 = User::updateOrCreate(['nomerIdentitas' => 'D003'], ['nama' => 'Budi Setiadi, Ph.D', 'role' => 'dosen', 'email' => 'budi_s@example.com', 'password' => Hash::make('password')]);

        // 3. Daftar Mata Kuliah
        $mkData = [
            ['kode' => 'IF101', 'nama' => 'Dasar Pemrograman', 'sks' => 3],
            ['kode' => 'IF102', 'nama' => 'Matematika Diskrit', 'sks' => 3],
            ['kode' => 'IF201', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 4],
            ['kode' => 'IF202', 'nama' => 'Struktur Data', 'sks' => 3],
            ['kode' => 'IF301', 'nama' => 'Kecerdasan Buatan', 'sks' => 3],
            ['kode' => 'IF302', 'nama' => 'Etika Profesi', 'sks' => 2],
            ['kode' => 'IF401', 'nama' => 'Cloud Computing', 'sks' => 3],
            ['kode' => 'IF402', 'nama' => 'Keamanan Siber', 'sks' => 3],
            ['kode' => 'IF501', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3], 
            ['kode' => 'IF502', 'nama' => 'Jaringan Komputer', 'sks' => 3],        
            ['kode' => 'IF601', 'nama' => 'Metode Penelitian', 'sks' => 2],        
            ['kode' => 'IF602', 'nama' => 'Pengolahan Citra Digital', 'sks' => 3], 
        ];

        foreach($mkData as $data) {
            MataKuliah::updateOrCreate(
                ['kode_mk' => $data['kode']], 
                ['nama_mk' => $data['nama'], 'sks' => $data['sks'], 'semester' => rand(1, 4)]
            );
        }

        $allMk = MataKuliah::all();

        // 4. DATA PERIODE AKTIF (Genap 2025/2026) - 3 s/d 4 Matkul Aktif Setiap Hari
        $jadwalAktif = [
            // ================== HARI SENIN (4 Mata Kuliah) ==================
            ['hari' => 'Senin', 'mulai' => '07:00', 'selesai' => '10:00', 'ruang' => 'Lab 01', 'mk' => $allMk[0], 'dosen' => $dosen1],
            ['hari' => 'Senin', 'mulai' => '10:15', 'selesai' => '12:15', 'ruang' => 'R. 302', 'mk' => $allMk[1], 'dosen' => $dosen2],
            ['hari' => 'Senin', 'mulai' => '13:00', 'selesai' => '15:30', 'ruang' => 'R. 105', 'mk' => $allMk[2], 'dosen' => $dosen3],
            ['hari' => 'Senin', 'mulai' => '15:45', 'selesai' => '18:15', 'ruang' => 'Lab 03', 'mk' => $allMk[3], 'dosen' => $dosen1],

            // ================== HARI SELASA (4 Mata Kuliah) ==================
            ['hari' => 'Selasa', 'mulai' => '07:30', 'selesai' => '10:00', 'ruang' => 'Lab 02', 'mk' => $allMk[4], 'dosen' => $dosen2],
            ['hari' => 'Selasa', 'mulai' => '10:15', 'selesai' => '12:15', 'ruang' => 'R. 202', 'mk' => $allMk[5], 'dosen' => $dosen3],
            ['hari' => 'Selasa', 'mulai' => '13:00', 'selesai' => '15:30', 'ruang' => 'R. 401', 'mk' => $allMk[6], 'dosen' => $dosen1],
            ['hari' => 'Selasa', 'mulai' => '15:45', 'selesai' => '18:15', 'ruang' => 'R. 402', 'mk' => $allMk[7], 'dosen' => $dosen2],

            // ================== HARI RABU (3 Mata Kuliah) ==================
            ['hari' => 'Rabu', 'mulai' => '08:00', 'selesai' => '10:30', 'ruang' => 'Lab 01', 'mk' => $allMk[8], 'dosen' => $dosen1],
            ['hari' => 'Rabu', 'mulai' => '10:45', 'selesai' => '13:15', 'ruang' => 'R. 301', 'mk' => $allMk[9], 'dosen' => $dosen3],
            ['hari' => 'Rabu', 'mulai' => '14:00', 'selesai' => '16:00', 'ruang' => 'Daring/Zoom', 'mk' => $allMk[10], 'dosen' => $dosen2],

            // ================== HARI KAMIS (3 Mata Kuliah) ==================
            ['hari' => 'Kamis', 'mulai' => '08:00', 'selesai' => '10:30', 'ruang' => 'Lab 02', 'mk' => $allMk[11], 'dosen' => $dosen1],
            ['hari' => 'Kamis', 'mulai' => '10:45', 'selesai' => '13:15', 'ruang' => 'R. 105', 'mk' => $allMk[0], 'dosen' => $dosen3],
            ['hari' => 'Kamis', 'mulai' => '14:00', 'selesai' => '16:30', 'ruang' => 'R. 302', 'mk' => $allMk[2], 'dosen' => $dosen2],

            // ================== HARI JUMAT (3 Mata Kuliah) ==================
            ['hari' => 'Jumat', 'mulai' => '08:00', 'selesai' => '10:30', 'ruang' => 'Lab 03', 'mk' => $allMk[3], 'dosen' => $dosen1],
            ['hari' => 'Jumat', 'mulai' => '13:30', 'selesai' => '16:00', 'ruang' => 'R. 202', 'mk' => $allMk[4], 'dosen' => $dosen2],
            ['hari' => 'Jumat', 'mulai' => '16:15', 'selesai' => '18:15', 'ruang' => 'Daring/Zoom', 'mk' => $allMk[7], 'dosen' => $dosen3],
        ];

        foreach ($jadwalAktif as $j) {
            $jd = JadwalKuliah::create([
                'id_matkul' => $j['mk']->id_matkul, 
                'id_user' => $j['dosen']->id_user,
                'hari' => $j['hari'], 
                'jam_mulai' => $j['mulai'], 
                'jam_selesai' => $j['selesai'],
                'ruangan' => $j['ruang'], 
                'tahun_akademik' => '2025/2026', 
                'tipe_semester' => 'Genap',       
                'is_buka' => 1
            ]);
            Krs::create(['id_user' => $mhs->id_user, 'id_jadwal' => $jd->id_jadwal]);
        }

        // 5. DATA PERIODE LALU (Ganjil 2025/2026)
        for ($i=0; $i<7; $i++) {
            $jdLalu = JadwalKuliah::create([
                'id_matkul' => $allMk[$i]->id_matkul, 
                'id_user' => $dosen1->id_user,
                'hari' => 'Senin', 
                'jam_mulai' => '08:00', 
                'jam_selesai' => '10:00',
                'ruangan' => 'Gedung Lama', 
                'tahun_akademik' => '2025/2026', 
                'tipe_semester' => 'Ganjil',      
                'is_buka' => 0
            ]);
            Krs::create(['id_user' => $mhs->id_user, 'id_jadwal' => $jdLalu->id_jadwal]);
        }

        // 6. DATA PERIODE DEPAN (Ganjil 2026/2027) - Simulasi Masa Depan
        $jdDepan = JadwalKuliah::create([
            'id_matkul' => $allMk[4]->id_matkul, 
            'id_user' => $dosen2->id_user,
            'hari' => 'Jumat', 
            'jam_mulai' => '14:00', 
            'jam_selesai' => '16:00',
            'ruangan' => 'R. Internasional', 
            'tahun_akademik' => '2026/2027', 
            'tipe_semester' => 'Ganjil',      
            'is_buka' => 1
        ]);
        Krs::create(['id_user' => $mhs->id_user, 'id_jadwal' => $jdDepan->id_jadwal]);

        $this->command->info('Database Terisi! Setiap hari kerja sekarang memiliki 3-4 kelas aktif untuk simulasi.');
    }
}