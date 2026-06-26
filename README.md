# 📚 Attendify - Sistem Absensi Berbasis Face Recognition


Attendify merupakan sistem absensi berbasis web yang dirancang untuk mendukung proses absensi perkuliahan secara otomatis menggunakan teknologi Face Recognition. Sistem ini mengintegrasikan kamera untuk melakukan verifikasi identitas mahasiswa dan hanya mengizinkan proses absensi pada jadwal mata kuliah yang sedang berlangsung.

Selain mendukung proses absensi, Attendify juga menyediakan fitur pengelolaan data akademik, jadwal perkuliahan, pengambilan KRS, rekap kehadiran, serta pengajuan izin tidak hadir secara terintegrasi.

## Fitur Utama

| Fitur | Deskripsi |
|--------|-----------|
| Autentikasi Multi Pengguna | Mendukung peran Admin, Dosen, dan Mahasiswa dengan hak akses yang berbeda. |
| Manajemen Akademik | Mengelola data mahasiswa, dosen, mata kuliah, KRS, dan jadwal perkuliahan. |
| Absensi Face Recognition | Verifikasi kehadiran mahasiswa menggunakan Face Recognition dengan algoritma Euclidean Distance. |
| Validasi Jadwal | Memastikan absensi hanya dapat dilakukan pada mata kuliah yang sedang berlangsung sesuai KRS dan jadwal. |
| Rekap Kehadiran | Menampilkan riwayat dan rekap kehadiran mahasiswa. |
| Pengajuan Izin | Mahasiswa dapat mengajukan izin, kemudian dosen dapat menyetujui atau menolaknya. |

## Teknologi yang Digunakan

| Kategori             | Teknologi                            |
| -------------------- | ------------------------------------ |
| Backend              | Laravel, PHP                         |
| Frontend             | Blade, HTML, CSS, JavaScript         |
| Face Recognition     | face-api.js                          |
| API Browser          | WebRTC (MediaDevices API), Fetch API |
| Algoritma Pencocokan | Euclidean Distance                   |
| Basis Data           | MySQL                                |


<h2>Tampilan Sistem</h2>

<h3>Registrasi Wajah</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/ed2a6d43-61d5-42b8-8dac-e3bdce3f21ca" width="700">
</p>

<h3>Jadwal Aktif</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/19cd9412-b619-46bc-95e6-595df74c63ac" width="700">
</p>

<h3>Verifikasi Wajah Presensi</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/a83faa32-97fb-46ad-8327-efe487045fbf" width="700">
</p>

<h3>Pengajuan Surat Izin</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/82b112e0-79a5-4b8a-8708-396ec6466888" width="700">
</p>

<h3>Rekap Absensi</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/e8fce1dc-b034-40e3-a0d7-e280d7e41c61" width="700">
</p>

<h3>Dashboard Dosen</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/d3daceca-ead0-49e6-9544-e6fc7020efb4" width="700">
</p>

<h3>Validasi Perizinan Mahasiswa</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/b394dc28-1473-49c9-a900-19377f43d7c8" width="700">
</p>

<h3>Dashboard Admin</h3>
<p align="center">
  <img src="https://github.com/user-attachments/assets/f07fc540-cbec-45c4-a8a3-84403a123ca5" width="700">
</p>







