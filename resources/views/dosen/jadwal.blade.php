@extends('layouts.app')

@section('content')
<style>
    /* Tabel Minimalis (Diambil dari KRS Mahasiswa) */
    .table-container { 
        background: white; 
        border-radius: 20px; 
        overflow: hidden; 
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { 
        background: #f8fafc; 
        padding: 18px 20px; 
        text-align: left; 
        font-size: 11px; 
        font-weight: 800; 
        color: #64748b; 
        border-bottom: 2px solid #f1f5f9;
        text-transform: uppercase;
    }
    .modern-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; }

    /* Baris Pemisah Hari (Identik dengan KRS Mahasiswa) */
    .day-header { 
        background: #f8fafc; 
        color: #6366f1; 
        font-weight: 900; 
        font-size: 12px; 
        padding: 12px 20px !important;
        border-left: 4px solid #6366f1;
        text-transform: uppercase;
    }
    
    .modern-table tr:last-child td {
        border-bottom: none;
    }

    .btn-kelola-action {
        font-size: 12px;
        font-weight: 700;
        color: #7c3aed;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .btn-kelola-action:hover {
        opacity: 0.7;
    }
</style>

<div style="margin-bottom: 25px; margin-top: 10px;">
    <h1 style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 5px;">Jadwal Mengajar Anda</h1>
    <p style="color: #64748b; margin: 0; font-size: 15px;">Daftar seluruh kelas dan mata kuliah yang Anda ampu pada periode semester ini.</p>
</div>

<div class="table-container">
    <table class="modern-table">
        <thead>
            <tr>
                <th style="width: 35%;">Mata Kuliah</th>
                <th style="width: 10%;">SKS</th>
                <th style="width: 20%;">Jadwal</th>
                <th style="width: 15%;">Ruang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $hasJadwal = false;
            @endphp

            @foreach($daftarHari as $hari)
                @if(isset($jadwalPerHari[$hari]) && count($jadwalPerHari[$hari]) > 0)
                    @php $hasJadwal = true; @endphp
                    
                    <tr>
                        <td colspan="5" class="day-header">{{ strtoupper($hari) }}</td>
                    </tr>

                    @foreach($jadwalPerHari[$hari] as $jadwal)
                        <tr>
                            <td>
                                <div style="font-weight: 800; color: #1e293b; font-size: 14px;">{{ $jadwal->mataKuliah->nama_mk }}</div>
                                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">{{ $jadwal->mataKuliah->kode_mk ?? 'INF' . $jadwal->id_jadwal }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #475569; font-size: 14px;">{{ $jadwal->mataKuliah->sks ?? '3' }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #1e293b; font-size: 13px;">
                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                </div>
                            </td>
                            <td>
                                <div style="color: #3b82f6; font-weight: 800; font-size: 12px;">
                                    {{ $jadwal->ruangan }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach

            @if(!$hasJadwal)
                <tr>
                    <td colspan="5" style="text-align: center; padding: 80px 0;">
                        <p style="color: #94a3b8; font-weight: 700;">Tidak ada jadwal mengajar yang terdaftar untuk semester ini.</p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection