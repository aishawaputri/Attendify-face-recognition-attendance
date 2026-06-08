@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* CSS Identik dengan Tabel Eduplex */
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
    .modern-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:last-child td { border-bottom: none; }

    /* Badge Status */
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-disetujui { background: #dcfce7; color: #166534; }
    .status-ditolak { background: #fee2e2; color: #b91c1c; }

    /* Tombol Aksi Cepat */
    .btn-acc { background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; cursor: pointer; transition: 0.2s;}
    .btn-acc:hover { background: #059669; }
    .btn-tolak { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; cursor: pointer; transition: 0.2s; margin-left: 5px;}
    .btn-tolak:hover { background: #dc2626; }

    /* --- STYLING MODAL POP-UP (WITH INTERNAL SCROLL) --- */
    .custom-modal {
        display: none; 
        position: fixed; 
        z-index: 9999; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(4px);
    }
    .modal-content-wrapper {
        background-color: #ffffff;
        margin: 4% auto; 
        padding: 24px;
        border: 1px solid #e2e8f0;
        width: 60%; 
        max-width: 800px;
        border-radius: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        position: relative;
        animation: modalSlideUp 0.3s ease-out;
        
        /* Batasi tinggi kartu modal dan aktifkan layout flex */
        max-height: 80vh; 
        display: flex;
        flex-direction: column;
    }
    @keyframes modalSlideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .close-modal-btn {
        position: absolute;
        right: 20px;
        top: 20px;
        color: #94a3b8;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f8fafc;
        z-index: 10;
    }
    .close-modal-btn:hover { color: #1e293b; background: #f1f5f9; }
</style>

<div style="margin-bottom: 25px; margin-top: 10px;">
    <h1 style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 5px;">Validasi Perizinan Mahasiswa</h1>
    <p style="color: #64748b; margin: 0; font-size: 15px;">Kelola pengajuan surat sakit dan izin dari mahasiswa perwalian atau kelas Anda.</p>
</div>

<div class="table-container">
    <table class="modern-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal Absen</th>
                <th style="width: 25%;">Mahasiswa</th>
                <th style="width: 20%;">Keterangan / Alasan</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 25%; text-align: right;">Aksi Cepat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarSurat as $surat)
                <tr>
                    <td>
                        <div style="font-weight: 800; color: #1e293b; font-size: 13px;">
                            {{ \Carbon\Carbon::parse($surat->tanggal_absen)->format('d M Y') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #1e293b; font-size: 14px;">{{ $surat->mahasiswa->nama ?? 'Nama Tidak Ditemukan' }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">NIM: {{ $surat->mahasiswa->nomerIdentitas ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #3b82f6; font-size: 13px;">{{ $surat->jenis_izin }}</div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 500; margin-top: 2px;">
                            Lampiran: 
                            @if($surat->surat_dokumen)
                                <a href="javascript:void(0)" 
                                   onclick="openDocModal('{{ asset($surat->surat_dokumen) }}', '{{ $surat->mahasiswa->nama ?? 'Mahasiswa' }}')" 
                                   style="color: #6366f1; font-weight: 700; text-decoration: underline;">
                                   Lihat File
                                </a>
                            @else
                                <span style="color: #94a3b8;">Tidak Ada File</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($surat->status_acc == 'Pending')
                            <span class="badge-status status-pending">Menunggu ACC</span>
                        @elseif($surat->status_acc == 'Disetujui')
                            <span class="badge-status status-disetujui">Disetujui</span>
                        @else
                            <span class="badge-status status-ditolak">Ditolak</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($surat->status_acc == 'Pending')
                            <form action="{{ route('dosen.pengajuan.update', $surat->id_pengajuan) }}" method="POST" class="form-konfirmasi" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="status" value="Disetujui">
                                <button type="submit" class="btn-acc">✓ ACC</button>
                            </form>
                            
                            <form action="{{ route('dosen.pengajuan.update', $surat->id_pengajuan) }}" method="POST" class="form-konfirmasi" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="status" value="Ditolak">
                                <button type="submit" class="btn-tolak">✕ Tolak</button>
                            </form>
                        @else
                            <span style="font-size: 11px; color: #94a3b8; font-weight: 700;">Telah Diproses</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 80px 0;">
                        <p style="color: #94a3b8; font-weight: 700;">Belum ada pengajuan surat dari mahasiswa saat ini.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="documentModal" class="custom-modal" onclick="closeDocModalOnBackdrop(event)">
    <div class="modal-content-wrapper">
        <span class="close-modal-btn" onclick="closeDocModal()">&times;</span>
        
        <h3 id="modalTitle" style="margin-top: 0; margin-bottom: 18px; font-size: 18px; font-weight: 800; color: #0f172a; padding-right: 40px;">
            Pratinjau Dokumen Lampiran
        </h3>
        
        <div id="modalBodyContainer" style="overflow-y: auto; flex-grow: 1; padding-right: 5px; border-top: 1px solid #f1f5f9; padding-top: 15px;">
            <div id="modalBody" style="display: flex; justify-content: center; align-items: center; min-height: 200px;">
                </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. POP-UP KONFIRMASI SWEETALERT DI TENGAH LAYAR SAAT TOMBOL DIKLIK
        const forms = document.querySelectorAll('.form-konfirmasi');
        
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                
                const statusValue = this.querySelector('input[name="status"]').value;
                const isAcc = statusValue === 'Disetujui';
                const titleText = isAcc ? 'Setujui Perizinan?' : 'Tolak Perizinan?';
                const confirmColor = isAcc ? '#10b981' : '#ef4444';
                const iconType = isAcc ? 'question' : 'warning';

                Swal.fire({
                    title: titleText,
                    text: `Apakah Anda yakin ingin mengubah status perizinan menjadi ${statusValue.toLowerCase()}?`,
                    icon: iconType,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: isAcc ? 'Ya, Setujui!' : 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            });
        });

        // 2. PERBAIKAN: POP-UP BERHASIL TAMPIL BESAR DI TENGAH LAYAR (SAMA SEPERTI SAAT MENANYAKAN)
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#4f46e5', // Menggunakan warna tema indigo Eduplex
                confirmButtonText: 'OK'
            });
        @endif
    });

    // 3. LOGIC POP-UP LIHAT FOTO/DOKUMEN
    function openDocModal(fullAssetUrl, mhsName) {
        const modal = document.getElementById('documentModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        
        modalTitle.innerText = `Surat Lampiran Keterangan: ${mhsName}`;
        const fileExtension = fullAssetUrl.split('.').pop().toLowerCase();
        
        if (fileExtension === 'pdf') {
            modalBody.innerHTML = `<embed src="${fullAssetUrl}" style="width: 100%; height: 60vh; border-radius: 12px;" type="application/pdf">`;
        } else {
            modalBody.innerHTML = `<img src="${fullAssetUrl}" style="max-width: 100%; height: auto; border-radius: 12px; object-fit: contain;" alt="Dokumen Absen">`;
        }
        
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; 
    }

    function closeDocModal() {
        const modal = document.getElementById('documentModal');
        const modalBody = document.getElementById('modalBody');
        
        modal.style.display = "none";
        modalBody.innerHTML = ""; 
        document.body.style.overflow = "auto"; 
    }

    function closeDocModalOnBackdrop(event) {
        const modal = document.getElementById('documentModal');
        if (event.target === modal) {
            closeDocModal();
        }
    }
</script>
@endsection