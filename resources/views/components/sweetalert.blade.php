<style>
    /* Styling Dasar Popup */
    .my-rounded-swal {
        border-radius: 24px !important;
        padding: 20px !important;
    }

    /* Tombol Batal (Abu-abu Pudar - Flat) */
    .btn-cancel-swal {
        background-color: #f1f5f9 !important; /* Abu-abu pudar */
        color: #64748b !important;
        padding: 12px 28px !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        margin: 0 8px !important;
        border: none !important; /* Menghapus border */
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-cancel-swal:hover {
        background-color: #e2e8f0 !important;
    }

    /* Tombol Ya, Hapus (Merah Muda Pudar - Flat) */
    .btn-danger-swal {
        background-color: #fee2e2 !important; /* Merah sangat muda */
        color: #ef4444 !important; /* Teks merah tegas */
        padding: 12px 28px !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        margin: 0 8px !important;
        border: none !important; /* Menghapus border */
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-danger-swal:hover {
        background-color: #fca5a5 !important; /* Merah sedikit lebih gelap saat hover */
        color: #b91c1c !important;
    }

    /* Menyesuaikan jarak teks */
    .swal2-title {
        padding-top: 10px !important;
        font-size: 20px !important;
    }
    .swal2-html-container {
        margin: 10px 0 20px 0 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Pop-up Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                customClass: { popup: 'my-rounded-swal' }
            });
        @endif

        // 2. Pop-up Error/Gagal
        @if(session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') ?? $errors->first() }}",
                confirmButtonColor: '#f43f5e',
                customClass: { popup: 'my-rounded-swal' }
            });
        @endif
    });
</script>

<style>
    .my-rounded-swal { border-radius: 20px !important; }
</style>