@extends('layouts.app')

@section('content')

@include('components.sweetalert')

<style>
    /* Menggunakan styling yang sama dengan Matakuliah kamu */
    .my-rounded-swal { border-radius: 28px !important; padding: 2rem !important; width: 380px !important; }
    .btn-danger-swal { background-color: #f43f5e !important; color: white !important; border-radius: 12px !important; padding: 12px 24px !important; font-weight: 700 !important; border: none !important; }
    .btn-cancel-swal { background-color: #e2e8f0 !important; color: #475569 !important; border-radius: 12px !important; padding: 12px 24px !important; font-weight: 700 !important; border: none !important; }

    .btn-primary { background-color: var(--sidebar-bg); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { text-align: left; padding: 15px; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
    .modern-table td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    
    .badge-info { background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 8px; font-weight: 600; font-size: 12px; }
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; visibility: hidden; transition: 0.3s; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box { background: white; width: 90%; max-width: 500px; padding: 30px; border-radius: 24px; }
    .form-control { width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e2e8f0; margin-bottom: 15px; }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #1e293b;">Manajemen KRS</h1>
        <p style="color: #64748b; font-size: 14px;">Plotting mahasiswa ke dalam jadwal perkuliahan.</p>
    </div>
    <button onclick="openModal('modalCreate')" class="btn-primary">
        <span style="font-size: 18px;">+</span> Tambah Peserta KRS
    </button>
</div>

<div class="card" style="background: white; border-radius: 24px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <table class="modern-table">
        <thead>
            <tr>
                <th>#</th>
                <th>MAHASISWA</th>
                <th>MATA KULIAH</th>
                <th>HARI / JAM</th>
                <th>RUANGAN</th>
                <th style="text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($krsData as $krs)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <strong style="color: #1e293b;">{{ $krs->user->nama }}</strong><br>
                    <small style="color: #64748b;">{{ $krs->user->nomerIdentitas }}</small>
                </td>
                <td>
                    <span class="badge-info">{{ $krs->jadwalKuliah->mataKuliah->kode_mk }}</span><br>
                    <strong>{{ $krs->jadwalKuliah->mataKuliah->nama_mk }}</strong>
                </td>
                <td>{{ $krs->jadwalKuliah->hari }}, {{ substr($krs->jadwalKuliah->jam_mulai, 0, 5) }}</td>
                <td>{{ $krs->jadwalKuliah->ruangan }}</td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button onclick="openEditModal({{ json_encode($krs) }})" class="btn-primary" style="padding: 8px; background: #f1f5f9; color: #475569;"><img src="{{ asset('icons/edit.png') }}" width="18"></button>
                        <button onclick="openDeleteModal({{ $krs->id_krs }})" class="btn-primary" style="padding: 8px; background: #fee2e2; color: #ef4444;"><img src="{{ asset('icons/delete.png') }}" width="18"></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center; padding:50px; color:#64748b;">Data KRS masih kosong.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modalCreate" class="modal-overlay">
    <div class="modal-box">
        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">Tambah Peserta KRS</h2>
        <form action="{{ route('admin.krs.store') }}" method="POST">
            @csrf
            <label style="font-size: 13px; font-weight: 700;">Pilih Mahasiswa</label>
            <select name="id_user" class="form-control" required>
                <option value="">-- Pilih Mahasiswa --</option>
                @foreach($mahasiswas as $m)
                    <option value="{{ $m->id_user }}">{{ $m->nomerIdentitas }} - {{ $m->nama }}</option>
                @endforeach
            </select>

            <label style="font-size: 13px; font-weight: 700;">Pilih Jadwal Kuliah</label>
            <select name="id_jadwal" class="form-control" required>
                <option value="">-- Pilih Jadwal --</option>
                @foreach($jadwals as $j)
                    <option value="{{ $j->id_jadwal }}">
                        {{ $j->hari }} | {{ $j->mataKuliah->nama_mk }} ({{ $j->ruangan }})
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">Simpan Data</button>
            <button type="button" onclick="closeModal('modalCreate')" style="width: 100%; background:none; border:none; margin-top:10px; color:#64748b; cursor:pointer;">Batal</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">Edit Peserta KRS</h2>
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <label style="font-size: 13px; font-weight: 700;">Mahasiswa</label>
            <select name="id_user" id="edit_user" class="form-control" required>
                @foreach($mahasiswas as $m)
                    <option value="{{ $m->id_user }}">{{ $m->nama }}</option>
                @endforeach
            </select>

            <label style="font-size: 13px; font-weight: 700;">Jadwal Kuliah</label>
            <select name="id_jadwal" id="edit_jadwal" class="form-control" required>
                <option value="">-- Pilih Jadwal Sesi --</option>
                @foreach($jadwals as $j)
                    <option value="{{ $j->id_jadwal }}">
                        {{ $j->mataKuliah->nama_mk }} | {{ $j->hari }} ({{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }})
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">Update Data</button>
            <button type="button" onclick="closeModal('modalEdit')" style="width: 100%; background:none; border:none; margin-top:10px; color:#64748b; cursor:pointer;">Batal</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function openEditModal(data) {
        document.getElementById('edit_user').value = data.id_user;
        document.getElementById('edit_jadwal').value = data.id_jadwal;
        document.getElementById('formEdit').action = "/krs/" + data.id_krs;
        openModal('modalEdit');
    }

    function openDeleteModal(id) {
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
            reverseButtons: true        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = "/krs/" + id;
                form.method = 'POST';
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection