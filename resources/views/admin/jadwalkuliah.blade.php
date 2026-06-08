@extends('layouts.app')

@section('content')

@include('components.sweetalert')

<style>
    /* --- STYLE KHUSUS JADWAL --- */
    .time-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 12px;
        border-radius: 8px;
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .day-badge {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }

    /* Warna Badge per Hari */
    .day-senin { background: #fee2e2; color: #991b1b; }
    .day-selasa { background: #fef3c7; color: #92400e; }
    .day-rabu { background: #dcfce7; color: #166534; }
    .day-kamis { background: #e0f2fe; color: #075985; }
    .day-jumat { background: #f3e8ff; color: #6b21a8; }
    .day-sabtu { background: #f1f5f9; color: #475569; }

    /* Toggle Switch Style */
    .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #10b981; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Button & Table */
    .btn-primary { background-color: var(--sidebar-bg); color: white; padding: 12px 24px; border-radius: 12px; border: none; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; text-decoration: none; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(50, 44, 95, 0.2); opacity: 0.9; }
    
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { text-align: left; padding: 15px; color: var(--text-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
    .modern-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: var(--text-dark); vertical-align: middle; }
    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f1f5f9 !important;
        cursor: pointer;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }


    .action-btn { background: #f1f5f9; border: none; padding: 8px; border-radius: 10px; cursor: pointer; transition: 0.2s; display: inline-flex; }
    .btn-edit:hover { background-color: #fef08a; }
    .btn-delete:hover { background-color: #fee2e2; }

    /* Modal Overlay */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; visibility: hidden; transition: 0.3s; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box { background: white; width: 95%; max-width: 550px; padding: 30px; border-radius: 24px; transform: scale(0.9); transition: 0.3s; }
    .modal-overlay.active .modal-box { transform: scale(1); }

    .form-control { width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e2e8f0; font-family: inherit; font-size: 14px; outline: none; }
    .form-control:focus { border-color: var(--sidebar-bg); }
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark); }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 5px; color: var(--text-dark);">Sesi Kelas</h1>
        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Atur ketersediaan kelas dalam satu halaman.</p>
    </div>
    <button onclick="openModal('modalCreateJadwal')" class="btn-primary">
        <span style="font-size: 18px;">+</span> Buat Sesi Baru
    </button>
</div>

<div class="card" style="background: white; border-radius: 24px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <table class="modern-table">
        <thead>
            <tr>
                <th style="width: 50px;">NO</th>
                <th>HARI</th>
                <th>MATAKULIAH</th>
                <th>WAKTU</th>
                <th>RUANGAN</th>
                <th>TAHUN / SEMESTER</th>
                <th>STATUS</th>
                <th style="text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $j)
            <tr>
                <td style="font-weight: 700; color: var(--text-muted);">{{ $loop->iteration }}</td>
                <td>
                    <span class="day-badge day-{{ strtolower($j->hari) }}">
                        {{ $j->hari }}
                    </span>
                </td>
                <td>
                    <div style="font-weight: 800; color: #1e293b;">{{ $j->matakuliah->nama_mk }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Dosen: {{ $j->user->nama ?? 'Belum Diatur' }}</div>
                </td>
                <td>
                    <div class="time-badge">
                        {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}
                    </div>
                </td>
                <td>
                    <div style="font-size: 12px; margin-top: 5px; color: #64748b; font-weight: 700;">
                        {{ $j->ruangan }}
                    </div>
                </td>
                <td>
                    <div style="font-weight: 700;">{{ $j->tahun_akademik }}</div>
                    <div style="font-size: 12px; color: #6366f1;">{{ $j->tipe_semester }}</div>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" {{ $j->is_buka ? 'checked' : '' }} disabled>
                        <span class="slider"></span>
                    </label>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button onclick="openEditModal({{ json_encode($j) }})" class="action-btn btn-edit">
                            <img src="{{ asset('icons/edit.png') }}" width="16">
                        </button>
                        <button onclick="confirmDelete({{ $j->id_jadwal }})" class="action-btn btn-delete">
                            <img src="{{ asset('icons/delete.png') }}" width="16">
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:50px; color:var(--text-muted);">Belum ada jadwal kuliah.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modalCreateJadwal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 20px; font-weight: 800;">Buat Jadwal Baru</h2>
            <button onclick="closeModal('modalCreateJadwal')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#94a3b8;">&times;</button>
        </div>
        
        <form action="{{ route('admin.jadwalkuliah.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Pilih Semester Matkul</label>
                    <select id="modalFilterSemester" onchange="filterMatkulBySemester('create')" class="form-control">
                        <option value="">-- Pilih --</option>
                        @for($i=1; $i<=8; $i++) <option value="{{ $i }}">Semester {{ $i }}</option> @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Matakuliah</label>
                    <select name="id_matkul" id="modalSelectMatkul" class="form-control" required disabled>
                        <option value="">Pilih Matakuliah</option>
                        @foreach($matakuliahs as $mk)
                            <option value="{{ $mk->id_matkul }}" data-semester="{{ $mk->semester }}" style="display: none;">
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Hari</label>
                    <select name="hari" class="form-control" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" placeholder="2024/2025" required>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Ruangan</label>
                    <input type="text" name="ruangan" class="form-control" placeholder="Contoh: Lab 01 / Ruang 203" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Dosen Pengajar</label>
                    <select name="id_user" class="form-control" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id_user }}">{{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Semester</label>
                <select name="tipe_semester" class="form-control" required>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px; align-items: center; margin-top: 10px; background: #f8fafc; padding: 12px; border-radius: 12px;">
                <label class="switch">
                    <input type="checkbox" name="is_buka" value="1">
                    <span class="slider"></span>
                </label>
                <span style="font-size: 13px; font-weight: 700; color: #475569;">Buka pendaftaran?</span>

            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 20px;">Simpan Jadwal</button>
        </form>
    </div>
</div>
<div id="modalEditJadwal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 20px; font-weight: 800;">Edit Jadwal Kuliah</h2>
            <button onclick="closeModal('modalEditJadwal')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#94a3b8;">&times;</button>
        </div>
        
        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Filter Semester</label>
                    <select id="edit_modalFilterSemester" onchange="filterMatkulBySemester('edit')" class="form-control" style="background-color: #f1f5f9;">
                        <option value="">-- Pilih --</option>
                        @for($i=1; $i<=8; $i++) 
                            <option value="{{ $i }}">Semester {{ $i }}</option> 
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Matakuliah</label>
                    <select name="id_matkul" id="edit_modalSelectMatkul" class="form-control" required>
                        <option value="">Pilih Matakuliah</option>
                        @foreach($matakuliahs as $mk)
                            <option value="{{ $mk->id_matkul }}" data-semester="{{ $mk->semester }}">
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Hari</label>
                    <select name="hari" id="edit_hari" class="form-control" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" id="edit_tahun" class="form-control" required>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Ruangan</label>
                    <input type="text" name="ruangan" id="edit_ruangan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Dosen Pengajar</label>
                    <select name="id_user" id="edit_dosen" class="form-control" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id_user }}">{{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="edit_mulai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="edit_selesai" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Semester</label>
                <select name="tipe_semester" id="edit_tipe" class="form-control" required>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px; align-items: center; margin-top: 10px; background: #f8fafc; padding: 12px; border-radius: 12px;">
                <label class="switch">
                    <input type="checkbox" name="is_buka" id="edit_is_buka" value="1">
                    <span class="slider"></span>
                </label>
                <span style="font-size: 13px; font-weight: 700; color: #475569;">Buka pendaftaran?</span>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 20px;">Update Jadwal</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function filterMatkulBySemester(type) {
        const prefix = type === 'edit' ? 'edit_' : '';
        const semester = document.getElementById(prefix + 'modalFilterSemester').value;
        const select = document.getElementById(prefix + 'modalSelectMatkul');
        const options = select.getElementsByTagName('option');

        if(type === 'create') select.disabled = (semester === "");
        
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === "") continue;
            const optSem = options[i].getAttribute('data-semester');
            options[i].style.display = (optSem === semester || semester === "") ? "block" : "none";
        }
    }

    function openEditModal(data) {
        document.getElementById('formEdit').action = "/sesikelas/" + data.id_jadwal;
        const matkulOption = document.querySelector(`#edit_modalSelectMatkul option[value="${data.id_matkul}"]`);
        const currentSemester = matkulOption ? matkulOption.getAttribute('data-semester') : "";

        document.getElementById('edit_modalFilterSemester').value = currentSemester;
        document.getElementById('edit_modalSelectMatkul').value = data.id_matkul;
        document.getElementById('edit_hari').value = data.hari;
        document.getElementById('edit_tahun').value = data.tahun_akademik;
        document.getElementById('edit_mulai').value = data.jam_mulai.substring(0, 5); 
        document.getElementById('edit_selesai').value = data.jam_selesai.substring(0, 5);
        document.getElementById('edit_tipe').value = data.tipe_semester;
        document.getElementById('edit_is_buka').checked = (data.is_buka == 1);
        document.getElementById('edit_ruangan').value = data.ruangan;
        document.getElementById('edit_dosen').value = data.id_user;

        filterMatkulBySemester('edit');
        openModal('modalEditJadwal');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data akan dihapus permanen dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'my-rounded-swal',
                confirmButton: 'btn-danger-swal',
                cancelButton: 'btn-cancel-swal'
            },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = "{{ url('sesikelas') }}/" + id;
                form.method = 'POST';
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection