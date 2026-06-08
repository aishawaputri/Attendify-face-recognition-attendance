@extends('layouts.app')

@section('content')
<style>
    /* --- CSS STATISTIK RINGKASAN (BAGIAN ATAS) --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .stats-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stats-info h4 { margin: 0; color: #64748b; font-size: 13px; font-weight: 700; text-transform: uppercase; }
    .stats-info p { margin: 2px 0 0; color: #0f172a; font-size: 24px; font-weight: 900; }

    /* --- CSS LAYOUT KARTU MATAKULIAH (BAGIAN BAWAH) --- */
    .rekap-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 24px;
        margin-top: 20px;
    }
    .rekap-card {
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
    .rekap-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }
    .rekap-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 5px;
        background: linear-gradient(90deg, #6366f1, #a855f7);
    }
    .rekap-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 5px;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .status-hadir { background: #d1fae5; color: #065f46; }
    .status-alpa { background: #fee2e2; color: #991b1b; }

    .rekap-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div style="margin-bottom: 25px;">
    <h1 style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 0;">Rekap Presensi Periode Ini</h1>
    <p style="color: #64748b; margin-top: 5px; font-size: 15px;">
        Daftar ringkasan kehadiran seluruh mata kuliah periode aktif <span style="color: #6366f1; font-weight: 800;">{{ $semester }} {{ $tahunAkademik }}</span>.
    </p>
</div>

<div class="stats-grid">
    <!-- WIDGET 1: TOTAL MATKUL -->
    <div class="stats-card">
        <div class="stats-icon-wrapper" style="background: #e0e7ff;">
            <img src="{{ asset('icons/open-book.png') }}" alt="Total Matkul" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="stats-info">
            <h4>Total Matkul</h4>
            <p>{{ $totalMatkul ?? 0 }}</p>
        </div>
    </div>

    <!-- WIDGET 2: TOTAL HADIR -->
    <div class="stats-card">
        <div class="stats-icon-wrapper" style="background: #d1fae5;">
            <img src="{{ asset('icons/user-check.png') }}" alt="Hadir" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="stats-info">
            <h4>Total Hadir</h4>
            <p>{{ $totalHadir ?? 0 }}</p>
        </div>
    </div>

    <!-- WIDGET 3: TOTAL IZIN / SAKIT -->
    <div class="stats-card">
        <div class="stats-icon-wrapper" style="background: #fef3c7;">
            <img src="{{ asset('icons/surat.png') }}" alt="Izin" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="stats-info">
            <h4>Sakit / Izin</h4>
            <p>{{ $totalIzin ?? 0 }}</p>
        </div>
    </div>

    <!-- WIDGET 4: TOTAL ALFA -->
    <div class="stats-card">
        <div class="stats-icon-wrapper" style="background: #fee2e2;">
            <img src="{{ asset('icons/warning.png') }}" alt="Alfa" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="stats-info">
            <h4>Total Alfa</h4>
            <p>{{ $totalAlfa ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="rekap-grid">
    @forelse($rekapPerMatkul as $rekap)
        <div class="rekap-card">
            <div class="rekap-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a; max-width: 75%; line-height: 1.4;">
                    {{ $rekap->nama_mk }}
                </h3>
                
                <span class="status-badge {{ $rekap->persentase >= 75 ? 'status-hadir' : 'status-alpa' }}">
                    {{ $rekap->persentase }}%
                </span>
            </div>

            <div style="font-size: 13px; font-weight: 700; color: #6366f1; margin-bottom: 15px;">
                Sudah berjalan {{ $rekap->total_berjalan }} pertemuan
            </div>

            <div class="rekap-meta" style="margin-bottom: 18px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 15px;">
                <div class="meta-item">
                    <img src="{{ asset('icons/door.png') }}" alt="Room" style="width: 14px; height: 14px; object-fit: contain;">
                    <span>Ruang: {{ $rekap->ruangan }}</span>
                </div>
                <div class="meta-item">
                    <img src="{{ asset('icons/school (2).png') }}" alt="Teacher" style="width: 14px; height: 14px; object-fit: contain;">
                    <span>Dosen: {{ $rekap->dosen }}</span>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 6px;">
                    <span>Rasio Kehadiran</span>
                    <span>{{ $rekap->hadir }} / 16 Kelas</span>
                </div>
                <div style="width: 100%; height: 8px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                    <div style="width: {{ $rekap->bar_progress }}%; height: 100%; background: {{ $rekap->persentase >= 75 ? '#10b981' : '#ef4444' }}; border-radius: 999px; transition: width 0.5s ease;"></div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; text-align: center; background: #f8fafc; padding: 12px; border-radius: 12px;">
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Hadir</div>
                    <div style="font-size: 16px; font-weight: 900; color: #10b981;">{{ $rekap->hadir }}</div>
                </div>
                <div style="border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Izin/Sakit</div>
                    <div style="font-size: 16px; font-weight: 900; color: #f59e0b;">{{ $rekap->izin }}</div>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Alfa</div>
                    <div style="font-size: 16px; font-weight: 900; color: #ef4444;">{{ $rekap->alfa }}</div>
                </div>
            </div>
            
            @if($rekap->persentase < 75)
                <div style="margin-top: 12px; background: #fff1f2; color: #9f1239; padding: 8px 12px; border-radius: 10px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 5px;">
                    ⚠️ Persentase kehadiran kritis! Kurang dari 75%.
                </div>
            @endif
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; background: white; padding: 60px 20px; border-radius: 20px; border: 2px dashed #e2e8f0;">
            <p style="color: #94a3b8; font-weight: 700; font-size: 16px; margin: 0;">Tidak ada jadwal mata kuliah yang dikontrak untuk hari {{ $today }}.</p>
        </div>
    @endforelse
</div>
@endsection