@extends('layouts.student')

@section('title', 'Bangun Pagi')

@section('content')
@php
    // Waktu otomatis terkunci format 24 Jam saat halaman dibuka
    $waktuSekarang = $journal->waktu_bangun ?? \Carbon\Carbon::now()->format('H:i');
    
    // Logika otomatis status waktu berdasarkan format 24 jam
    if (isset($journal->status_waktu)) {
        $statusOtomatis = $journal->status_waktu;
    } else {
        $jamMenit = str_replace(':', '', $waktuSekarang);
        if ($jamMenit <= '0400') {
            $statusOtomatis = 'Tepat Waktu';
        } elseif ($jamMenit <= '0600') {
            $statusOtomatis = 'Lewat Sedikit';
        } else {
            $statusOtomatis = 'Kesiangan';
        }
    }
@endphp

<!-- Form utama untuk menyimpan data jurnal Bangun Pagi -->
<form id="form-bangun-pagi" action="{{ route('student.bangun-pagi.update') }}" method="POST" class="flex flex-col min-h-screen bg-gray-50 w-full"
      x-data="{ 
          selectedMood: '{{ $journal->mood ?? 'BERENERGI' }}'
      }">
    @csrf

    <!-- KONTEN UTAMA HALAMAN -->
    <main class="flex-1 p-8 space-y-6 overflow-y-auto">
        
        <!-- HEADER BANNER -->
        <div class="relative bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 overflow-hidden">
            <!-- Efek Background Dekoratif -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="space-y-2 relative z-10">
                <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold tracking-wider uppercase border border-white/10">
                    Kebiasaan 1
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight">Bangun Pagi</h1>
                <p class="text-blue-200 text-sm italic font-medium">
                    "Awal hari yang disiplin adalah kunci kesuksesan seorang pelajar hebat."
                </p>
            </div>

            <!-- Widget Tanggal Hari Ini -->
            <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/15 px-5 py-3 rounded-2xl flex items-center gap-3 shadow-inner">
                <div class="p-2.5 bg-white/10 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-blue-200 font-bold">Hari Ini</p>
                    <p class="text-sm font-extrabold text-white">{{ \Carbon\Carbon::now()->format('l, d M') }}</p>
                </div>
            </div>
        </div>

        <!-- GRID KONTROL UTAMA -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- KARTU 1: WAKTU BANGUN -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl shadow-inner border border-pink-100">
                            ⏰
                        </div>
                        <div>
                            <h2 class="font-extrabold text-gray-900 text-base">Waktu Bangun</h2>
                            <p class="text-xs text-gray-400 font-medium">Terkunci otomatis saat halaman dibuka</p>
                        </div>
                    </div>

                    <!-- Waktu otomatis terkunci format 24 jam -->
                    <div class="relative mb-2">
                        <input type="hidden" name="waktu_bangun" value="{{ $waktuSekarang }}">
                        
                        <div class="w-full bg-gray-100 border border-gray-200 rounded-2xl px-5 py-4 text-2xl font-black text-gray-700 tracking-wider flex items-center justify-between select-none cursor-not-allowed">
                            <span>{{ \Carbon\Carbon::parse($waktuSekarang)->format('H:i') }} WIB</span>
                            <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full font-bold uppercase tracking-wider">Locked</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 font-medium mb-4">*Catatan: Jam format 24 jam dan status waktu terisi otomatis secara permanen.</p>
                </div>

                <!-- Tampilan Status Waktu Terkunci (Locked / Tidak Bisa Dipilih) -->
                <div>
                    <input type="hidden" name="status_waktu" value="{{ $statusOtomatis }}">
                    
                    <div class="w-full p-4 rounded-2xl border text-center transition flex items-center justify-between shadow-sm select-none cursor-not-allowed
                        @if($statusOtomatis === 'Tepat Waktu') bg-blue-50 border-blue-200 text-blue-900
                        @elseif($statusOtomatis === 'Lewat Sedikit') bg-amber-50 border-amber-200 text-amber-900
                        @else bg-red-50 border-red-200 text-red-950 @endif">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📌</span>
                            <span class="text-xs font-bold uppercase tracking-wider">Status Waktu Otomatis:</span>
                        </div>
                        <span class="text-xs font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-sm
                            @if($statusOtomatis === 'Tepat Waktu') bg-blue-900 text-white
                            @elseif($statusOtomatis === 'Lewat Sedikit') bg-amber-600 text-white
                            @else bg-red-600 text-white @endif">
                            {{ $statusOtomatis }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- KARTU 2: RUTINITAS PAGI -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner border border-amber-100">
                        ✨
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-900 text-base">Rutinitas Pagi</h2>
                        <p class="text-xs text-gray-400 font-medium">Centang kegiatan yang sudah kamu lakukan</p>
                    </div>
                </div>

                <!-- Daftar Checkbox Rutinitas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-auto">
                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100/80 cursor-pointer transition">
                        <input type="checkbox" name="merapikan_tempat_tidur" value="1" {{ optional($journal)->merapikan_tempat_tidur ? 'checked' : '' }}
                               class="w-5 h-5 rounded-lg text-blue-900 border-gray-300 focus:ring-blue-900">
                        <span class="text-xs font-bold text-gray-700">Merapikan Tempat Tidur</span>
                    </label>

                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100/80 cursor-pointer transition">
                        <input type="checkbox" name="minum_air_putih" value="1" {{ optional($journal)->minum_air_putih ? 'checked' : '' }}
                               class="w-5 h-5 rounded-lg text-blue-900 border-gray-300 focus:ring-blue-900">
                        <span class="text-xs font-bold text-gray-700">Minum Air Putih</span>
                    </label>

                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100/80 cursor-pointer transition">
                        <input type="checkbox" name="mandi_pagi" value="1" {{ optional($journal)->mandi_pagi ? 'checked' : '' }}
                               class="w-5 h-5 rounded-lg text-blue-900 border-gray-300 focus:ring-blue-900">
                        <span class="text-xs font-bold text-gray-700">Mandi Pagi</span>
                    </label>

                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100/80 cursor-pointer transition">
                        <input type="checkbox" name="persiapan_sekolah" value="1" {{ optional($journal)->persiapan_sekolah ? 'checked' : '' }}
                               class="w-5 h-5 rounded-lg text-blue-900 border-gray-300 focus:ring-blue-900">
                        <span class="text-xs font-bold text-gray-700">Persiapan Sekolah</span>
                    </label>
                </div>
            </div>

        </div>

        <!-- GRID BAGIAN BAWAH: MOOD & REFLEKSI -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- KARTU 3: MOOD PAGI INI -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="font-extrabold text-gray-900 text-sm mb-1">Mood Pagi Ini</h2>
                    <p class="text-xs text-gray-400 font-medium mb-4">Bagaimana perasaanmu saat mengawali hari?</p>
                </div>

                <input type="hidden" name="mood" x-model="selectedMood">
                <div class="grid grid-cols-3 gap-3">
                    <!-- Berenergi -->
                    <button type="button" @click="selectedMood = 'BERENERGI'"
                            :class="selectedMood === 'BERENERGI' ? 'border-amber-400 bg-amber-50/60 shadow-md ring-2 ring-amber-400/20' : 'border-gray-200 bg-gray-50 hover:bg-gray-100'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border transition group">
                        <span class="text-2xl mb-1 group-hover:scale-110 transition">⚡</span>
                        <span class="text-[10px] font-extrabold text-gray-700 uppercase tracking-tight">Berenergi</span>
                    </button>

                    <!-- Senang -->
                    <button type="button" @click="selectedMood = 'SENANG'"
                            :class="selectedMood === 'SENANG' ? 'border-emerald-400 bg-emerald-50/60 shadow-md ring-2 ring-emerald-400/20' : 'border-gray-200 bg-gray-50 hover:bg-gray-100'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border transition group">
                        <span class="text-2xl mb-1 group-hover:scale-110 transition">😊</span>
                        <span class="text-[10px] font-extrabold text-gray-700 uppercase tracking-tight">Senang</span>
                    </button>

                    <!-- Mengantuk -->
                    <button type="button" @click="selectedMood = 'MENGANTUK'"
                            :class="selectedMood === 'MENGANTUK' ? 'border-blue-400 bg-blue-50/60 shadow-md ring-2 ring-blue-400/20' : 'border-gray-200 bg-gray-50 hover:bg-gray-100'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border transition group">
                        <span class="text-2xl mb-1 group-hover:scale-110 transition">😴</span>
                        <span class="text-[10px] font-extrabold text-gray-700 uppercase tracking-tight">Mengantuk</span>
                    </button>
                </div>
            </div>

            <!-- KARTU 4: REFLEKSI SINGKAT -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <h2 class="font-extrabold text-gray-900 text-sm mb-1">Refleksi Singkat</h2>
                    <p class="text-xs text-gray-400 font-medium mb-3">Tuliskan catatan kecil atau hal positif yang ingin kamu capai hari ini.</p>
                </div>
                <div>
                    <textarea name="refleksi" rows="3" placeholder="Bagaimana perasaanmu setelah bangun lebih awal hari ini? Apa rencanamu untuk hari ini?"
                              class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition resize-none">{{ optional($journal)->refleksi }}</textarea>
                </div>
            </div>

        </div>

    </main>
</form>
@endsection