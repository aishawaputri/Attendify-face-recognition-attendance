@extends('layouts.app')

@section('content')
<style>
    /* --- BANNER SELAMAT DATANG --- */
    .welcome-card {
        background: linear-gradient(135deg, var(--sidebar-bg, #4338ca) 0%, #312e81 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    
    .welcome-text h1 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .welcome-text p {
        font-size: 15px;
        opacity: 0.9;
        margin: 0;
    }

    /* --- TIGA KARTU ATAS --- */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #e2e8f0;
        transition: 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }

    .summary-title {
        font-size: 12px;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .summary-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .summary-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* --- SEKSI DETAIL BAWAH (LAYOUT GRID UTAMA) --- */
    .dashboard-details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
    }

    @media (max-width: 991px) {
        .dashboard-details-grid {
            grid-template-columns: 1fr;
        }
    }

    .detail-box {
        background: white;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
    }

    .detail-box h3 {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-top: 0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* --- DESAIN BARU: GRID CARD HORIZONTAL UNTUK KELAS HARI INI --- */
    .class-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
        max-height: 290px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .class-mini-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
        position: relative;
        overflow: hidden;
    }

    .class-mini-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #6366f1;
    }

    .class-time-tag {
        align-self: flex-start;
        font-size: 11px;
        font-weight: 800;
        color: #4338ca;
        background: #e0e7ff;
        padding: 3px 8px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    /* --- SCROLL CONTAINER UNTUK SURAT --- */
    .scrollable-surat-list {
        max-height: 290px;
        overflow-y: auto;
        padding-right: 5px;
    }

    /* Pengaturan Scrollbar Minimalis */
    .class-cards-grid::-webkit-scrollbar,
    .scrollable-surat-list::-webkit-scrollbar {
        width: 5px;
    }
    .class-cards-grid::-webkit-scrollbar-track,
    .scrollable-surat-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .class-cards-grid::-webkit-scrollbar-thumb,
    .scrollable-surat-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* List item surat */
    .tracking-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px;
        background: #f8fafc;
        border-radius: 14px;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
    }

    .badge-status-surat {
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
    }

    .badge-type-surat {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 5px;
        background: #e2e8f0;
        color: #334155;
    }
</style>

<div class="welcome-card">
    <div class="welcome-text">
        <h1>Selamat Datang, {{ $user->nama }}! 👋</h1>
        <p>NIM: {{ $user->nomerIdentitas ?? '-' }} | Program Studi: {{ $user->prodi ?? 'Belum diatur' }}</p>
    </div>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div>
            <div class="summary-title">Jadwal Hari Ini</div>
            <div class="summary-value">{{ $totalJadwalHariIni ?? 0 }} Mata Kuliah</div>
        </div>
        <div class="summary-icon-wrapper" style="background: #e0e7ff;">
            <img src="{{ asset('icons/open-book.png') }}" alt="Jadwal" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
    </div>
    
    <div class="summary-card">
        <div>
            <div class="summary-title">Total Kehadiran</div>
            <div class="summary-value">{{ $totalKehadiranGlobal ?? 100 }}%</div>
        </div>
        <div class="summary-icon-wrapper" style="background: #d1fae5;">
            <img src="{{ asset('icons/user-check.png') }}" alt="Kehadiran" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
    </div>
   
</div>

<div class="dashboard-details-grid">
    
    <div class="detail-box">
        <h3>
            <img src="{{ asset('icons/open-book.png') }}" style="width: 22px; height: 22px; object-fit: contain;" alt=""> 
            Kelas Berlangsung Hari Ini
        </h3>
        
        <div class="class-cards-grid">
            @isset($jadwalHariIni)
                @forelse($jadwalHariIni as $krs)
                    <div class="class-mini-card">
                        <div>
                            <span class="class-time-tag">
                                🕒 {{ date('H:i', strtotime($krs->jadwalKuliah->jam_mulai)) }} - {{ date('H:i', strtotime($krs->jadwalKuliah->jam_selesai)) }}
                            </span>
                            <h4 style="margin: 5px 0; font-size: 14px; font-weight: 800; color: #0f172a; line-height: 1.4;">
                                {{ $krs->jadwalKuliah->mataKuliah->nama_mk }}
                            </h4>
                        </div>
                        <div style="margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                            <div style="font-size: 11px; color: #64748b; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $krs->jadwalKuliah->user->nama ?? '—' }}
                            </div>
                            <div style="font-size: 11px; color: #475569; font-weight: 700; margin-top: 2px;">
                                Ruang {{ $krs->jadwalKuliah->ruangan }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px 10px; color: #94a3b8; font-weight: 600; font-size: 14px;">
                        Tidak ada jadwal kelas aktif untuk periode hari ini.
                    </div>
                @endforelse
            @endisset
        </div>
    </div>

    <div class="detail-box">
        <h3>
            <img src="{{ asset('icons/surat.png') }}" style="width: 22px; height: 22px; object-fit: contain;" alt=""> 
            Status Pengajuan Surat
        </h3>

        <div class="scrollable-surat-list">
            @isset($pengajuanSuratTerakhir)
                @forelse($pengajuanSuratTerakhir as $surat)
                    <div class="tracking-list-item">
                        <div>
                            <h4 style="margin: 0 0 3px; font-size: 13px; font-weight: 800; color: #1e293b; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $surat->jadwalKuliah->mataKuliah->nama_mk ?? 'Surat' }}">
                                {{ $surat->jadwalKuliah->mataKuliah->nama_mk ?? 'Surat Keterangan' }}
                            </h4>
                            <div style="display: flex; gap: 5px; align-items: center; margin-top: 4px;">
                                <span class="badge-type-surat">
                                    {{-- Mengambil isi asli kolom keterangan sakit/izin --}}
                                    {{ $surat->status ?? 'Izin' }}
                                </span>
                                <span style="font-size: 10px; color: #94a3b8; font-weight: 600;">
                                    {{ date('d M', strtotime($surat->tanggal_absen)) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            @if($surat->status_acc == 'Pending')
                                <span class="badge-status-surat" style="background: #fef3c7; color: #d97706;">Pending</span>
                            @elseif($surat->status_acc == 'Disetujui')
                                <span class="badge-status-surat" style="background: #d1fae5; color: #059669;">Setuju</span>
                            @else
                                <span class="badge-status-surat" style="background: #fee2e2; color: #dc2626;">Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 50px 10px; color: #94a3b8; font-weight: 600; font-size: 13px;">
                        Belum ada riwayat pengajuan surat.
                    </div>
                @endforelse
            @endisset
        </div>
    </div>

</div>
@endsection