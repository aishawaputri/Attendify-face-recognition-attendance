@extends('layouts.app')

@section('content')
<style>
    /* --- INTEGRASI LAYOUT EDUPLEX DOSEN --- */
    .banner-eduplex {
        background: #4c1d95; /* Ungu gelap khas Eduplex */
        border-radius: 20px;
        padding: 35px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .banner-eduplex h1 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 10px 0;
    }
    .banner-eduplex p {
        font-size: 14px;
        opacity: 0.85;
        margin: 0;
    }

    /* --- STATISTIK CARD --- */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }
    .card-stat {
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        color: inherit;
        border: 1px solid transparent;
        transition: transform 0.2s, border-color 0.2s;
    }
    .card-stat:hover {
        transform: translateY(-2px);
        border-color: #ddd6fe;
    }
    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }
    .stat-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* --- DUA KOLOM UTAMA --- */
    .dashboard-main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
    }
    @media (max-width: 992px) {
        .dashboard-main-grid {
            grid-template-columns: 1fr;
        }
    }

    .section-title-box {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    .section-title-box h3 {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    /* --- LIST KELAS MENGAJAR HARI INI --- */
    .kelas-list-wrapper {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .item-kelas-bar {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        color: inherit;
        transition: opacity 0.2s;
    }
    .item-kelas-bar:last-child {
        border-bottom: none;
    }
    .item-kelas-bar:hover {
        opacity: 0.8;
    }
    .kelas-info-left h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px 0;
    }
    .kelas-meta-detail {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }
    .kelas-meta-detail strong {
        color: #475569;
    }
    .kelas-info-right {
        text-align: right;
    }
    .time-badge {
        font-size: 11px;
        font-weight: 700;
        color: #7c3aed;
        background: #f5f3ff;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 6px;
    }

    /* --- RE-DESAIN BARU KOTAK PENGAJUAN SURAT --- */
    .surat-side-wrapper {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .new-surat-alert-box {
        background: #fafafa;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .surat-alert-header {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .surat-alert-title {
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }
    .surat-alert-desc {
        font-size: 11.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    .surat-btn-action-purple {
        background: #322c5f;
        color: white;
        text-decoration: none;
        font-size: 11.5px;
        font-weight: 700;
        padding: 10px 14px;
        border-radius: 10px;
        text-align: center;
        display: block;
        transition: background 0.2s;
    }
    .surat-btn-action-purple:hover {
        background: #231e43;
    }
</style>

<div class="banner-eduplex">
    <h1>Selamat Datang, {{ $user->nama }}!</h1>
    <p>NIDN: {{ $user->nomerIdentitas ?? '-' }}</p>
</div>

<div class="stats-container">
    <div class="card-stat">
        <div>
            <div class="stat-label">Jadwal Hari Ini</div>
            <div class="stat-value">{{ $totalKelas ?? 0 }} Mata Kuliah</div>
        </div>
        <div class="stat-icon-box" style="background: #eedfee;">
            <img src="{{ asset('icons/open-book.png') }}" style="width: 20px; height: 20px;">
        </div>
    </div>

    <a href="{{ route('dosen.pengajuan') }}" class="card-stat">
        <div>
            <div class="stat-label">Surat Perlu ACC</div>
            <div class="stat-value" style="color: #1e293b;">
                {{ $suratPending ?? 0 }} Berkas
            </div>
        </div>
        <div class="stat-icon-box" style="background: #fffbeb;">
            <img src="{{ asset('icons/surat.png') }}" style="width: 20px; height: 20px;">
        </div>
    </a>
</div>

<div class="dashboard-main-grid">
    
    <!-- KOLOM KIRI: JADWAL KELAS -->
    <div>
        <div class="section-title-box">
            <h3>Kelas Mengajar Hari Ini</h3>
        </div>
        
        <div class="kelas-list-wrapper">
            @isset($kelasAktif)
                @forelse($kelasAktif as $kelas)
                    <div class="item-kelas-bar">
                        <div class="kelas-info-left">
                            <h4>{{ $kelas->mataKuliah->nama_mk }}</h4>
                            <div class="kelas-meta-detail">
                                Ruangan: <strong>{{ $kelas->ruangan }}</strong>
                            </div>
                        </div>
                        <div class="kelas-info-right">
                            <span class="time-badge">
                                {{ date('H:i', strtotime($kelas->jam_mulai)) }} - {{ date('H:i', strtotime($kelas->jam_selesai)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 10px; color: #94a3b8; font-size: 13px; font-weight: 500;">
                        Tidak ada jadwal mengajar untuk Anda pada hari ini.
                    </div>
                @endforelse
            @endisset
        </div>
    </div>

    <!-- KOLOM KANAN: PERUBAHAN TAMPILAN CARD STATUS PENGAJUAN SURAT -->
    <div>
        <div class="section-title-box">
            <h3>Status Pengajuan Surat</h3>
        </div>
        
        <div class="surat-side-wrapper">
            @if(($suratPending ?? 0) > 0)
                <div class="new-surat-alert-box">
                    <div class="surat-alert-header">
                        <div style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></div>
                        <h5 class="surat-alert-title">Permohonan Mahasiswa</h5>
                    </div>
                    
                    <p class="surat-alert-desc">
                        Terdapat <strong style="color: #1e293b;">{{ $suratPending }} berkas</strong> permohonan dispensasi baru yang sedang memerlukan konfirmasi dan persetujuan Anda selaku dosen pengampu.
                    </p>
                    
                    <a href="{{ route('dosen.pengajuan') }}" class="surat-btn-action-purple">
                        Lihat Absensi Mahasiswa →
                    </a>
                </div>
            @else
                <div style="text-align: center; padding: 30px 10px;">
                    <span style="font-size: 24px; display: inline-block; margin-bottom: 8px;">🎉</span>
                    <p style="color: #94a3b8; font-size: 12px; font-weight: 600; margin: 0;">Semua pengajuan surat mahasiswa telah diproses.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection