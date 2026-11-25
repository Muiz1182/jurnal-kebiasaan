@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- HEADER PENCARIAN -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" 
               aria-label="Kembali ke Dashboard"
               class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition shrink-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                    <span>&gt;</span>
                    <span class="text-blue-900 font-bold">Pencarian</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Hasil Pencarian
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Menampilkan data yang cocok dengan kata kunci: <span class="font-extrabold text-blue-900 italic">"{{ $query }}"</span>
                </p>
            </div>
        </div>
    </div>

    <!-- HASIL PENCARIAN -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        @if(isset($results) && count($results) > 0)
            <ul class="divide-y divide-slate-100 text-xs sm:text-sm">
                @foreach($results as $item)
                    <li class="p-5 sm:p-6 hover:bg-slate-50/70 transition">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center text-sm font-black shrink-0 border border-blue-100">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-900 text-sm">{{ $item->title ?? 'Data Ditemukan' }}</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $item->description ?? 'Deskripsi item...' }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="py-16 text-center text-slate-400 p-6">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl text-slate-400">
                    🔍
                </div>
                <p class="font-extrabold text-sm text-slate-700">Tidak ada data yang cocok</p>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    Pencarian untuk "{{ $query }}" tidak menemukan hasil. Coba gunakan kata kunci lainnya.
                </p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition">
                    Kembali ke Dashboard Guru
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
