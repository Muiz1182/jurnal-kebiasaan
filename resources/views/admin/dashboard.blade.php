@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="w-full px-6 py-6">
    <div class="grid grid-cols-1 gap-8">
        
        <!-- Header Informasi Admin -->
        <header class="flex justify-between items-center bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard Administrator, {{ auth()->user()->name }}</h1>
                <p class="text-gray-500 text-sm mt-1">
                    {{ now()->translatedFormat('l, d F Y') }} | Kelola sistem dan pantau aktivitas guru di sini.
                </p>
            </div>
            <div class="flex gap-3">
                <span class="bg-blue-50 text-blue-900 px-6 py-3 rounded-2xl font-bold text-sm border border-blue-100 flex items-center">
                    Role: Admin Utama
                </span>
            </div>
        </header>

        <!-- Statistik Khusus Admin -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Guru / Pengguna</p>
                <!-- REVISI: Mengubah variabel $totalUsers menjadi $totalTeachers agar hanya menghitung jumlah guru -->
                <h2 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $totalTeachers ?? 0 }}</h2>
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Seluruh Jurnal</p>
                <h2 class="text-3xl font-extrabold text-blue-900 mt-2">{{ $totalJournals ?? 0 }}</h2>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Sistem</p>
                <h2 class="text-3xl font-extrabold text-green-600 mt-2">Active</h2>
            </div>
        </div>

        <!-- Panel Konten Admin -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-4">Panel Kontrol Administrator</h3>
            <p class="text-gray-600 text-sm">Selamat datang di Panel Administrator. Anda memiliki hak akses penuh untuk mengelola data master, melihat seluruh rekapitulasi jurnal, dan pengaturan sistem.</p>
        </div>
        
    </div>
</div>
@endsection