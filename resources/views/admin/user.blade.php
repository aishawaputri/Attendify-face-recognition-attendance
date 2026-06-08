@extends('layouts.app')

@section('content')
@include('components.sweetalert')

<style>
    /* Style Konsisten dengan Jadwal Kuliah */
    .btn-primary { background-color: var(--sidebar-bg); color: white; padding: 12px 24px; border-radius: 12px; border: none; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; text-decoration: none; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(50, 44, 95, 0.2); }
    
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

    /* Modal Styling */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; visibility: hidden; transition: 0.3s; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box { background: white; width: 95%; max-width: 500px; padding: 30px; border-radius: 24px; transform: scale(0.9); transition: 0.3s; }
    .modal-overlay.active .modal-box { transform: scale(1); }

    .form-control { width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e2e8f0; font-size: 14px; outline: none; margin-top: 5px; box-sizing: border-box; }
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-size: 13px; font-weight: 700; color: var(--text-dark); }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 5px; color: var(--text-dark);">{{ $title }}</h1>
        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Manajemen data {{ $role }} dalam sistem.</p>
    </div>
    <button onclick="openModalTambah()" class="btn-primary">+ Tambah {{ ucfirst($role) }}</button>
</div>

<div class="card" style="background: white; border-radius: 24px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <table class="modern-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>NAMA</th>
                <th>EMAIL</th>
                @if($role == 'mahasiswa') <th>NIM</th> 
                @elseif($role == 'dosen') <th>NIDN</th> 
                @elseif($role == 'admin') <th>NIK</th> 
                @endif
                <th style="text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td style="font-weight: 700; color: var(--text-muted);">{{ $loop->iteration }}</td>
                <td><div style="font-weight: 800; color: #1e293b;">{{ $user->nama }}</div></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->nomerIdentitas ?? '-' }}</td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button onclick="openEditModal({{ json_encode($user) }})" class="action-btn btn-edit">
                            <img src="{{ asset('icons/edit.png') }}" width="16">
                        </button>
                        <button onclick="confirmDelete('{{ $user->id_user }}')" class="action-btn btn-delete">
                            <img src="{{ asset('icons/delete.png') }}" width="16">
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="modalUser" class="modal-overlay">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 id="modalTitle" style="font-size: 20px; font-weight: 800; margin: 0;">Tambah User</h2>
            <button onclick="closeModal('modalUser')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#94a3b8;">&times;</button>
        </div>
        
        <form id="userForm" action="" method="POST">
            @csrf
            <div id="methodField"></div>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="field_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="field_email" class="form-control" required>
            </div>

            @if($role == 'mahasiswa')
            <div class="form-group">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" id="field_nim" class="form-control">
            </div>
            @elseif($role == 'dosen')
            <div class="form-group">
                <label class="form-label">NIDN</label>
                <input type="text" name="nidn" id="field_nidn" class="form-control">
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="field_password" class="form-control">
                <small id="passHint" style="color: #64748b; display: none; font-size: 11px;">*Kosongkan jika tidak ingin ganti password</small>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">Simpan Data</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function openModalTambah() {
        document.getElementById('modalTitle').innerText = "Tambah {{ ucfirst($role) }}";
        document.getElementById('userForm').action = "{{ route('admin.users.store', $role) }}";
        document.getElementById('methodField').innerHTML = "";
        document.getElementById('userForm').reset();
        document.getElementById('passHint').style.display = 'none';
        document.getElementById('field_password').required = true;
        openModal('modalUser');
    }

    function openEditModal(user) {
        document.getElementById('modalTitle').innerText = "Edit {{ ucfirst($role) }}";
        
        // Sesuaikan dengan route update kamu
        document.getElementById('userForm').action = "/users/" + user.id_user; 
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        
        // Isi data umum
        document.getElementById('field_name').value = user.nama;
        document.getElementById('field_email').value = user.email;
        
        // PENGECEKAN AMAN: Cek dulu ID-nya ada atau tidak sebelum diisi
        if (document.getElementById('field_nim')) {
            document.getElementById('field_nim').value = user.nomerIdentitas;
        }
        if (document.getElementById('field_nidn')) {
            document.getElementById('field_nidn').value = user.nomerIdentitas;
        }
        if (document.getElementById('field_nip')) {
            document.getElementById('field_nip').value = user.nomerIdentitas;
        }
        
        document.getElementById('field_password').required = false;
        document.getElementById('passHint').style.display = 'block';
        
        openModal('modalUser');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: { confirmButton: 'btn-danger-swal', cancelButton: 'btn-cancel-swal' },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = "/users/" + id;
                form.method = 'POST';
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection