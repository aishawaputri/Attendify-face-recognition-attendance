@extends('layouts.app')

@section('content')

@include('components.sweetalert')

<style>
    /* --- SWEETALERT CUSTOM STYLING --- */
    .my-rounded-swal {
        border-radius: 28px !important;
        padding: 2rem !important;
        width: 380px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
    }

    .swal2-title { font-size: 20px !important; font-weight: 800 !important; color: #1e293b !important; }
    .swal2-html-container { font-size: 14px !important; color: #64748b !important; }

    /* Custom Button SweetAlert agar tidak berantakan */
    .btn-danger-swal { background-color: #f43f5e !important; color: white !important; border-radius: 12px !important; padding: 12px 24px !important; font-weight: 700 !important; border: none !important; cursor: pointer !important; margin: 5px !important; }
    .btn-cancel-swal { background-color: #e2e8f0 !important; color: #475569 !important; border-radius: 12px !important; padding: 12px 24px !important; font-weight: 700 !important; border: none !important; cursor: pointer !important; margin: 5px !important; }

    /* --- TABLE & BUTTON STYLING --- */
    .btn-primary { background-color: var(--sidebar-bg); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 14px; border: none; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { background-color: #433a8b; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(50, 44, 95, 0.2); }
    
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { text-align: left; padding: 15px; color: var(--text-muted); font-size: 12px; font-weight: 700; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
    .modern-table td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: var(--text-dark); }
    
    .badge-sks { background: #e0f2fe; color: #0369a1; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    
    .action-btn { background: #f1f5f9; border: none; cursor: pointer; padding: 8px; border-radius: 10px; transition: all 0.2s ease; display: inline-flex; }
    .action-btn img { width: 18px; height: 18px; }
    .btn-edit:hover { background-color: #fef08a; transform: translateY(-2px); }
    .btn-delete:hover { background-color: #fee2e2; transform: translateY(-2px); }

    /* --- FORM & MODAL --- */
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark); }
    .form-control { width: 100%; padding: 12px 15px; border-radius: 10px; border: 2px solid #e2e8f0; background: #f8fafc; font-size: 14px; }
    
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; visibility: hidden; transition: 0.3s; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box { background: white; width: 90%; max-width: 500px; padding: 30px; border-radius: 24px; transform: scale(0.9); transition: 0.3s; }
    .modal-overlay.active .modal-box { transform: scale(1); }
    
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .col-no { width: 50px; text-align: center; color: var(--text-muted); font-weight: 700; }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 5px; color: var(--text-dark);">Manajemen Matakuliah</h1>
        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Kelola data mata kuliah dalam satu halaman.</p>
    </div>
    <button onclick="openModal('modalCreate')" class="btn-primary">
        <span style="font-size: 18px;">+</span> Tambah Matakuliah
    </button>
</div>

<div class="card" style="background: white; border-radius: 24px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <table class="modern-table">
        <thead>
            <tr>
                <th class="col-no">#</th>
                <th>KODE MK</th>
                <th>NAMA MATAKULIAH</th>
                <th>SKS</th>
                <th>SEMESTER</th>
                <th style="text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matakuliahs as $mk)
            <tr onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                <td class="col-no">{{ $loop->iteration }}</td>
                <td><span style="color: #64748b; font-family: monospace;">{{ $mk->kode_mk }}</span></td>
                <td><strong style="color: #1e293b;">{{ $mk->nama_mk }}</strong></td>
                <td><span class="badge-sks">{{ $mk->sks }} SKS</span></td>
                <td>Semester {{ $mk->semester }}</td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button onclick="openEditModal({{ json_encode($mk) }})" class="action-btn btn-edit" title="Edit">
                            <img src="{{ asset('icons/edit.png') }}" alt="Edit">
                        </button>
                        <button onclick="openDeleteModal({{ $mk->id_matkul }})" class="action-btn btn-delete" title="Hapus">
                            <img src="{{ asset('icons/delete.png') }}" alt="Hapus">
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:50px; color:var(--text-muted);">
                    Belum ada data matakuliah.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modalCreate" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2 style="font-size: 20px; font-weight: 800;">Tambah Matakuliah</h2>
            <button onclick="closeModal('modalCreate')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#94a3b8;">&times;</button>
        </div>
        <form action="{{ route('admin.matakuliah.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Kode Matakuliah</label>
                <input type="text" name="kode_mk" class="form-control" placeholder="Contoh: MK001" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Matakuliah</label>
                <input type="text" name="nama_mk" class="form-control" placeholder="Contoh: Pemrograman Web" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Jumlah SKS</label>
                    <input type="number" name="sks" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester</label>
                    <input type="number" name="semester" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">Simpan Data</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2 style="font-size: 20px; font-weight: 800;">Edit Matakuliah</h2>
            <button onclick="closeModal('modalEdit')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#94a3b8;">&times;</button>
        </div>
        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Kode Matakuliah</label>
                <input type="text" name="kode_mk" id="edit_kode" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Matakuliah</label>
                <input type="text" name="nama_mk" id="edit_nama" class="form-control" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Jumlah SKS</label>
                    <input type="number" name="sks" id="edit_sks" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester</label>
                    <input type="number" name="semester" id="edit_semester" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">Update Data</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function openEditModal(data) {
        document.getElementById('edit_kode').value = data.kode_mk;
        document.getElementById('edit_nama').value = data.nama_mk;
        document.getElementById('edit_sks').value = data.sks;
        document.getElementById('edit_semester').value = data.semester;
        
        // Sesuaikan route edit (pastikan route name benar)
        let formUrl = "{{ url('matakuliah') }}/" + data.id_matkul;
        document.getElementById('formEdit').action = formUrl;
        
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
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = "{{ url('matakuliah') }}/" + id;
                form.method = 'POST';
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection