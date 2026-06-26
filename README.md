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


