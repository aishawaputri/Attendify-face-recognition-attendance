@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .custom-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="w-full text-slate-800 p-2 md:p-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start w-full">
        
        <div class="lg:col-span-2 space-y-6 w-full">
            
            <div class="w-full">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Overview Stats</h2>
                    <a href="#" class="text-xs font-bold text-purple-600 hover:text-purple-700 transition">View All Data</a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">
                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.174L12 6.227l7.74 3.947m-15.48 0L12 14.122l7.74-3.948m-15.48 0v4.711a3 3 0 001.62 2.678l4.5 2.25a3 3 0 002.76 0l4.5-2.25a3 3 0 001.62-2.678v-4.711m-15.48 0L12 14.122m7.74-3.948L12 14.122m0 0v6.463" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Mahasiswa</p>
                            <div class="text-2xl font-black text-slate-900 my-1">{{ $stats['total_mahasiswa'] ?? 3 }}</div>
                        </div>
                        <div class="flex justify-between text-[11px] font-bold text-emerald-600 mt-2 pt-2 border-t border-slate-50">
                            <span>Status Akun</span>
                            <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full text-[10px]">Aktif</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Dosen</p>
                            <div class="text-2xl font-black text-slate-900 my-1">{{ $stats['total_dosen'] ?? 5 }}</div>
                        </div>
                        <div class="flex justify-between text-[11px] font-bold text-indigo-600 mt-2 pt-2 border-t border-slate-50">
                            <span>Status Kerja</span>
                            <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full text-[10px]">Tersedia</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Jadwal Aktif</p>
                            <div class="text-2xl font-black text-slate-900 my-1">{{ $stats['jadwal_aktif'] ?? 13 }}</div>
                        </div>
                        <div class="flex justify-between text-[11px] font-bold text-amber-600 mt-2 pt-2 border-t border-slate-50">
                            <span>Hari Ini</span>
                            <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full text-[10px]">Monitoring</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm w-full h-[390px] flex flex-col">
                <div class="flex justify-between items-center mb-4 flex-shrink-0">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Log Absensi Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Real-time feed kehadiran mahasiswa.</p>
                    </div>
                    <span class="bg-purple-50 text-purple-600 text-[11px] font-bold px-3 py-1.5 rounded-xl">Live Feed</span>
                </div>
                
                <div class="flex-1 overflow-y-auto pr-1 space-y-3 w-full custom-scroll">
                    @forelse($recent_absensis ?? [] as $log)
                    <div class="flex justify-between items-center p-4 bg-slate-50/60 rounded-2xl border border-slate-100 hover:bg-slate-50 transition w-full">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ substr($log->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">{{ $log->user->name ?? 'Mahasiswa' }}</p>
                                <p class="text-xs text-slate-400 font-medium">{{ $log->jadwalKuliah->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-100 px-2.5 py-1 rounded-lg shadow-sm">
                                🕒 {{ isset($log->created_at) ? date('H:i', strtotime($log->created_at)) : '10:34' }}
                            </span>
                            <span class="w-20 text-center bg-emerald-50 text-emerald-600 border border-emerald-100 py-1.5 rounded-xl text-xs font-bold">
                                Hadir
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="flex justify-between items-center p-4 bg-slate-50/60 rounded-2xl border border-slate-100 w-full">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-sm">M</div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">Mahasiswa</p>
                                <p class="text-xs text-slate-400 font-medium">Basis Data Lanjut</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-100 px-2.5 py-1 rounded-lg">🕒 10:34</span>
                            <span class="w-20 text-center bg-emerald-50 text-emerald-600 border border-emerald-100 py-1.5 rounded-xl text-xs font-bold">Hadir</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6 w-full">
            
            <div class="bg-gradient-to-br from-purple-900 via-indigo-950 to-slate-950 text-white rounded-3xl p-6 shadow-md relative overflow-hidden w-full">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>
                
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 rounded-md bg-purple-500 flex items-center justify-center text-xs font-black">A</div>
                    <span class="font-bold text-[10px] tracking-widest text-purple-300 uppercase"> Attendify</span>
                </div>
                
                <h2 class="text-xl font-extrabold tracking-tight mb-1">Sistem Biometrik Aktif</h2>
                <p class="text-xs text-slate-400 leading-relaxed font-medium mb-4">
                    Mengawasi kecocokan vektor wajah mahasiswa otomatis.
                </p>
                
                <div class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-full text-[11px] font-bold text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Online
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm w-full h-[390px] flex flex-col">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex-shrink-0">Jadwal Kuliah Hari Ini</h3>
                
                <div class="flex-1 overflow-y-auto pr-1 space-y-2.5 w-full custom-scroll">
                    @forelse($jadwal_hari_ini ?? [] as $jadwal)
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-slate-200 transition w-full">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-xs text-slate-800 leading-tight">{{ $jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Ruang: {{ $jadwal->ruangan ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold bg-white border border-slate-100 px-2 py-1 rounded-md text-slate-500">
                            {{ isset($jadwal->jam_mulai) ? date('H:i', strtotime($jadwal->jam_mulai)) : '08:00' }}
                        </span>
                    </div>
                    @empty
                    @php
                        $dummies = [
                            ['title' => 'Matematika Diskrit', 'room' => 'Gedung Lama', 'time' => '08:00'],
                            ['title' => 'Pemrograman Berorientasi Objek', 'room' => 'Gedung Lama', 'time' => '08:00'],
                            ['title' => 'Basis Data Lanjut', 'room' => 'R. 302', 'time' => '10:15'],
                            ['title' => 'Pemrograman Laravel', 'room' => 'R. 105', 'time' => '13:00'],
                            ['title' => 'Bahasa Inggris', 'room' => 'Lab 03', 'time' => '15:45'],
                        ];
                    @endphp
                    @foreach($dummies as $d)
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-slate-100 w-full">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-xs text-slate-800 leading-tight">{{ $d['title'] }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Ruang: {{ $d['room'] }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold bg-white border border-slate-100 px-2 py-1 rounded-md text-slate-500">
                            {{ $d['time'] }}
                        </span>
                    </div>
                    @endforeach
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection