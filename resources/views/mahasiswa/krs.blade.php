@extends('layouts.app')

@section('content')
<style>
    /* Filter Wrapper Putih agar menyatu */
    .filter-wrapper { 
        background: #ffffff; 
        padding: 25px; 
        border-radius: 20px; 
        color: #1e293b; 
        margin-bottom: 30px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    
    .filter-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr auto auto; 
        gap: 20px; 
        align-items: flex-end; 
    }

    .input-group-custom label { 
        display: block; 
        font-size: 11px; 
        font-weight: 800; 
        color: #94a3b8; 
        margin-bottom: 8px; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
    }

    .select-custom { 
        background: #f8fafc; 
        border: 2px solid #f1f5f9; 
        color: #1e293b; 
        padding: 10px 15px; 
        border-radius: 12px; 
        width: 100%; 
        outline: none; 
        font-weight: 700;
        transition: 0.3s;
    }
    .select-custom:focus { border-color: #3b82f6; background: #fff; }

    .btn-submit { 
        background: #3b82f6; 
        color: white; 
        border: none; 
        padding: 12px 25px; 
        border-radius: 12px; 
        font-weight: 700; 
        cursor: pointer; 
        transition: 0.3s;
    }
    .btn-submit:hover { background: #2563eb; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }

    .sks-container {
        background: #f0f7ff;
        padding: 5px 20px;
        border-radius: 12px;
        border: 1px solid #e0eefe;
        text-align: center;
        min-width: 100px;
    }

    /* Tabel Minimalis */
    .table-container { 
        background: white; 
        border-radius: 20px; 
        overflow: hidden; 
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
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

    .day-header { 
        background: #f8fafc; 
        color: #6366f1; 
        font-weight: 900; 
        font-size: 12px; 
        padding: 12px 20px !important;
        border-left: 4px solid #6366f1;
    }
</style>

<div style="margin-bottom: 25px;">
    <h1 style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 0;">Kartu Rencana Studi</h1>
    <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
        <p style="color: #64748b; margin: 0; font-size: 15px;">
            Periode Aktif: <span style="color: #3b82f6; font-weight: 800;">{{ $realSmtAktif }} {{ $realTaAktif }}</span>
        </p>
        
        @if($filterTa != $realTaAktif || $filterSmt != $realSmtAktif)
            <span style="background: #fff7ed; color: #9a3412; border: 1px solid #ffedd5; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                ⚠ Menampilkan Arsip: {{ $filterSmt }} {{ $filterTa }}
            </span>
        @else
            <span style="background: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                ● Sinkron
            </span>
        @endif
    </div>
</div>

<div class="filter-wrapper">
    <form action="{{ route('mahasiswa.krs') }}" method="GET" class="filter-grid">
        <div class="input-group-custom">
            <label>Tahun Akademik</label>
            <select name="tahun_akademik" class="select-custom">
                @foreach($listTa as $ta)
                    <option value="{{ $ta }}" {{ $filterTa == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group-custom">
            <label>Semester</label>
            <select name="tipe_semester" class="select-custom">
                <option value="Ganjil" {{ $filterSmt == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ $filterSmt == 'Genap' ? 'selected' : '' }}>Genap</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Tampilkan Data</button>

        <div class="sks-container">
            <div style="font-size: 10px; font-weight: 800; color: #64748b;">TOTAL BEBAN</div>
            <div style="font-size: 20px; font-weight: 900; color: #3b82f6;">{{ $totalSks }} <span style="font-size: 12px; font-weight: 700;">SKS</span></div>
        </div>
    </form>
</div>

<div class="table-container">
    <table class="modern-table">
        <thead>
            <tr>
                <th style="width: 30%;">Mata Kuliah</th>
                <th style="width: 10%;">SKS</th>
                <th style="width: 25%;">Dosen</th>
                <th style="width: 20%;">Jadwal</th>
                <th style="width: 15%;">Ruang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($krsData as $hari => $grupKrs)
                <tr>
                    <td colspan="5" class="day-header">{{ strtoupper($hari) }}</td>
                </tr>
                @foreach($grupKrs as $krs)
                <tr>
                    <td>
                        <div style="font-weight: 800; color: #1e293b; font-size: 14px;">{{ $krs->jadwalKuliah->mataKuliah->nama_mk }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">{{ $krs->jadwalKuliah->mataKuliah->kode_mk }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #475569; font-size: 14px;">{{ $krs->jadwalKuliah->mataKuliah->sks }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #475569; font-size: 13px;">{{ $krs->jadwalKuliah->user->nama ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b; font-size: 13px;">
                            {{ substr($krs->jadwalKuliah->jam_mulai, 0, 5) }} - {{ substr($krs->jadwalKuliah->jam_selesai, 0, 5) }}
                        </div>
                    </td>
                    <td>
                        <div style="color: #3b82f6; font-weight: 800; font-size: 12px;">
                             {{ $krs->jadwalKuliah->ruangan }}
                        </div>
                    </td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 80px 0;">
                        <p style="color: #94a3b8; font-weight: 700;">Tidak ada data KRS pada periode ini.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection