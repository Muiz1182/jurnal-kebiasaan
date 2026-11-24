@extends('layouts.student')

@section('title', 'Rajin Berolahraga - Jurnal Harian 7 Kebiasaan Anak Indonesia')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">

        <!-- Notifikasi Flash Message -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-5 py-4 rounded-2xl shadow-sm text-sm font-semibold flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-5 py-4 rounded-2xl shadow-sm text-sm font-semibold space-y-1">
                <div class="flex items-center gap-2 text-rose-700 font-bold">
                    <span>⚠️</span> Mohon lengkapi isian formulir:
                </div>
                <ul class="list-disc list-inside text-xs text-rose-800 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HEADER BANNER NAVY MODERN (ANTISLOP) -->
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-slate-800">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-emerald-400/30">
                            KEBIASAAN 3
                        </span>
                        @if($hasReachedTarget)
                            <span class="px-3 py-1 bg-emerald-500 text-slate-950 text-[11px] font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                                Target 30 Menit Tercapai
                            </span>
                        @elseif($journal)
                            <span class="px-3 py-1 bg-amber-400 text-slate-950 text-[11px] font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                                Durasi Terisi ({{ $journal->durasi }} Menit)
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-slate-700">
                                Belum Mengisi Hari Ini
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                        Rajin Berolahraga
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm font-medium leading-relaxed">
                        Aktivitas fisik teratur menjaga kebugaran tubuh, meningkatkan fokus belajar, dan memicu energi positif setiap hari.
                    </p>
                </div>

                <!-- Indikator Tanggal & Tombol Aksi -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full md:w-auto shrink-0">
                    <div class="bg-slate-800/90 border border-slate-700 px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl flex items-center gap-3">
                        <span class="text-lg sm:text-xl shrink-0">📅</span>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">TANGGAL HARI INI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white truncate">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>

                    @if($journal)
                        <button type="button" onclick="confirmReset()" class="w-full sm:w-auto min-h-[44px] bg-rose-500/10 hover:bg-rose-500 text-rose-300 hover:text-white border border-rose-500/30 font-bold px-3.5 py-2.5 rounded-xl sm:rounded-2xl transition text-xs flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>🗑️</span> Kosongkan
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- FORM UTAMA -->
        <form id="form-olahraga" action="{{ route('student.olahraga.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- KOLOM KIRI DAN TENGAH (FORMULIR INPUT) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- 1. PILIH JENIS OLAHRAGA (MULTI-SELECT) -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-slate-900">
                                    1. Jenis Olahraga yang Dilakukan
                                </h3>
                                <p class="text-xs text-slate-500">Pilih satu atau lebih olahraga yang kamu lakukan hari ini</p>
                            </div>
                            <span id="selected_sports_count" class="text-xs font-bold px-3 py-1 bg-slate-100 text-slate-700 rounded-full w-fit">
                                0 Dipilih
                            </span>
                        </div>

                        @php
                            $savedSportsRaw = old('jenis_olahraga');
                            if (!$savedSportsRaw && isset($journal->jenis_olahraga)) {
                                $savedSportsRaw = explode(', ', $journal->jenis_olahraga);
                            }
                            $oldSports = is_array($savedSportsRaw) ? array_map('trim', $savedSportsRaw) : [];

                            $presetSports = [
                                ['name' => 'Lari / Jogging', 'icon' => '🏃‍♂️'],
                                ['name' => 'Jalan Kaki Cepat', 'icon' => '🚶‍♂️'],
                                ['name' => 'Bersepeda', 'icon' => '🚴‍♂️'],
                                ['name' => 'Berenang', 'icon' => '🏊‍♂️'],
                                ['name' => 'Sepak Bola / Futsal', 'icon' => '⚽'],
                                ['name' => 'Bola Basket', 'icon' => '🏀'],
                                ['name' => 'Bulu Tangkis', 'icon' => '🏸'],
                                ['name' => 'Bola Voli', 'icon' => '🏐'],
                                ['name' => 'Senam / Yoga', 'icon' => '🤸'],
                                ['name' => 'Latihan Beban / Push-up', 'icon' => '🏋️‍♂️'],
                                ['name' => 'Lompat Tali / Skipping', 'icon' => '🪢'],
                                ['name' => 'Lainnya', 'icon' => '➕'],
                            ];

                            $presetNames = array_column($presetSports, 'name');
                            $customSports = array_filter($oldSports, function($s) use ($presetNames) {
                                return !in_array($s, $presetNames);
                            });
                            $hasCustomSelected = count($customSports) > 0;
                        @endphp

                        <!-- Container input hidden array jenis_olahraga[] -->
                        <div id="hidden_sports_container">
                            @foreach($oldSports as $sportItem)
                                <input type="hidden" name="jenis_olahraga[]" value="{{ $sportItem }}" class="hidden-sport-input" data-sport="{{ $sportItem }}">
                            @endforeach
                        </div>

                        <!-- Grid Pilihan Olahraga -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($presetSports as $sport)
                                @php
                                    $isSelected = ($sport['name'] === 'Lainnya')
                                        ? $hasCustomSelected
                                        : in_array($sport['name'], $oldSports);
                                @endphp
                                <button type="button"
                                    onclick="toggleSport('{{ $sport['name'] }}', this)"
                                    data-sport-name="{{ $sport['name'] }}"
                                    class="sport-btn p-3.5 sm:p-4 rounded-2xl border text-center flex flex-col items-center justify-center gap-2 transition cursor-pointer {{ $isSelected ? 'border-emerald-600 bg-emerald-50 text-emerald-950 font-bold shadow-sm ring-1 ring-emerald-500' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white font-medium hover:bg-slate-50' }}">
                                    <span class="text-2xl">{{ $sport['icon'] }}</span>
                                    <span class="text-xs leading-snug text-center">{{ $sport['name'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Form Input Tambahan untuk Kategori Lainnya -->
                        <div id="custom_sport_wrapper" class="{{ $hasCustomSelected ? '' : 'hidden' }} bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <label class="block text-xs font-bold text-slate-800">
                                Tambah Olahraga Lainnya:
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="custom_sport_input" placeholder="Contoh: Tenis Meja, Bela Diri, dsb."
                                    class="flex-1 rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <button type="button" onclick="addCustomSport()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition cursor-pointer">
                                    Tambah
                                </button>
                            </div>
                            <div id="custom_sports_badges" class="flex flex-wrap gap-2 pt-1">
                                @foreach($customSports as $customSport)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-900 border border-emerald-300 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $customSport }}
                                        <button type="button" onclick="removeCustomSportBadge(this, '{{ $customSport }}')" class="hover:text-rose-700 text-emerald-700 font-extrabold ml-1 cursor-pointer">&times;</button>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 2. DURASI LATIHAN & EVALUASI TARGET 30 MENIT -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">
                                2. Durasi Latihan Fisik
                            </h3>
                            <p class="text-xs text-slate-500">Rekomendasi Kemenkes RI: Minimal 30 menit setiap hari</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div class="space-y-3">
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
                                    <input type="number" name="durasi" id="durasi_input" min="1" max="600"
                                        value="{{ old('durasi', optional($journal)->durasi) }}"
                                        placeholder="0" required
                                        oninput="evaluateDuration()"
                                        class="w-28 bg-transparent text-3xl sm:text-4xl font-black text-slate-900 focus:outline-none text-center">
                                    <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Menit</span>
                                </div>

                                <!-- Preset tombol cepat -->
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach([15, 30, 45, 60] as $mins)
                                        <button type="button" onclick="setDurasi({{ $mins }})" class="py-2.5 bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 text-xs font-bold rounded-xl transition cursor-pointer shadow-2xs text-center">
                                            {{ $mins }} m
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Live Card Evaluasi Target -->
                            <div id="target_card" class="p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-slate-50 border-slate-200">
                                <div class="flex items-center gap-2">
                                    <span id="target_icon" class="text-xl">⏱️</span>
                                    <p id="target_title" class="text-xs font-black text-slate-800 uppercase tracking-wider">
                                        Status Target Harian
                                    </p>
                                </div>
                                <p id="target_desc" class="text-xs text-slate-600 leading-relaxed font-medium">
                                    Masukkan durasi olahraga untuk melihat pencapaian target aktivitas harian.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 3. INTENSITAS LATIHAN -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">
                                3. Tingkat Intensitas
                            </h3>
                            <p class="text-xs text-slate-500">Pilih intensitas latihan yang sesuai dengan denyut tubuhmu</p>
                        </div>

                        @php
                            $currentIntensitas = old('intensitas', optional($journal)->intensitas ?? 'Sedang');
                            $intensities = [
                                [
                                    'name' => 'Ringan',
                                    'desc' => 'Bernapas normal dan santai, masih bisa mengobrol santai tanpa terengah.',
                                    'example' => 'Jalan santai, peregangan otot, yoga ringan',
                                    'level' => '1 Tingkat Energi'
                                ],
                                [
                                    'name' => 'Sedang',
                                    'desc' => 'Napas agak cepat dan berkeringat, masih bisa berbicara tapi tidak bernyanyi.',
                                    'example' => 'Jalan cepat, senam aerobik, bersepeda rekreasi',
                                    'level' => '2 Tingkat Energi'
                                ],
                                [
                                    'name' => 'Tinggi',
                                    'desc' => 'Napas berat dan berkeringat deras, hanya mampu berbicara beberapa kata singkat.',
                                    'example' => 'Lari cepat (jogging cepat), futsal, renang cepat',
                                    'level' => '3 Tingkat Energi'
                                ],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach($intensities as $item)
                                @php $isCheck = ($currentIntensitas === $item['name']); @endphp
                                <label class="intensity-card p-4 rounded-2xl border cursor-pointer transition flex flex-col justify-between space-y-3 {{ $isCheck ? 'border-emerald-600 bg-emerald-50/50 shadow-sm ring-1 ring-emerald-500' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" name="intensitas" value="{{ $item['name'] }}" {{ $isCheck ? 'checked' : '' }} required onchange="updateIntensitasUI()" class="text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-sm font-extrabold text-slate-900">{{ $item['name'] }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-snug">{{ $item['desc'] }}</p>
                                    <div class="pt-2 border-t border-slate-100 text-[10px] text-slate-500">
                                        <span class="font-bold text-slate-700">Contoh:</span> {{ $item['example'] }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 4. PERASAAN & CATATAN LATIHAN -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">
                                4. Perasaan Setelah Latihan & Catatan
                            </h3>
                            <p class="text-xs text-slate-500">Ungkapkan apa yang kamu rasakan setelah bergerak aktif</p>
                        </div>

                        <input type="hidden" name="perasaan" id="selected_perasaan" value="{{ old('perasaan', optional($journal)->perasaan ?? 'Berenergi & Segar') }}" required>

                        @php
                            $savedPerasaan = old('perasaan', optional($journal)->perasaan ?? 'Berenergi & Segar');
                            $moods = [
                                ['name' => 'Lelah tapi Puas', 'emoji' => '😌'],
                                ['name' => 'Berenergi & Segar', 'emoji' => '⚡'],
                                ['name' => 'Santai & Tenang', 'emoji' => '🌿'],
                                ['name' => 'Bersemangat', 'emoji' => '🔥'],
                                ['name' => 'Sangat Lelah', 'emoji' => '😮‍💨'],
                            ];
                        @endphp

                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                            @foreach($moods as $mood)
                                @php $isMoodActive = ($savedPerasaan === $mood['name']); @endphp
                                <button type="button"
                                    onclick="selectMood('{{ $mood['name'] }}', this)"
                                    class="mood-btn p-3 rounded-2xl border text-center flex flex-col items-center justify-center gap-1.5 transition cursor-pointer {{ $isMoodActive ? 'border-emerald-600 bg-emerald-50 text-emerald-950 font-bold shadow-sm ring-1 ring-emerald-500' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white font-medium hover:bg-slate-50' }}">
                                    <span class="text-2xl">{{ $mood['emoji'] }}</span>
                                    <span class="text-xs text-center leading-tight">{{ $mood['name'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-bold text-slate-800">
                                Catatan Latihan Tambahan (Opsional):
                            </label>
                            <textarea name="catatan" rows="3"
                                class="w-full rounded-2xl border border-slate-300 p-4 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50/50"
                                placeholder="Contoh: Berhasil lari 4 putaran lapangan sekolah bersama teman, kondisi tubuh terasa segar...">{{ old('catatan', optional($journal)->catatan) }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Simpan Bawah -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-8 py-3.5 rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-sm cursor-pointer">
                            <span>💾</span> Simpan Jurnal Olahraga
                        </button>
                    </div>

                </div>

                <!-- KOLOM KANAN (SIDEBAR & REKAP MINGGUAN) -->
                <div class="space-y-6">

                    <!-- KARTU WEEKLY STREAK -->
                    <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-xl space-y-5 border border-slate-800">
                        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">
                                WEEKLY STREAK
                            </span>
                            <span class="text-xs text-slate-400 font-semibold">Minggu Ini</span>
                        </div>

                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-white">{{ $totalDaysActive ?? 0 }}</span>
                                <span class="text-sm font-bold text-slate-300">Hari Aktif</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Konsistensi bergerak setiap hari kunci kebugaran jasmani.</p>
                        </div>

                        <!-- 7 Hari (Senin s/d Minggu) -->
                        <div class="grid grid-cols-7 gap-1 pt-1">
                            @php
                                $daysLabel = [
                                    1 => 'Sen',
                                    2 => 'Sel',
                                    3 => 'Rab',
                                    4 => 'Kam',
                                    5 => 'Jum',
                                    6 => 'Sab',
                                    7 => 'Min',
                                ];
                                $todayNum = \Carbon\Carbon::today()->format('N');
                            @endphp

                            @foreach($daysLabel as $dayIdx => $dayName)
                                @php
                                    $isCompleted = isset($weeklyChartData[$dayIdx]) && $weeklyChartData[$dayIdx]['active'];
                                    $isToday = ($todayNum == $dayIdx);
                                @endphp
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition
                                        {{ $isCompleted
                                            ? 'bg-emerald-400 text-slate-950 shadow-md shadow-emerald-400/20'
                                            : ($isToday
                                                ? 'bg-slate-800 border-2 border-emerald-400 text-white'
                                                : 'bg-slate-800 text-slate-500') }}">
                                        {{ $isCompleted ? '✓' : '' }}
                                    </div>
                                    <span class="text-[10px] font-bold {{ $isToday ? 'text-emerald-400' : 'text-slate-400' }}">
                                        {{ $dayName }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- RINGKASAN STATISTIK MINGGUAN -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <h4 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider">
                            Statistik Olahraga Minggu Ini
                        </h4>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Durasi</p>
                                <p class="text-xl font-black text-slate-900 mt-1">{{ $totalDuration ?? 0 }} <span class="text-xs font-semibold text-slate-500">Mnt</span></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Rata-rata / Sesi</p>
                                <p class="text-xl font-black text-slate-900 mt-1">{{ $averageDuration ?? 0 }} <span class="text-xs font-semibold text-slate-500">Mnt</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- PANDUAN AKTIVITAS SEHAT (KEMENKES RI) -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <h4 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider">
                            Pedoman Olahraga Sehat
                        </h4>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3 bg-emerald-50/70 p-3.5 rounded-2xl border border-emerald-100">
                                <span class="text-lg">💧</span>
                                <div>
                                    <p class="text-xs font-extrabold text-emerald-950">Hidrasi Sebelum & Sesudah</p>
                                    <p class="text-[11px] text-emerald-800 leading-snug mt-0.5">Minum 1 gelas air putih sebelum mulai dan cukupi cairan setelah berolahraga.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-blue-50/70 p-3.5 rounded-2xl border border-blue-100">
                                <span class="text-lg">🤸</span>
                                <div>
                                    <p class="text-xs font-extrabold text-blue-950">Pemanasan & Pendinginan</p>
                                    <p class="text-[11px] text-blue-800 leading-snug mt-0.5">Luangkan 5 menit pemanasan agar otot siap dan 5 menit pendinginan untuk pemulihan.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-amber-50/70 p-3.5 rounded-2xl border border-amber-100">
                                <span class="text-lg">🎯</span>
                                <div>
                                    <p class="text-xs font-extrabold text-amber-950">Target 30 Menit / Hari</p>
                                    <p class="text-[11px] text-amber-800 leading-snug mt-0.5">Mencapai 30 menit sehari menjaga fungsi jantung, stamina belajar, dan daya tahan tubuh.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>

        <!-- FORM HIDDEN UNTUK KOSONGKAN JURNAL -->
        @if($journal)
            <form id="form-reset-olahraga" action="{{ route('student.olahraga.destroy') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif

</div>

<script>
    // 1. Toggle Pilihan Olahraga Preset
    function toggleSport(sportName, element) {
        const container = document.getElementById('hidden_sports_container');
        const customWrapper = document.getElementById('custom_sport_wrapper');

        if (sportName === 'Lainnya') {
            const isSelected = element.classList.contains('border-emerald-600');
            if (!isSelected) {
                customWrapper.classList.remove('hidden');
                element.classList.remove('border-slate-200', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
                element.classList.add('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            } else {
                customWrapper.classList.add('hidden');
                element.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
                element.classList.add('border-slate-200', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
            }
            updateSportsCount();
            return;
        }

        const existingInput = container.querySelector(`input[data-sport="${sportName}"]`);
        if (existingInput) {
            existingInput.remove();
            element.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            element.classList.add('border-slate-200', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        } else {
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'jenis_olahraga[]';
            newInput.value = sportName;
            newInput.setAttribute('data-sport', sportName);
            newInput.className = 'hidden-sport-input';
            container.appendChild(newInput);

            element.classList.remove('border-slate-200', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
            element.classList.add('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
        }
        updateSportsCount();
    }

    // 2. Tambah Jenis Olahraga Kustom
    function addCustomSport() {
        const inputField = document.getElementById('custom_sport_input');
        const sportName = inputField.value.trim();
        if (!sportName) return;

        const container = document.getElementById('hidden_sports_container');
        const badgesContainer = document.getElementById('custom_sports_badges');

        if (container.querySelector(`input[data-sport="${sportName}"]`)) {
            inputField.value = '';
            return;
        }

        const newInput = document.createElement('input');
        newInput.type = 'hidden';
        newInput.name = 'jenis_olahraga[]';
        newInput.value = sportName;
        newInput.setAttribute('data-sport', sportName);
        newInput.className = 'hidden-sport-input';
        container.appendChild(newInput);

        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-900 border border-emerald-300 px-3 py-1 rounded-full text-xs font-bold';
        badge.innerHTML = `${sportName} <button type="button" class="hover:text-rose-700 text-emerald-700 font-extrabold ml-1 cursor-pointer">&times;</button>`;
        badge.querySelector('button').onclick = function() {
            removeCustomSportBadge(badge, sportName);
        };
        badgesContainer.appendChild(badge);

        inputField.value = '';
        updateSportsCount();
    }

    // 3. Hapus Jenis Olahraga Kustom
    function removeCustomSportBadge(badgeEl, sportName) {
        badgeEl.remove();
        const container = document.getElementById('hidden_sports_container');
        const inputEl = container.querySelector(`input[data-sport="${sportName}"]`);
        if (inputEl) inputEl.remove();
        updateSportsCount();
    }

    // 4. Update Counter Olahraga Terpilih
    function updateSportsCount() {
        const inputs = document.querySelectorAll('#hidden_sports_container input[name="jenis_olahraga[]"]');
        const count = inputs.length;
        const countBadge = document.getElementById('selected_sports_count');
        if (countBadge) {
            countBadge.textContent = `${count} Dipilih`;
        }
    }

    // 5. Pilih Durasi Preset
    function setDurasi(minutes) {
        const input = document.getElementById('durasi_input');
        input.value = minutes;
        evaluateDuration();
    }

    // 6. Evaluasi Live Durasi & Target
    function evaluateDuration() {
        const input = document.getElementById('durasi_input');
        const minutes = parseInt(input.value) || 0;
        const card = document.getElementById('target_card');
        const icon = document.getElementById('target_icon');
        const title = document.getElementById('target_title');
        const desc = document.getElementById('target_desc');

        if (minutes <= 0) {
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-slate-50 border-slate-200';
            icon.textContent = '⏱️';
            title.textContent = 'Status Target Harian';
            title.className = 'text-xs font-black text-slate-800 uppercase tracking-wider';
            desc.textContent = 'Masukkan durasi olahraga untuk melihat pencapaian target aktivitas harian.';
            desc.className = 'text-xs text-slate-600 leading-relaxed font-medium';
        } else if (minutes < 30) {
            const sisa = 30 - minutes;
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-amber-50 border-amber-200';
            icon.textContent = '⏳';
            title.textContent = `Perlu ${sisa} Menit Lagi`;
            title.className = 'text-xs font-black text-amber-900 uppercase tracking-wider';
            desc.textContent = `Kamu berolahraga ${minutes} menit. Tambah ${sisa} menit lagi agar memenuhi standar harian 30 menit dari Kemenkes RI.`;
            desc.className = 'text-xs text-amber-800 leading-relaxed font-medium';
        } else {
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-emerald-50 border-emerald-200';
            icon.textContent = '🏆';
            title.textContent = 'Target Tercapai!';
            title.className = 'text-xs font-black text-emerald-950 uppercase tracking-wider';
            desc.textContent = `Luar biasa! Durasi ${minutes} menit telah memenuhi bahkan melampaui target minimal 30 menit kebugaran fisik harian.`;
            desc.className = 'text-xs text-emerald-900 leading-relaxed font-medium';
        }
    }

    // 7. Update UI Intensitas
    function updateIntensitasUI() {
        document.querySelectorAll('input[name="intensitas"]').forEach(radio => {
            const card = radio.closest('.intensity-card');
            if (radio.checked) {
                card.classList.remove('border-slate-200', 'hover:border-slate-300', 'bg-white');
                card.classList.add('border-emerald-600', 'bg-emerald-50/50', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            } else {
                card.classList.remove('border-emerald-600', 'bg-emerald-50/50', 'shadow-sm', 'ring-1', 'ring-emerald-500');
                card.classList.add('border-slate-200', 'hover:border-slate-300', 'bg-white');
            }
        });
    }

    // 8. Pilih Mood/Perasaan
    function selectMood(moodName, element) {
        document.getElementById('selected_perasaan').value = moodName;
        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            btn.classList.add('border-slate-200', 'hover:border-slate-300', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        });
        element.classList.remove('border-slate-200', 'hover:border-slate-300', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        element.classList.add('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
    }

    // 9. Konfirmasi Kosongkan Jurnal
    function confirmReset() {
        if (confirm('Apakah kamu yakin ingin mengosongkan jurnal olahraga hari ini? Data di dashboard dan catatan akan direset.')) {
            document.getElementById('form-reset-olahraga').submit();
        }
    }

    // 10. Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        updateSportsCount();
        updateIntensitasUI();
        evaluateDuration();
    });
</script>
@endsection
