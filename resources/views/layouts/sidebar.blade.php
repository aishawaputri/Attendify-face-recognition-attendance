<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    .sidebar { 
        width: 270px; 
        background-color: var(--sidebar-bg); 
        height: 100vh; 
        position: fixed; 
        left: 0; top: 0; 
        padding: 30px 20px; 
        border-radius: 0 35px 35px 0; 
        display: flex; 
        flex-direction: column; 
        z-index: 1000; 
        box-shadow: 15px 0 50px rgba(21, 28, 44, 0.15); 
        font-family: 'Poppins', sans-serif; 
    }

    .logo-container { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; padding-left: 10px; }
    .logo-icon { width: 30px; height: 30px; background: var(--card-blue); border-radius: 8px; }
    .nav-menu { display: flex; flex-direction: column; gap: 5px; flex: 1; }
    
    .menu-header {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin: 20px 0 5px 15px;
    }

    .nav-item { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        padding: 14px 20px;
        border-radius: 30px; 
        text-decoration: none; 
        color: #7b8a9e; 
        font-weight: 600; 
        font-size: 16px; 
        transition: all 0.3s ease; 
    }
    
    .nav-item.active { 
        background-color: var(--menu-active); 
        color: var(--sidebar-bg); 
        border-radius: 30px; 
        box-shadow: 0 3px 13px rgba(252, 232, 114, 0.5), 0 0 7px rgba(252, 232, 114, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .nav-item:hover:not(.active) { 
        color: #ffffff; 
        background: rgba(255,255,255,0.05); 
    }

    .logout-wrapper {
        margin-top: auto; 
        margin-bottom: 20px; 
        padding-top: 20px; 
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .logout-btn {
        display: flex; 
        align-items: center; 
        gap: 15px; 
        padding: 14px 18px; 
        border-radius: 30px;
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
        border: none; 
        width: 100%; 
        cursor: pointer; 
        font-size: 16px; 
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: #f43f5e;
        color: #ffffff;
        box-shadow: 0 8px 25px rgba(244, 63, 94, 0.4);
        transform: translateY(-2px);
    }
</style>

@php
    $menus = [];
    $user = auth()->user();

    // ==========================================
    // MENU KHUSUS ADMIN
    // ==========================================
    if ($user->isAdmin()) {
        $menus = [
            ['header' => 'MAIN MENU'],
            ['url' => 'admin/dashboard',   'icon' => 'dashboard.png', 'label' => 'Dashboard'],
            ['url' => 'matakuliah',  'icon' => 'book.png',      'label' => 'Matakuliah'],
            ['url' => 'sesikelas','icon' => 'schedule.png',  'label' => 'Sesi Kelas'],

            ['header' => 'USER MANAGEMENT'],
            ['url' => 'mahasiswa',   'icon' => 'group.png',     'label' => 'Mahasiswa'],
            ['url' => 'dosen',       'icon' => 'school.png',    'label' => 'Dosen'],
        ];
    }
    // ==========================================
    // MENU KHUSUS DOSEN
    // ==========================================
    elseif ($user->isDosen()) {
        $menus = [
            ['header' => 'AKADEMIK'],
            ['url' => 'dosen/dashboard',   'icon' => 'dashboard.png', 'label' => 'Dashboard'],
            ['url' => 'dosen/jadwal',      'icon' => 'schedule.png',  'label' => 'Jadwal Mengajar'],
            ['url' => 'dosen/perizinan',      'icon' => 'report .png',  'label' => 'Perizinan Surat'],
        
        ];
    }
    // ==========================================
    // MENU KHUSUS MAHASISWA
    // ==========================================
    elseif ($user->isMahasiswa()) {
        $menus = [
            ['header' => 'PERKULIAHAN'],
            // Ingat, URL dashboard mahasiswa tidak pakai prefix 'mahasiswa'
            ['url' => 'dashboard',         'icon' => 'dashboard.png', 'label' => 'Dashboard'],
            ['url' => 'krs',  'icon' => 'schedule.png',  'label' => 'KRS'],
            ['url' => 'presensi','icon' => 'presensi.png',      'label' => 'Presensi'],
            ['url' => 'rekap','icon' => 'report .png',      'label' => 'Rekap Presensi'],
        ];
    }
@endphp

<div class="sidebar">
    <div class="logo-container">
        <div class="logo-icon"></div>
        <div style="color: white; font-size: 24px; font-weight: 800;">Attendify</div>
    </div>
    
    <nav class="nav-menu">
        
        @foreach($menus as $menu)
            @if(isset($menu['header']))
                <div class="menu-header">{{ $menu['header'] }}</div>
            @else
                <a href="{{ url($menu['url']) }}" class="nav-item {{ request()->is($menu['url'] . '*') ? 'active' : '' }}">
                    <img src="{{ asset('icons/' . $menu['icon']) }}" alt="icon" style="width: 22px; height: 22px; object-fit: contain;">
                    {{ $menu['label'] }}
                </a>
            @endif
        @endforeach

        <div class="logout-wrapper">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <img src="{{ asset('icons/logout.png') }}" alt="logout" style="width: 22px; height: 22px; object-fit: contain;">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="bottom-widget"></div>
</div>