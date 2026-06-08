<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eduplex Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: #e6f0f9;
            --sidebar-bg: #322c5f;
            --menu-active: #fce872;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --radius-lg: 24px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1;
            margin-left: 270px;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        /* --- STYLING DROP DOWN (Gaya Glenn Dickinson) --- */
        .dropdown-menu {
            position: absolute;
            top: 55px;
            right: 0;
            background: white;
            width: 220px; /* Lebar lebih proporsional */
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 15px;
            display: none;
            z-index: 1000;
            border: 1px solid rgba(0,0,0,0.05);
            animation: fadeIn 0.2s ease-out;
        }

        .dropdown-menu.show { display: block; }

        /* Container Profil di dalam Dropdown */
        .dropdown-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Container Item (Sama Ukuran) */
        .menu-item-container {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #f8fafc; /* Warna container lembut */
            border-radius: 12px;
            text-decoration: none;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            width: 100%;
            cursor: pointer;
        }

        .menu-item-container:hover {
            background: #ffffff;
            border-color: #e2e8f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .menu-item-container span {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        /* Container Khusus Logout */
        .logout-container {
            background: #fff1f2; /* Warna merah muda lembut */
        }
        
        .logout-container:hover {
            border-color: #fecaca;
        }

        .logout-container span {
            color: #ef4444;
        }

        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Ikon Panah Rotasi */
        .arrow-icon {
            transition: transform 0.3s ease;
            width: 10px;
            opacity: 0.4;
        }
        .rotate-arrow { transform: rotate(180deg); }

        .card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }
    </style>
    @stack('styles')
</head>
<body>

    @include('layouts.sidebar')

    <div class="main-content">
        @php
            $hour = date('H');
            $greeting = ($hour >= 5 && $hour < 11) ? 'Good Morning' :
                        (($hour >= 11 && $hour < 15) ? 'Good Afternoon' :
                        (($hour >= 15 && $hour < 18) ? 'Good Evening' : 'Good Night'));
        @endphp

        <div class="top-bar">
            <div class="welcome-text">
                <p style="color: var(--text-muted); font-size: 11px; margin: 0;">Welcome back,</p>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark);">
                {{ $greeting }}, <span style="color: #f39c12;">{{ implode(' ', array_slice(explode(' ', Auth::user()->nama), 0, 2)) }}!</span>                </h3>
            </div>

            <div class="user-profile-wrapper" style="display: flex; align-items: center; gap: 15px; position: relative;">
                
                <div class="user-info" style="text-align: right;">
                    <h4 style="font-size: 13px; font-weight: 700; color: var(--text-dark); margin: 0;">{{ Auth::user()->nama }}</h4>
                    <p style="font-size: 10px; color: var(--text-muted); margin: 0;">{{ Auth::user()->email }}</p>
                </div>

                <div class="profile-container" onclick="toggleDropdown()" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <img src="{{ asset('icons/profile.png') }}" alt="Profile" style="width: 38px; height: 38px; object-fit: contain;">
                    <img src="{{ asset('icons/down.png') }}" id="arrowIcon" class="arrow-icon" alt="v">

                    <div id="profileDropdown" class="dropdown-menu">
                        <div class="dropdown-user-info">
                            <img src="{{ asset('icons/profile.png') }}" alt="Avatar">
                            <div style="overflow: hidden;">
                                <h4 style="font-size: 13px; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->name }}</h4>
                                <p style="font-size: 10px; color: var(--text-muted);">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="menu-item-container">
                            <img src="{{ asset('icons/unlock.png') }}" width="16" alt="key">
                            <span>Ubah Password</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                            @csrf
                            <button type="submit" class="menu-item-container logout-container">
                                <img src="{{ asset('icons/logout_red.png') }}" width="16" alt="logout">
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            const arrow = document.getElementById("arrowIcon");
            dropdown.classList.toggle("show");
            arrow.classList.toggle("rotate-arrow");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-container')) {
                const dropdown = document.getElementById("profileDropdown");
                const arrow = document.getElementById("arrowIcon");
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    arrow.classList.remove('rotate-arrow');
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>