@extends('layouts.app')

@section('content')
<style>
    /* --- CSS ALERT WAJAH --- */
    .reg-alert-card {
        background: #ffffff; border-radius: 20px; padding: 25px; display: flex;
        align-items: center; justify-content: space-between; margin-bottom: 30px;
        border: 1px solid #ffe4e6; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .btn-register-face {
        background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); color: white;
        padding: 12px 24px; border-radius: 12px; font-weight: 800; text-decoration: none;
        transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.3);
        font-size: 14px; border: none;
    }
    .btn-register-face:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(225, 29, 72, 0.4); color: white; }

    /* --- CSS BARU: CARD LAYOUT --- */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        margin-top: 20px;
    }
    .schedule-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }
    /* Aksen garis warna di atas kartu */
    .schedule-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 5px;
        background: linear-gradient(90deg, #6366f1, #a855f7);
    }
    .card-header-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .time-badge {
        background: #f8fafc;
        color: #475569;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid #e2e8f0;
    }
    .room-badge {
        background: #eef2ff;
        color: #4f46e5;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .subject-info {
        margin-bottom: 25px;
        flex-grow: 1;
    }
    .subject-info h3 {
        margin: 0 0 8px 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.4;
    }
    .subject-info p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }
    
    /* --- CSS TOMBOL ABSEN --- */
    .btn-absen {
        padding: 12px 20px; border-radius: 12px; font-weight: 800;
        text-decoration: none; display: block; width: 100%; text-align: center;
        font-size: 14px; transition: 0.3s; border: none; cursor: pointer;
        box-sizing: border-box;
    }
    .btn-ready { background: #4f46e5; color: white; box-shadow: 0 4px 10px rgba(79, 70, 233, 0.25); }
    .btn-ready:hover { background: #4338ca; transform: scale(0.98); }
    .btn-locked { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; border: 1px dashed #cbd5e1; }
    .btn-success-disabled { 
        background: #10b981; color: white; cursor: not-allowed; 
        box-shadow: none; opacity: 0.9;
    }
    .btn-status-disabled { 
        background: #cbd5e1; color: #475569; cursor: not-allowed; 
        box-shadow: none; border: 1px solid #cbd5e1;
    }
    /* Style Tambahan untuk status penolakan berkas permanen (Alfa) */
    .btn-danger-disabled {
        background: #fee2e2; color: #991b1b; cursor: not-allowed;
        box-shadow: none; border: 1px solid #fca5a5;
    }

    /* --- CSS MODAL OVERLAY GLOBAL --- */
    .custom-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.8); z-index: 9999;
        display: none; justify-content: center; align-items: center;
        backdrop-filter: blur(4px);
    }
    .custom-modal-content {
        background: white; width: 90%; max-width: 480px;
        border-radius: 24px; padding: 35px 30px 30px !important; text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
    }

    /* --- CSS PILIHAN JALUR MODAL --- */
    .choice-grid {
        display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 16px !important; margin: 25px 0 10px !important;
    }
    
    .choice-grid .choice-card:first-child:hover {
        border-color: #4f46e5 !important;
    }
    .choice-grid .choice-card:last-child:hover {
        border-color: #d97706 !important;
    }
    .choice-card {
        border: 2px solid #e2e8f0 !important; border-radius: 16px !important; padding: 25px 15px !important;
        cursor: pointer !important; transition: all 0.2s ease !important; display: flex !important;
        flex-direction: column !important; align-items: center !important; justify-content: center !important; 
        min-height: 170px !important; background: #f8fafc !important; box-sizing: border-box !important;
    }
    .choice-card:hover { background: #f0f6ff !important; transform: translateY(-2px) !important; }
    .choice-title { font-size: 14px; font-weight: 800; color: #0f172a; }

    /* --- CSS FORM INPUT MODAL --- */
    .form-group { text-align: left; margin-bottom: 15px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 6px; }
    .form-control {
        width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1;
        font-size: 14px; font-weight: 600; color: #0f172a; box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

    /* --- CSS MODAL KAMERA --- */
    .video-wrapper {
        position: relative; width: 100%; border-radius: 16px;
        overflow: hidden; background: #000; margin: 20px 0;
        border: 4px solid #f1f5f9;
    }
    #presensi-video { width: 100%; height: auto; display: block; transform: scaleX(-1); }
    .btn-verify {
        background: #10b981; color: white; width: 100%; padding: 14px;
        border-radius: 12px; font-weight: 800; font-size: 15px;
        border: none; cursor: pointer; margin-bottom: 10px; transition: 0.3s;
    }
    .btn-verify:hover { background: #059669; }
    .btn-verify:disabled { background: #94a3b8; cursor: not-allowed; }
    
    .btn-submit-izin {
        background: #d97706; color: white; width: 100%; padding: 14px;
        border-radius: 12px; font-weight: 800; font-size: 15px;
        border: none; cursor: pointer; margin-bottom: 10px; transition: 0.3s;
    }
    .btn-submit-izin:hover { background: #b45309; }
    
    .btn-close-modal {
        background: #f1f5f9; color: #64748b; width: 100%; padding: 12px;
        border-radius: 12px; font-weight: 700; font-size: 14px;
        border: none; cursor: pointer; transition: 0.3s;
    }
    .btn-close-modal:hover { background: #e2e8f0; color: #0f172a; }

    .swal2-container { z-index: 100000 !important; }
</style>

<div style="margin-bottom: 25px;">
    <h1 style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 0;">Presensi Kehadiran</h1>
    <p style="color: #64748b; margin-top: 5px; font-size: 15px;">
        <span style="color: #3b82f6; font-weight: 800;">{{ $today }}, {{ date('d F Y') }}</span>
    </p>
</div>

@if(!$isFaceRegistered)
<div class="reg-alert-card">
    <div style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #fff1f2; width: 90px; height: 90px; border-radius: 20px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <img src="{{ asset('icons/scan.png') }}" alt="Scan" style="width: 60px; height: 60px; object-fit: contain;">
        </div>
        <div>
            <h4 style="margin: 0; color: #9f1239; font-weight: 900; font-size: 18px;">Wajah Belum Terdaftar!</h4>
            <p style="margin: 5px 0 0; color: #be123c; font-size: 14px; opacity: 0.8;">Silakan lakukan pendaftaran wajah untuk mengaktifkan fitur absensi.</p>
        </div>
    </div>
    <a href="{{ route('mahasiswa.face-token.index') }}" class="btn-register-face">
        Daftarkan Wajah
    </a>
</div>
@endif

<div class="schedule-grid">
    @forelse($jadwalHariIni as $krs)
        @php
            $idJadwalAsli = $krs->jadwalKuliah->id_jadwal;

            $start = $krs->jadwalKuliah->jam_mulai;
            $end = $krs->jadwalKuliah->jam_selesai;
            $isOngoing = ($currentTime >= $start && $currentTime <= $end);
            
            $statusAbsenMhs = $sudahAbsenHariIni[$idJadwalAsli] ?? null; 
            $statusSuratMhs = $statusSuratHariIni[$idJadwalAsli] ?? null;
        @endphp
        
        <div class="schedule-card">
            <div class="card-header-info">
                <div class="time-badge">
                    🕒 {{ date('h:i A', strtotime($start)) }} - {{ date('h:i A', strtotime($end)) }}
                </div>
                <div class="room-badge">
                    <img src="{{ asset('icons/door.png') }}" alt="Room" style="width: 14px; height: 14px; object-fit: contain;">
                    {{ $krs->jadwalKuliah->ruangan }}
                </div>
            </div>
            
            <div class="subject-info">
                <h3>{{ $krs->jadwalKuliah->mataKuliah->nama_mk }}</h3>
                <p>{{ $krs->jadwalKuliah->user->nama ?? '—' }}</p>
            </div>
            
            <div class="card-action">
                @if(!$isFaceRegistered)
                    <button class="btn-absen btn-locked" disabled>Terkunci</button>
                    
                @elseif($statusAbsenMhs == 'Hadir')
                    <button class="btn-absen btn-success-disabled" disabled>Sudah Hadir</button>
                    
                @elseif($statusAbsenMhs == 'Sakit' || $statusAbsenMhs == 'Izin')
                    <button class="btn-absen btn-status-disabled" disabled>Status: {{ $statusAbsenMhs }}</button>
                
                @elseif($statusSuratMhs == 'Pending')
                    <button class="btn-absen" disabled style="background: #ff9800; color: white; cursor: not-allowed; border: none; box-shadow: none;">
                        Pending (Verifikasi Dosen)
                    </button>

                @elseif($statusSuratMhs == 'Disetujui')
                    <button class="btn-absen btn-status-disabled" disabled style="background: #06b6d4; color: white; border: none;">
                        Status: Izin / Sakit
                    </button>

                {{-- PERBAIKAN DI SINI: Jika surat ditolak, tampilkan badge statis Alfa secara permanen --}}
                @elseif($statusSuratMhs == 'Ditolak')
                    <button class="btn-absen btn-danger-disabled" disabled>
                        Izin Ditolak (Absensi: Alfa)
                    </button>

                @elseif($isOngoing)
                    <button id="btn-absen-{{ $idJadwalAsli }}" onclick="openChoiceModal({{ $idJadwalAsli }})" class="btn-absen btn-ready">
                        Mulai Absen
                    </button>
                    
                @else
                    <button class="btn-absen btn-locked" disabled title="Hanya bisa absen pada jam kuliah">
                        Belum Waktunya
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; background: white; padding: 60px 20px; border-radius: 20px; border: 2px dashed #e2e8f0;">
            <img src="{{ asset('assets/img/coffee-break.png') }}" alt="Empty" style="width: 100px; opacity: 0.4; margin-bottom: 20px;">
            <h3 style="color: #475569; font-weight: 800; font-size: 18px; margin: 0 0 5px;">Libur / Tidak Ada Kelas</h3>
            <p style="color: #94a3b8; font-size: 14px; margin: 0;">Tidak ada jadwal kuliah untuk hari ini ({{ $today }}).</p>
        </div>
    @endforelse
</div>

{{-- MODAL METODE ABSENSI --}}
<div id="choiceModal" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <button onclick="closeChoiceModal()" style="position: absolute !important; top: 16px !important; right: 20px !important; background: none !important; border: none !important; font-size: 22px !important; font-weight: 600 !important; color: #94a3b8 !important; cursor: pointer !important; transition: color 0.2s !important; padding: 5px !important; line-height: 1 !important;" onmouseover="this.style.color='#f43f5e'" onmouseout="this.style.color='#94a3b8'">
            &times;
        </button>
        <h3 style="margin: 0; font-weight: 900; color: #0f172a; font-size: 20px;">Metode Presensi</h3>
        <p style="color: #64748b; font-size: 13px; margin-top: 5px;">Pilih opsi pencatatan kehadiran kuliah Anda hari ini.</p>
        
        <div class="choice-grid">
            <div class="choice-card" onclick="triggerScanWajah()">
                <div style="flex: 1 !important; display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 10px !important; width: 100% !important;">
                    <img src="{{ asset('icons/camera.png') }}" alt="📸" style="height: 56px !important; width: auto !important; object-fit: contain !important; filter: drop-shadow(0 2px 4px rgba(79, 70, 229, 0.15)) !important;">
                </div>
                <div class="choice-title" style="font-weight: 800 !important; color: #0f172a !important; text-align: center !important; margin-top: auto !important;">Scan Wajah (Hadir)</div>
            </div>

            <div class="choice-card" onclick="triggerFormIzin()">
                <div style="flex: 1 !important; display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 10px !important; width: 100% !important;">
                    <img src="{{ asset('icons/surat.png') }}" alt="📄" style="height: 56px !important; width: auto !important; object-fit: contain !important; filter: drop-shadow(0 2px 4px rgba(217, 119, 6, 0.15)) !important;">
                </div>
                <div class="choice-title" style="font-weight: 800 !important; color: #0f172a !important; text-align: center !important; margin-top: auto !important;">Surat Izin / Sakit</div>
            </div>
        </div>
        <button onclick="closeChoiceModal()" class="btn-close-modal" style="margin-top: 10px;">Batal</button>
    </div>
</div>

{{-- MODAL SCAN WAJAH --}}
<div id="cameraModal" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <button onclick="closeCameraModal()" style="position: absolute !important; top: 16px !important; right: 20px !important; background: none !important; border: none !important; font-size: 22px !important; font-weight: 600 !important; color: #94a3b8 !important; cursor: pointer !important; transition: color 0.2s !important; padding: 5px !important; line-height: 1 !important;" onmouseover="this.style.color='#f43f5e'" onmouseout="this.style.color='#94a3b8'">
            &times;
        </button>

        <h3 style="margin: 0; font-weight: 900; color: #0f172a; font-size: 20px;">Verifikasi Wajah</h3>
        <p style="color: #64748b; font-size: 13px; margin-top: 5px;" id="modal-status">Menyiapkan kamera...</p>
        
        <div class="video-wrapper">
            <video id="presensi-video" autoplay muted></video>
        </div>

        <button id="btn-verify" class="btn-verify" disabled>Verifikasi Sekarang</button>
        <button onclick="closeCameraModal()" class="btn-close-modal">Kembali</button>
    </div>
</div>

{{-- MODAL UNGHAH DOKUMEN SURAT --}}
<div id="permitModal" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <button onclick="closePermitModal()" style="position: absolute !important; top: 16px !important; right: 20px !important; background: none !important; border: none !important; font-size: 22px !important; font-weight: 600 !important; color: #94a3b8 !important; cursor: pointer !important; transition: color 0.2s !important; padding: 5px !important; line-height: 1 !important;" onmouseover="this.style.color='#f43f5e'" onmouseout="this.style.color='#94a3b8'">
            &times;
        </button>
        <h3 style="margin: 0; font-weight: 900; color: #0f172a; font-size: 20px;">Pengajuan Surat Keterangan</h3>
        <p style="color: #64748b; font-size: 13px; margin-top: 5px;">Unggah bukti dokumen yang sah untuk validasi izin/sakit.</p>
        
        <form id="form-pengajuan-izin" enctype="multipart/form-data" style="margin-top: 20px;">
            <div class="form-group">
                <label>Jenis Keterangan</label>
                <select name="status" id="permit-status" class="form-control" required>
                    <option value="Izin">Izin (Keperluan Mendesak)</option>
                    <option value="Sakit">Sakit (Butuh Istirahat/Rawat)</option>
                </select>
            </div>
            <div class="form-group" style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Foto Bukti Surat (.jpg, .png, max 2MB)</label>
                
                <label for="permit-file" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px 16px; border: 2px dashed #cbd5e1; border-radius: 14px; background: #f8fafc; cursor: pointer; transition: all 0.2s ease; text-align: center;" onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#f0f6ff';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc';" id="upload-zone">
                    
                    <div style="height: 50px; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <img id="upload-icon-img" src="{{ asset('icons/upload.png') }}" alt="Upload" style="height: 42px; width: auto; object-fit: contain;">
                    </div>
                    
                    <span id="file-label-text" style="font-size: 13px; font-weight: 700; color: #475569;">Pilih atau Taruh File di Sini</span>
                    <span id="file-size-text" style="font-size: 11px; color: #94a3b8; margin-top: 2px; font-weight: 600;">Format gambar maksimal 2MB</span>
                    
                    <input type="file" name="surat_dokumen" id="permit-file" accept="image/*" required style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;" onchange="updateFileName(this)">
                </label>
            </div>

            <button type="submit" id="btn-submit-izin" class="btn-submit-izin">Kirim Pengajuan</button>
        </form>
        <button onclick="closePermitModal()" class="btn-close-modal">Kembali</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const video = document.getElementById('presensi-video');
        const choiceModal = document.getElementById('choiceModal');
        const cameraModal = document.getElementById('cameraModal');
        const permitModal = document.getElementById('permitModal');
        const btnVerify = document.getElementById('btn-verify');
        const modalStatus = document.getElementById('modal-status');
        
        let currentJadwalId = null;
        let streamReference = null;

        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models')
            ]);
            console.log("Model Face-API Berhasil Dimuat");
        } catch (err) {
            console.error("Gagal memuat model Face API:", err);
        }

        window.openChoiceModal = function(jadwalId) {
            currentJadwalId = jadwalId;
            choiceModal.style.display = 'flex';
        };
        window.closeChoiceModal = function() {
            choiceModal.style.display = 'none';
        };

        window.triggerScanWajah = function() {
            closeChoiceModal();
            cameraModal.style.display = 'flex';
            modalStatus.innerText = "Menghubungkan ke kamera...";
            btnVerify.disabled = true;
            btnVerify.innerText = "Tunggu Sebentar...";

            navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "user", width: { ideal: 640 }, height: { ideal: 480 } } 
            })
            .then(stream => {
                streamReference = stream;
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    modalStatus.innerText = "Kamera aktif. Posisikan wajah Anda lalu tekan Verifikasi.";
                    btnVerify.disabled = false;
                    btnVerify.innerText = "Verifikasi Sekarang";
                };
            })
            .catch(err => {
                modalStatus.innerText = "Akses kamera ditolak atau tidak ditemukan.";
                Swal.fire('Error', 'Tidak dapat mengakses kamera.', 'error');
            });
        };

        window.closeCameraModal = function() {
            cameraModal.style.display = 'none';
            if (streamReference) {
                streamReference.getTracks().forEach(track => track.stop());
                streamReference = null;
            }
            video.srcObject = null;
            choiceModal.style.display = 'flex';
        };

        window.triggerFormIzin = function() {
            closeChoiceModal();
            permitModal.style.display = 'flex';
        };

        window.closePermitModal = function() {
            permitModal.style.display = 'none';
            document.getElementById('form-pengajuan-izin').reset();
            choiceModal.style.display = 'flex';
        };

        // --- SUBMIT JALUR FORM SURAT ---
        document.getElementById('form-pengajuan-izin').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btnSubmit = document.getElementById('btn-submit-izin');
            const chosenStatus = document.getElementById('permit-status').value;
            
            btnSubmit.disabled = true;
            btnSubmit.innerText = "Mengirim...";

            const formData = new FormData(e.target);
            formData.append('id_jadwal', currentJadwalId);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                const response = await fetch('/presensi', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    permitModal.style.display = 'none';
                    choiceModal.style.display = 'none';
                    
                    Swal.fire('Berhasil!', data.message, 'success');

                    const targetBtn = document.getElementById('btn-absen-' + currentJadwalId);
                    if (targetBtn) {
                        const newBtn = document.createElement('button');
                        newBtn.className = 'btn-absen btn-status-disabled';
                        newBtn.innerText = '⏳ Pending (' + chosenStatus + ')';
                        newBtn.disabled = true;
                        targetBtn.parentNode.replaceChild(newBtn, targetBtn);
                    }
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = "Kirim Pengajuan";
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan jaringan atau sistem.', 'error');
                btnSubmit.disabled = false;
                btnSubmit.innerText = "Kirim Pengajuan";
            }
        });
        
        // --- SUBMIT JALUR SCAN WAJAH ---
        btnVerify.addEventListener('click', async () => {
            btnVerify.disabled = true;
            btnVerify.innerText = "Memproses Wajah...";
            modalStatus.innerText = "Sedang memindai wajah...";

            try {
                const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    Swal.fire({
                        toast: true, position: 'top', icon: 'warning',
                        title: 'Wajah Tidak Terdeteksi!',
                        text: 'Posisikan wajah Anda dengan jelas di depan kamera.',
                        showConfirmButton: false, timer: 3000, timerProgressBar: true
                    });
                    btnVerify.disabled = false;
                    btnVerify.innerText = "Verifikasi Sekarang";
                    modalStatus.innerText = "Posisikan wajah Anda lalu tekan Verifikasi.";
                    return;
                }

                const faceDescriptor = Array.from(detection.descriptor);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                const response = await fetch('/presensi', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id_jadwal: currentJadwalId, face_vector: faceDescriptor })
                });

                const data = await response.json();

                if (data.success) {
                    cameraModal.style.display = 'none';
                    choiceModal.style.display = 'none';
                    Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 2000, showConfirmButton: false });

                    const targetBtn = document.getElementById('btn-absen-' + currentJadwalId);
                    if (targetBtn) {
                        const newBtn = document.createElement('button');
                        newBtn.className = 'btn-absen btn-success-disabled';
                        newBtn.innerText = 'Sudah Hadir';
                        newBtn.disabled = true;
                        targetBtn.parentNode.replaceChild(newBtn, targetBtn);
                    }
                } else {
                    const savedJadwalId = currentJadwalId;
                    cameraModal.style.display = 'none';
                    Swal.fire({ title: 'Wajah Tidak Cocok!', text: data.message, icon: 'error', confirmButtonText: 'Coba Lagi', confirmButtonColor: '#e11d48', allowOutsideClick: false })
                    .then((result) => { if (result.isConfirmed) { window.openChoiceModal(savedJadwalId); triggerScanWajah(); } });
                }
            } catch (err) {
                console.error(err);
                cameraModal.style.display = 'none';
                Swal.fire({ title: 'Error Sistem', text: 'Terjadi kesalahan teknis.', icon: 'error', confirmButtonText: 'Coba Lagi', confirmButtonColor: '#64748b', allowOutsideClick: false })
                .then((result) => { if (result.isConfirmed) { window.openChoiceModal(currentJadwalId); triggerScanWajah(); } });
            }
        });
    });

    function updateFileName(input) {
        const labelText = document.getElementById('file-label-text');
        const sizeText = document.getElementById('file-size-text');
        const uploadZone = document.getElementById('upload-zone');
        const uploadIcon = document.getElementById('upload-icon-img');

        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            labelText.innerText = file.name;
            labelText.style.color = "#10b981"; 
            sizeText.innerText = "File berhasil dimuat (" + (file.size / (1024 * 1024)).toFixed(2) + " MB)";
            uploadZone.style.borderColor = "#10b981";
            uploadZone.style.background = "#f0fdf4";
            uploadIcon.src = "{{ asset('icons/surat.png') }}";
            uploadIcon.style.filter = "drop-shadow(0 2px 4px rgba(16, 185, 129, 0.2))";
        } else {
            labelText.innerText = "Pilih atau Taruh File di Sini";
            labelText.style.color = "#475569";
            sizeText.innerText = "Format gambar maksimal 2MB";
            uploadZone.style.borderColor = "#cbd5e1";
            uploadZone.style.background = "#f8fafc";
            uploadIcon.src = "{{ asset('icons/upload.png') }}";
            uploadIcon.style.filter = "none";
        }
    }
</script>
@endsection