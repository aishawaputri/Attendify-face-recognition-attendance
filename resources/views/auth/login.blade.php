<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Attendify - Sistem Absensi Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi masuk yang halus untuk form */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-form { animation: slideUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen w-full flex items-center justify-center p-4 sm:p-6 md:p-8">

    <div class="bg-white rounded-3xl md:rounded-[40px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] flex w-full max-w-5xl overflow-hidden min-h-[550px] md:min-h-[600px]">
        
        <!-- SISI KIRI: Background Gradasi Estetik Tanpa Gambar Kampus -->
        <div class="hidden md:flex md:w-1/2 relative overflow-hidden bg-gradient-to-br from-indigo-900 via-slate-900 to-black p-10 lg:p-14 flex-col justify-between">
            
            <!-- Ornamen Dekoratif Lingkaran Abstrak -->
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 shadow-lg">
                    <div class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></div>
                    <span class="text-white text-[10px] font-bold uppercase tracking-widest">Attendance System v2.0</span>
                </div>
            </div>

            <!-- Identitas Utama Aplikasi: Attendify -->
            <div class="text-white relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <!-- Icon Pengganti Geometris -->
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center font-black text-xl text-white shadow-lg shadow-indigo-500/30">
                        A
                    </div>
                    <h2 class="text-2xl font-black tracking-tight bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Attendify</h2>
                </div>
                <h3 class="text-3xl lg:text-4xl font-extrabold leading-[1.2] mb-4">Efisiensi Absensi<br>Dalam Satu Genggaman.</h3>
                <p class="text-slate-400 text-sm lg:text-base max-w-sm leading-relaxed font-medium">
                    Sistem keamanan biometrik terintegrasi untuk mendukung ekosistem kampus digital yang lebih cerdas.
                </p>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 text-xs text-slate-500 font-semibold tracking-wide">
                Secure & Verified Access Only
            </div>
        </div>

        <!-- SISI KANAN: Form Login -->
        <div class="w-full md:w-1/2 p-8 sm:p-10 lg:p-14 flex flex-col justify-center bg-white animate-form relative z-30">
            
            <div class="w-full max-w-sm mx-auto space-y-8">
                <!-- Mobile Logo Header (Hanya muncul jika di HP/Tablet kecil) -->
                <div class="flex items-center gap-2.5 md:hidden mb-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-black text-sm text-white">A</div>
                    <span class="text-lg font-black text-slate-900 tracking-tight">Attendify</span>
                </div>

                <div>
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">Login Akun</h1>
                    <p class="text-slate-500 text-sm mt-2 font-medium leading-relaxed">Silakan gunakan email institusi dan kata sandi Anda untuk masuk ke Attendify.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    @if($errors->any())
                        <div class="bg-rose-50 text-rose-600 p-3 rounded-xl text-xs border border-rose-100 flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Email Institusi</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nim@student.univ.ac.id" 
                            class="w-full bg-slate-50 border-2 border-transparent px-4 py-3.5 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 outline-none transition-all duration-300 font-semibold text-slate-700 text-sm" required>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kata Sandi</label>
                        </div>
                        <input type="password" name="password" placeholder="••••••••••••" 
                            class="w-full bg-slate-50 border-2 border-transparent px-4 py-3.5 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 outline-none transition-all duration-300 font-semibold text-slate-700 text-sm" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-600/30 transition-all duration-300 flex items-center justify-center gap-3 group text-sm">
                            <span>Sign In ke Sistem</span>
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 w-full max-w-sm mx-auto">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] text-center md:text-left">
                    &copy; 2026 Universitas Muhammadiyah Prof. DR. HAMKA
                </p>
            </div>
        </div>
    </div>

</body>
</html>