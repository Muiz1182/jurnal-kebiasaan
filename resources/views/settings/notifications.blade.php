@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    <!-- HEADER PENGATURAN -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Pengaturan</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Preferensi Pemberitahuan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Atur bagaimana Anda menerima pengingat pengisian jurnal dan pembaruan sistem.
            </p>
        </div>
    </div>

    <!-- TABS NAVIGASI SETTINGS (RESPONSIF MOBILE SCROLL) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200/80">
        <a href="{{ route('settings.index') }}" 
           class="min-h-[44px] px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('settings.index') ? 'bg-blue-900 text-white shadow-md shadow-blue-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-user-circle"></i>
            <span>Profil &amp; Keamanan</span>
        </a>
        <a href="{{ route('settings.school') }}" 
           class="min-h-[44px] px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('settings.school') ? 'bg-blue-900 text-white shadow-md shadow-blue-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-school"></i>
            <span>Informasi Sekolah</span>
        </a>
        <a href="{{ route('settings.notifications') }}" 
           class="min-h-[44px] px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('settings.notifications') ? 'bg-blue-900 text-white shadow-md shadow-blue-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-bell"></i>
            <span>Notifikasi</span>
        </a>
    </div>

    <!-- CARD UTAMA PENGATURAN NOTIFIKASI -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs max-w-2xl space-y-6">
        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
            <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                Saluran Notifikasi
            </h2>
        </div>

        <form action="{{ route('settings.notifications.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Notifikasi Email -->
            <label class="flex items-start sm:items-center justify-between p-5 rounded-2xl border border-slate-200/80 hover:border-blue-200 hover:bg-slate-50/60 transition cursor-pointer gap-4 group">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center text-sm font-black shrink-0 border border-blue-100 mt-0.5 sm:mt-0">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-xs sm:text-sm text-slate-900 block group-hover:text-blue-900 transition">
                            Notifikasi Surel (Email)
                        </span>
                        <span class="text-xs text-slate-500 font-medium mt-0.5 block">
                            Terima pemberitahuan agenda belajar dan laporan akademik ke email Anda.
                        </span>
                    </div>
                </div>
                <input type="checkbox" 
                       name="email_notifications" 
                       value="1" 
                       {{ auth()->user()->email_notifications ? 'checked' : '' }} 
                       class="w-5 h-5 rounded-md border-slate-300 text-blue-900 focus:ring-blue-900 cursor-pointer shrink-0 mt-1 sm:mt-0">
            </label>
            
            <!-- Notifikasi Browser -->
            <label class="flex items-start sm:items-center justify-between p-5 rounded-2xl border border-slate-200/80 hover:border-blue-200 hover:bg-slate-50/60 transition cursor-pointer gap-4 group">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-900 flex items-center justify-center text-sm font-black shrink-0 border border-indigo-100 mt-0.5 sm:mt-0">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-xs sm:text-sm text-slate-900 block group-hover:text-blue-900 transition">
                            Notifikasi Browser / Perangkat
                        </span>
                        <span class="text-xs text-slate-500 font-medium mt-0.5 block">
                            Tampilkan pesan pengingat langsung saat menggunakan aplikasi di perangkat ini.
                        </span>
                    </div>
                </div>
                <input type="checkbox" 
                       name="browser_notifications" 
                       value="1" 
                       {{ auth()->user()->browser_notifications ? 'checked' : '' }} 
                       class="w-5 h-5 rounded-md border-slate-300 text-blue-900 focus:ring-blue-900 cursor-pointer shrink-0 mt-1 sm:mt-0">
            </label>

            <div class="pt-4">
                <button type="submit" 
                        class="min-h-[48px] px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-2xl font-extrabold text-xs sm:text-sm shadow-md shadow-blue-900/20 transition cursor-pointer">
                    Simpan Pengaturan Notifikasi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
