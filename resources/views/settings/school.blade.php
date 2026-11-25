@extends('layouts.app')

@section('title', 'Informasi Sekolah')

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
                Informasi Lembaga Sekolah
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Data profil identitas SMPN 9 Probolinggo yang ditampilkan pada sistem.
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

    <!-- CARD UTAMA INFORMASI SEKOLAH -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs max-w-2xl space-y-6">
        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
            <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                Profil Sekolah
            </h2>
        </div>

        <form action="{{ route('settings.school.update') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label for="school_name" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Nama Satuan Pendidikan <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="school_name"
                       name="name" 
                       value="{{ old('name', $school->name ?? 'SMPN 9 Probolinggo') }}" 
                       required
                       class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
            </div>
            
            <div class="space-y-1.5">
                <label for="address" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Alamat Lengkap Sekolah
                </label>
                <textarea id="address"
                          name="address" 
                          rows="3" 
                          placeholder="Masukkan alamat sekolah..."
                          class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">{{ old('address', $school->address ?? '') }}</textarea>
            </div>
            
            <div class="space-y-1.5">
                <label for="headmaster" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Nama Kepala Sekolah
                </label>
                <input type="text" 
                       id="headmaster"
                       name="headmaster" 
                       value="{{ old('headmaster', $school->headmaster ?? '') }}" 
                       placeholder="Nama lengkap beserta gelar"
                       class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
            </div>
            
            <button type="submit" 
                    class="min-h-[48px] px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-2xl font-extrabold text-xs sm:text-sm shadow-md shadow-blue-900/20 transition cursor-pointer pt-2">
                Simpan Perubahan Sekolah
            </button>
        </form>
    </div>

</div>
@endsection
