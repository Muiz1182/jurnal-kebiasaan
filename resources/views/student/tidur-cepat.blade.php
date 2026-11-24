@extends('layouts.student')

@section('title', 'Tidur Cepat')

@section('content')
<div class="min-h-screen bg-[#0f172a] text-white p-6">
    <!-- Header Halaman -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-3xl">🌙</span>
            <h1 class="text-2xl font-bold tracking-wide">Tidur Cepat</h1>
        </div>
        <p class="text-slate-400 text-sm max-w-2xl">
            Kualitas tidurmu malam ini menentukan semangat belajarmu besok pagi. Pastikan istirahat cukup untuk tubuh yang sehat dan pikiran yang tajam.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500 text-emerald-400 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Utama Membungkus Layout Grid (Agar Checklist di Kolom Kanan Ikut Terkirim) -->
    <form action="{{ route('student.tidur-cepat.store') }}" method="POST">
        @csrf

        <!-- Layout Utama (Grid 2 Kolom) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Form Log Jurnal Malam Ini (2 Kolom span) -->
            <div class="lg:col-span-2 bg-[#1e293b] border border-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-slate-100">Log Jurnal Malam Ini</h2>
                    
                    <!-- PERBAIKAN: Menambahkan Badge Status Begadang/Tepat Waktu & Tanggal -->
                    <div class="flex items-center gap-2">
                        @if(isset($journal) && $journal->jam_tidur)
                            @if($journal->is_begadang)
                                <span class="text-[10px] font-semibold bg-red-500/10 text-red-400 px-3 py-1 rounded-full border border-red-500/20 flex items-center gap-1">
                                    <span>🚨</span> Begadang
                                </span>
                            @else
                                <span class="text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full border border-emerald-500/20 flex items-center gap-1">
                                    <span>✅</span> Tepat Waktu
                                </span>
                            @endif
                        @endif
                        <span class="text-xs text-slate-400 font-medium bg-slate-800 px-3 py-1 rounded-full border border-slate-700">
                            {{ \Carbon\Carbon::now()->format('d F Y') }}
                        </span>
                    </div>
                </div>

                <!-- Input Jam Tidur & Target Bangun -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Jam Tidur</label>
                        <div class="relative">
                            <input type="time" name="jam_tidur" value="{{ old('jam_tidur', $journal->jam_tidur ?? '') }}" 
                                class="w-full bg-[#0f172a] border border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 transition">
                        </div>
                        @error('jam_tidur')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Target Bangun</label>
                        <div class="relative">
                            <input type="time" name="target_bangun" value="{{ old('target_bangun', $journal->target_bangun ?? '') }}" 
                                class="w-full bg-[#0f172a] border border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 transition">
                        </div>
                        @error('target_bangun')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Pilihan Kualitas Istirahat -->
                <div class="mb-6">
                    <label class="block text-xs font-medium text-slate-300 mb-2">Kualitas Istirahat</label>
                    <div class="grid grid-cols-3 gap-3">
                        @php
                            $currentKualitas = old('kualitas_istirahat', $journal->kualitas_istirahat ?? 'Nyenyak');
                        @endphp

                        <!-- Opsi Buruk -->
                        <label class="cursor-pointer">
                            <input type="radio" name="kualitas_istirahat" value="Buruk" class="peer sr-only" {{ $currentKualitas == 'Buruk' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center p-4 bg-[#0f172a] border border-slate-700 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-500/10 hover:border-slate-600 transition">
                                <span class="text-2xl mb-1">🔴</span>
                                <span class="text-xs font-medium text-slate-300">Buruk</span>
                            </div>
                        </label>

                        <!-- Opsi Cukup -->
                        <label class="cursor-pointer">
                            <input type="radio" name="kualitas_istirahat" value="Cukup" class="peer sr-only" {{ $currentKualitas == 'Cukup' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center p-4 bg-[#0f172a] border border-slate-700 rounded-xl peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 hover:border-slate-600 transition">
                                <span class="text-2xl mb-1">🟢</span>
                                <span class="text-xs font-medium text-slate-300">Cukup</span>
                            </div>
                        </label>

                        <!-- Opsi Nyenyak -->
                        <label class="cursor-pointer">
                            <input type="radio" name="kualitas_istirahat" value="Nyenyak" class="peer sr-only" {{ $currentKualitas == 'Nyenyak' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center p-4 bg-[#0f172a] border border-slate-700 rounded-xl peer-checked:border-indigo-600 peer-checked:bg-indigo-600/20 hover:border-slate-600 transition">
                                <span class="text-2xl mb-1">🟢</span>
                                <span class="text-xs font-medium text-slate-300">Nyenyak</span>
                            </div>
                        </label>
                    </div>
                    @error('kualitas_istirahat')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Refleksi Malam -->
                <div class="mb-6">
                    <label class="block text-xs font-medium text-slate-300 mb-2">Refleksi Malam</label>
                    <textarea name="refleksi_malam" rows="4" 
                        class="w-full bg-[#0f172a] border border-slate-700 rounded-xl p-4 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition"
                        placeholder="Bagaimana perasaanmu hari ini? Apa yang kamu syukuri sebelum tidur?">{{ old('refleksi_malam', $journal->refleksi_malam ?? '') }}</textarea>
                    @error('refleksi_malam')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-xl transition shadow-md shadow-indigo-600/20 text-sm">
                    Simpan Kebiasaan Tidur
                </button>
            </div>

            <!-- Kolom Kanan: Rangkuman, Tips, & Rutinitas (1 Kolom span) -->
            <div class="flex flex-col gap-6">
                
                <!-- Rangkuman Pekan Ini -->
                <div class="bg-[#1e293b] border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-emerald-400 text-sm">📈</span>
                        <h3 class="text-sm font-semibold text-slate-200">Rangkuman Pekan Ini</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-[#0f172a] border border-slate-800 p-3 rounded-xl">
                            <span class="block text-[10px] text-slate-400 mb-1">Rerata Jam Tidur</span>
                            <span class="text-base font-bold text-slate-100">{{ $summary['rerata_jam_tidur'] ?? '-' }}</span>
                        </div>
                        <div class="bg-[#0f172a] border border-slate-800 p-3 rounded-xl">
                            <span class="block text-[10px] text-slate-400 mb-1">Total Durasi</span>
                            <span class="text-base font-bold text-slate-100">{{ $summary['total_durasi'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tips Malam (Banner Edukasi) -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-800 shadow-lg group">
                    <div class="absolute inset-0 bg-cover bg-center filter brightness-50 group-hover:scale-105 transition duration-500" style="background-image: url('https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?q=80&w=600&auto=format&fit=crop')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/40 to-transparent"></div>
                    <div class="relative p-5 flex flex-col justify-end h-40">
                        <span class="bg-indigo-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-md w-fit mb-2">TIPS MALAM</span>
                        <h4 class="text-sm font-bold text-white leading-snug">Hindari Layar HP 30 Menit Sebelum Tidur</h4>
                    </div>
                </div>

                <!-- Rutinitas Sebelum Tidur (Checklist) -->
                <div class="bg-[#1e293b] border border-slate-800 rounded-2xl p-5 shadow-lg">
                    <h3 class="text-sm font-semibold text-slate-200 mb-4">Rutinitas Sebelum Tidur</h3>
                    
                    <!-- PERBAIKAN: Logika in_array agar tidak otomatis ter-checklist saat kosong -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="rutinitas[]" value="ganti_baju" class="rounded border-slate-700 bg-[#0f172a] text-indigo-600 focus:ring-0 w-4 h-4" 
                                {{ (isset($routines) && is_array($routines) && in_array('ganti_baju', $routines)) ? 'checked' : '' }}>
                            <span>Ganti baju tidur</span>
                        </label>
                        <label class="flex items-center gap-3 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="rutinitas[]" value="sikat_gigi" class="rounded border-slate-700 bg-[#0f172a] text-indigo-600 focus:ring-0 w-4 h-4" 
                                {{ (isset($routines) && is_array($routines) && in_array('sikat_gigi', $routines)) ? 'checked' : '' }}>
                            <span>Sikat gigi & Cuci muka</span>
                        </label>
                        <label class="flex items-center gap-3 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="rutinitas[]" value="berdoa" class="rounded border-slate-700 bg-[#0f172a] text-indigo-600 focus:ring-0 w-4 h-4" 
                                {{ (isset($routines) && is_array($routines) && in_array('berdoa', $routines)) ? 'checked' : '' }}>
                            <span>Berdoa</span>
                        </label>
                        <label class="flex items-center gap-3 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="rutinitas[]" value="matikan_lampu" class="rounded border-slate-700 bg-[#0f172a] text-indigo-600 focus:ring-0 w-4 h-4" 
                                {{ (isset($routines) && is_array($routines) && in_array('matikan_lampu', $routines)) ? 'checked' : '' }}>
                            <span>Matikan Lampu</span>
                        </label>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection