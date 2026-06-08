@include('components.sweetalert')
@extends('layouts.app')

@section('content')
    <style>
    /* --- 1. MEMATIKAN KOTAK PUTIH BACKDROP BAWAAN TEMPLATE --- */
    /* Menargetkan semua kemungkinan class pembungkus layout agar menjadi transparan sempurna */
    .content-wrapper, 
    .content, 
    .container-fluid,
    .main-content,
    .content-header,
    section,
    main,
    #app {
        background: transparent !important;
        background-color: transparent !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* --- 2. LAYOUT UTAMA CONTAINER --- */
    .main-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px 15px 20px 15px; /* Ditambah padding atas agar memberi ruang kelengkungan */
        min-height: calc(100vh - 60px);
        font-family: 'Nunito', 'Segoe UI', sans-serif;
        box-sizing: border-box;
        width: 100%;
    }

    /* --- 3. BOX REGISTRASI UTAMA (SATU-SATUNYA KARTU NYATA) --- */
    .registration-box {
        background: #ffffff !important;
        padding: 40px 35px 35px 35px; 
        border-radius: 24px !important; /* Membuat lekukan sudut halus dan utuh */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important; /* Bayangan halus tunggal */
        width: 100%;
        max-width: 780px; 
        text-align: center;
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
        position: relative;
        z-index: 99; /* Memaksa kartu ini berdiri di lapisan paling depan */
    }

    .header-section h2 {
        font-size: 26px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
        margin-top: 0;
    }

    /* Kotak Kuning Instruksi */
    .instruction-box {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        padding: 10px 24px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: inline-block;
    }

    .instruction-box p {
        color: #b45309;
        font-size: 13px;
        font-weight: 700;
        margin: 0;
    }

    /* --- 4. STREAM KAMERA INTERN --- */
    .video-wrapper {
        position: relative;
        width: 100%;
        max-width: 580px; 
        aspect-ratio: 16 / 9; 
        background: #0f172a;
        border-radius: 18px;
        overflow: hidden;
        margin: 0 auto 25px auto;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.2);
    }

    #video, #overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }
    
    canvas {
        position: absolute;
        top: 0;
        left: 0;
        transform: scaleX(-1);
    }

    /* --- 5. TOMBOL ABSENSI UNGU --- */
    .btn-capture {
        width: 100%;
        max-width: 340px; 
        background: #6366f1;
        color: white;
        font-weight: 800;
        padding: 14px 28px; 
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 15px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        display: inline-block;
    }

    .btn-capture:hover:not(:disabled) {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .btn-capture:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
    }

    #statusText {
        margin-top: 15px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0;
    }
</style>
    
    <div class="main-wrapper">
        <div class="registration-box">
            <div class="header-section">
                <h2>Registrasi Wajah</h2>
            </div>

            <div class="instruction-box">
                <p>💡 Pastikan wajah Anda terlihat jelas tanpa masker/kacamata hitam</p>
            </div>

            <div class="video-wrapper">
                <video id="video" autoplay muted playsinline></video>
                <canvas id="overlay"></canvas>
                
                <div id="loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center; z-index: 10;">
                    <div class="spinner-border text-light" role="status"></div>
                    <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Memuat AI Face Recognition...</p>
                </div>
            </div>

            <div>
                <button id="btnCapture" class="btn-capture" disabled>
                    Tunggu, AI Sedang Memuat...
                </button>
            </div>
            
            <p id="statusText">Inisialisasi sistem...</p>
        </div>
    </div>

<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay');
    const btnCapture = document.getElementById('btnCapture');
    const statusText = document.getElementById('statusText');
    const loader = document.getElementById('loader');

    async function init() {
        try {
            statusText.innerText = "Memuat model AI...";
            const MODEL_URL = '/models'; 

            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);

            statusText.innerText = "AI Siap! Menyalakan kamera...";
            startVideo(); 
        } catch (err) {
            console.error("Gagal load model:", err);
            statusText.innerText = "Error: " + err.message;
        }
    }

    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
        .then(function(stream) {
            video.srcObject = stream;
        })
        .catch(function(err) {
            statusText.innerText = "Kamera gagal menyala. Cek izin browser.";
        });
    }

    video.addEventListener('play', () => {
        const displaySize = { width: video.videoWidth, height: video.videoHeight };
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        faceapi.matchDimensions(canvas, displaySize);

        loader.style.display = 'none';
        btnCapture.disabled = false;
        btnCapture.innerText = "Ambil Data Wajah";
        statusText.innerText = "Sistem siap.";

        setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks();
            
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);

            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            
            faceapi.draw.drawDetections(canvas, resizedDetections);
            faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
        }, 100);
    });

    btnCapture.addEventListener('click', async () => {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            alert("Error: Meta tag CSRF tidak ditemukan.");
            return;
        }

        btnCapture.disabled = true;
        btnCapture.innerText = "Mengekstrak...";

        const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detection) {
            alert("Wajah tidak terdeteksi. Pastikan wajah terlihat jelas.");
            btnCapture.disabled = false;
            btnCapture.innerText = "Ambil Data Wajah";
            return;
        }

        const faceDescriptor = Array.from(detection.descriptor);
        
        fetch('/face-token/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
            },
            body: JSON.stringify({ face_vector: faceDescriptor })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/presensi"; 
                    }
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Oke'
                });
                btnCapture.disabled = false;
                btnCapture.innerText = "Ambil Data Wajah";
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan koneksi ke server.',
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
            btnCapture.disabled = false;
            btnCapture.innerText = "Ambil Data Wajah";
        });
    });

    window.onload = init;
</script>
@endsection