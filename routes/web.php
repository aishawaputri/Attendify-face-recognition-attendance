<?php

use App\Http\Controllers\FaceTokenController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// PINDAHKAN ini ke paling luar, jangan masukkan ke middleware guest
Route::get('/', function () {
    return auth()->check() ? redirect('/admin/dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    // Gunakan satu nama route saja agar tidak membingungkan Laravel
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']); 
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'viewAdmin'])->name('admin.dashboard');
            

    Route::name('admin.')->group(function() {
        Route::get('/matakuliah', [MataKuliahController::class, 'index'])->name('matakuliah');
        Route::post('/matakuliah', [MataKuliahController::class, 'store'])->name('matakuliah.store');
        Route::put('/matakuliah/{id_matkul}', [MataKuliahController::class, 'update'])->name('matakuliah.update');
        Route::delete('/matakuliah/{id_matkul}', [MataKuliahController::class, 'destroy'])->name('matakuliah.destroy');
        
        Route::get('/sesikelas', [JadwalKuliahController::class, 'index'])->name('jadwalkuliah');
        Route::post('/sesikelas', [JadwalKuliahController::class, 'store'])->name('jadwalkuliah.store');
        Route::put('/sesikelas/{id_jadwal}', [JadwalKuliahController::class, 'update'])->name('jadwalkuliah.update');
        Route::delete('/sesikelas/{id_jadwal}', [JadwalKuliahController::class, 'destroy'])->name('jadwalkuliah.destroy');
        
        Route::get('admin/krs', [KrsController   ::class, 'index'])->name('krs');
        Route::post('/krs', [KrsController::class, 'store'])->name('krs.store');
        Route::put('/krs/{id_krs}', [KrsController::class, 'update'])->name('krs.update');
        Route::delete('/krs/{id_krs}', [KrsController::class, 'destroy'])->name('krs.destroy');
    });

    Route::name('admin.')->group(function() {
        Route::get('/mahasiswa', [UserController::class, 'mahasiswa'])->name('users.mahasiswa');
        Route::get('/dosen', [UserController::class, 'dosen'])->name('users.dosen');
        Route::get('/pengaturan', [UserController::class, 'admin'])->name('users.admin');

        Route::post('/users/store/{role}', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id_user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id_user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/users/{id_user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::middleware('auth')->group(function () {
    // Jika Anda belum punya ProfileController, arahkan sementara ke '#' atau buat rutenya
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:mahasiswa'])->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'viewMahasiswa'])->name('dashboard');
    Route::get('/krs', [KrsController::class, 'viewMahasiswa'])->name('krs');
    
    Route::get('/presensi', [PresensiController::class, 'viewMahasiswa'])->name('presensi');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi');

    Route::get('/face-token', [FaceTokenController::class, 'index'])->name('face-token.index');
    Route::post('/face-token/store', [FaceTokenController::class, 'store'])->name('face-token.store');  
      
    Route::get('/rekap', [PresensiController::class, 'rekap'])->name('rekap');
});


Route::middleware(['auth'])->group(function () {
    // Rute tunggal untuk view dashboard (Mahasiswa & Dosen)
    Route::get('/dosen/dashboard', [DashboardController::class, 'viewDosen'])->name('dashboard');
    Route::get('/dosen/jadwal', [JadwalKuliahController::class, 'viewDosen'])->name('dosen.jadwal');

    // Rute untuk manajemen surat oleh dosen
    Route::get('/dosen/perizinan', [PengajuanSuratController::class, 'indexDosen'])->name('dosen.pengajuan');
    Route::post('/dosen/pengajuan/{id_pengajuan}', [PengajuanSuratController::class, 'updateStatus'])->name('dosen.pengajuan.update');
});