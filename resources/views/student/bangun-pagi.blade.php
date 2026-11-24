@extends('layouts.student')

@section('title', 'Bangun Pagi - Jurnal Harian 7 Kebiasaan Anak Indonesia')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0"
     id="time-sync-container"
     data-server-time="{{ $serverTime }}"
     data-server-timestamp="{{ $serverTimestamp }}">

    <!-- Notifikasi Flash Message -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 sm:px-5 py-3.5 rounded-2xl shadow-sm text-xs sm:text-sm font-semibold flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="text-lg shrink-0">✅</span>
                <span class="break-words">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-lg leading-none shrink-0 p-1">&times;</button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 sm:px-5 py-3.5 rounded-2xl shadow-sm text-xs sm:text-sm font-semibold space-y-1">
            <div class="flex items-center gap-2 text-rose-700 font-bold">
                <span>⚠️</span> Mohon periksa isian formulir:
            </div>
            <ul class="list-disc list-inside text-xs text-rose-800 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- BANNER PERINGATAN JIKA TERDETEKSI MANIPULASI JAM HP -->
    <div id="tamper_warning_banner" class="hidden bg-amber-500/10 border-2 border-amber-500/30 p-4 sm:p-5 rounded-2xl sm:rounded-3xl text-amber-950 space-y-1.5 shadow-sm">
        <div class="flex items-center gap-2.5">
            <span class="text-xl sm:text-2xl shrink-0">⚠️</span>
            <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-amber-900">
                Peringatan: Perbedaan Jam Perangkat Terdeteksi
            </h4>
        </div>
        <p id="tamper_warning_text" class="text-xs text-amber-900 leading-relaxed font-medium">
            Jam di pengaturan perangkat Anda tidak cocok dengan jam resmi server. Sistem otomatis mengunci waktu ke Jam Server Resmi (WIB) untuk mencegah manipulasi dan menjaga kejujuran data.
        </p>
    </div>

    <!-- HEADER BANNER NAVY MODERN (RESPONSIF MOBILE / DESKTOP) -->
    <div class="bg-slate-900 text-white p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 sm:px-3 py-1 bg-amber-400/20 text-amber-300 text-[10px] sm:text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-amber-400/30">
                        KEBIASAAN 1
                    </span>
                    @if($journal)
                        @if($journal->is_manipulated)
                            <span class="px-2.5 sm:px-3 py-1 bg-rose-500 text-white text-[10px] sm:text-[11px] font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                                ⚠️ Terdeteksi Manipulasi Jam HP
                            </span>
                        @elseif(str_contains(strtolower($journal->status_waktu), 'kesiangan'))
                            <span class="px-2.5 sm:px-3 py-1 bg-amber-400 text-slate-950 text-[10px] sm:text-[11px] font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                                Tercatat: Kesiangan
                            </span>
                        @else
                            <span class="px-2.5 sm:px-3 py-1 bg-emerald-500 text-slate-950 text-[10px] sm:text-[11px] font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                                Tercatat: Tepat Waktu
                            </span>
                        @endif
                    @else
                        <span class="px-2.5 sm:px-3 py-1 bg-slate-800 text-slate-300 text-[10px] sm:text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-slate-700">
                            Belum Mengisi Hari Ini
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Bangun Pagi
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm font-medium leading-relaxed">
                    Sistem divalidasi langsung oleh server resmi WIB. Batas waktu bangun tepat pukul 05:30 WIB. Jika mengisi lewat batas, tetap dapat disimpan secara normal.
                </p>
            </div>

            <!-- Indikator Tanggal & Tombol Aksi (Reflow responsif untuk HP) -->
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

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    @if($journal)
                        <button type="button" onclick="confirmReset()" class="flex-1 sm:flex-none min-h-[44px] bg-rose-500/10 hover:bg-rose-500 text-rose-300 hover:text-white border border-rose-500/30 font-bold px-3.5 py-2.5 rounded-xl sm:rounded-2xl transition text-xs flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>🗑️</span> Kosongkan
                        </button>
                    @endif

                    <button type="button" onclick="document.getElementById('form-bangun-pagi').submit()" class="flex-1 sm:flex-none min-h-[44px] bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-black px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                        <span>💾</span> Simpan Jurnal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM UTAMA -->
    <form id="form-bangun-pagi" action="{{ route('student.bangun-pagi.update') }}" method="POST">
        @csrf

        <!-- Hidden input waktu dan status -->
        <input type="hidden" name="waktu_bangun" id="waktu_bangun_input" value="{{ old('waktu_bangun', optional($journal)->waktu_bangun ?? '') }}">
        <input type="hidden" name="device_time" id="device_time_input" value="{{ old('device_time', optional($journal)->device_time ?? '') }}">
        <input type="hidden" name="status_waktu" id="status_waktu_input" value="{{ old('status_waktu', optional($journal)->status_waktu ?? '') }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">

            <!-- KOLOM KIRI & TENGAH (FORMULIR INPUT) -->
            <div class="lg:col-span-2 space-y-5 sm:space-y-6">

                <!-- 1. WAKTU VALID TERVALIDASI SERVER -->
                <div class="bg-white p-4 sm:p-6 lg:p-7 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm space-y-4 sm:space-y-5">
                    <div class="border-b border-slate-100 pb-3 sm:pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-sm sm:text-base lg:text-lg font-black text-slate-900">
                                1. Waktu Resmi & Validasi Anti-Kecurangan
                            </h3>
                            <p class="text-[11px] sm:text-xs text-slate-500">
                                Jam divalidasi langsung oleh server resmi untuk mencegah pengubahan jam manual pada HP/laptop.
                            </p>
                        </div>
                        <span id="sync_status_badge" class="text-[10px] sm:text-[11px] font-bold px-3 py-1 bg-emerald-100 text-emerald-900 rounded-full w-fit">
                            Terverifikasi Server
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 items-stretch">
                        <!-- Display Jam Server Resmi -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl sm:rounded-2xl p-4 sm:p-5 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-slate-500">
                                        Waktu Resmi Pengisian
                                    </p>
                                    <span id="device_clock_sub" class="text-[9px] sm:text-[10px] font-bold text-slate-400">
                                        HP: --:--
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-2 mt-1">
                                    <span id="live_clock_display" class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                        {{ $serverTime }}
                                    </span>
                                    <span class="text-xs font-extrabold text-slate-500 uppercase">WIB</span>
                                </div>
                            </div>
                            <div class="text-[11px] text-slate-600 flex items-center gap-1.5 font-medium">
                                <span id="lock_icon">🔒</span>
                                <span id="lock_text">Terkunci ke waktu server WIB resmi. Mengubah jam HP tidak akan memanipulasi penilaian.</span>
                            </div>
                        </div>

                        <!-- Live Card Evaluasi Ketepatan Waktu -->
                        <div id="status_card" class="p-4 sm:p-5 rounded-xl sm:rounded-2xl border transition flex flex-col justify-between space-y-2 bg-slate-50 border-slate-200">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span id="status_icon" class="text-xl sm:text-2xl shrink-0">⏳</span>
                                    <p id="status_title" class="text-xs font-black uppercase tracking-wider text-slate-800">
                                        Mengevaluasi Waktu...
                                    </p>
                                </div>
                                <p id="status_desc" class="text-xs text-slate-600 leading-relaxed font-medium">
                                    Menyesuaikan dengan jam resmi server.
                                </p>
                            </div>
                            <div class="pt-2 border-t border-slate-200/60 text-[10px] sm:text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                                <span>ℹ️</span>
                                <span>Batas waktu bangun tepat: <strong>05:30 WIB</strong>.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. RUTINITAS PAGI POSITIF (CHECKLIST) -->
                <div class="bg-white p-4 sm:p-6 lg:p-7 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm space-y-4 sm:space-y-5">
                    <div class="border-b border-slate-100 pb-3 sm:pb-4">
                        <h3 class="text-sm sm:text-base lg:text-lg font-black text-slate-900">
                            2. Rutinitas Pagi Positif
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-500">Centang kegiatan positif yang sudah kamu lakukan pagi ini</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3.5">
                        <!-- 1. Merapikan Tempat Tidur -->
                        <label class="routine-card min-h-[52px] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 hover:border-slate-300 active:scale-[0.99] bg-white cursor-pointer transition flex items-start gap-3">
                            <input type="checkbox" name="merapikan_tempat_tidur" value="1"
                                {{ old('merapikan_tempat_tidur', optional($journal)->merapikan_tempat_tidur) ? 'checked' : '' }}
                                onchange="updateRoutineUI(this)"
                                class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5 shrink-0">
                            <div class="space-y-0.5 min-w-0">
                                <p class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">Merapikan Tempat Tidur</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500 leading-snug">Menata kasur dan bantal melatih kedisiplinan sejak awal hari.</p>
                            </div>
                        </label>

                        <!-- 2. Minum Air Putih -->
                        <label class="routine-card min-h-[52px] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 hover:border-slate-300 active:scale-[0.99] bg-white cursor-pointer transition flex items-start gap-3">
                            <input type="checkbox" name="minum_air_putih" value="1"
                                {{ old('minum_air_putih', optional($journal)->minum_air_putih) ? 'checked' : '' }}
                                onchange="updateRoutineUI(this)"
                                class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5 shrink-0">
                            <div class="space-y-0.5 min-w-0">
                                <p class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">Minum Segelas Air Putih</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500 leading-snug">Mengaktifkan metabolisme organ dan mengganti cairan semalaman.</p>
                            </div>
                        </label>

                        <!-- 3. Mandi Pagi -->
                        <label class="routine-card min-h-[52px] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 hover:border-slate-300 active:scale-[0.99] bg-white cursor-pointer transition flex items-start gap-3">
                            <input type="checkbox" name="mandi_pagi" value="1"
                                {{ old('mandi_pagi', optional($journal)->mandi_pagi) ? 'checked' : '' }}
                                onchange="updateRoutineUI(this)"
                                class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5 shrink-0">
                            <div class="space-y-0.5 min-w-0">
                                <p class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">Mandi Pagi & Bersuci</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500 leading-snug">Menyegarkan tubuh, melancarkan peredaran darah, dan higienis.</p>
                            </div>
                        </label>

                        <!-- 4. Persiapan Sekolah -->
                        <label class="routine-card min-h-[52px] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 hover:border-slate-300 active:scale-[0.99] bg-white cursor-pointer transition flex items-start gap-3">
                            <input type="checkbox" name="persiapan_sekolah" value="1"
                                {{ old('persiapan_sekolah', optional($journal)->persiapan_sekolah) ? 'checked' : '' }}
                                onchange="updateRoutineUI(this)"
                                class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5 shrink-0">
                            <div class="space-y-0.5 min-w-0">
                                <p class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">Persiapan Peralatan Sekolah</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500 leading-snug">Mengecek seragam rapi, buku pelajaran, dan perlengkapan tas.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. MOOD PAGI & REFLEKSI -->
                <div class="bg-white p-4 sm:p-6 lg:p-7 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm space-y-4 sm:space-y-5">
                    <div class="border-b border-slate-100 pb-3 sm:pb-4">
                        <h3 class="text-sm sm:text-base lg:text-lg font-black text-slate-900">
                            3. Mood Pagi Ini & Refleksi Singkat
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-500">Pilih perasaanmu saat menyambut hari baru dan tuliskan afirmasi positif</p>
                    </div>

                    <input type="hidden" name="mood" id="selected_mood" value="{{ old('mood', optional($journal)->mood ?? 'BERENERGI') }}" required>

                    @php
                        $savedMood = old('mood', optional($journal)->mood ?? 'BERENERGI');
                        $moodList = [
                            ['id' => 'BERENERGI', 'name' => 'Berenergi', 'emoji' => '⚡'],
                            ['id' => 'SENANG', 'name' => 'Senang', 'emoji' => '😊'],
                            ['id' => 'TENANG', 'name' => 'Tenang', 'emoji' => '🌿'],
                            ['id' => 'SEMANGAT', 'name' => 'Semangat', 'emoji' => '🔥'],
                            ['id' => 'MENGANTUK', 'name' => 'Mengantuk', 'emoji' => '😴'],
                        ];
                    @endphp

                    <!-- Responsive Mood Grid: 2 kolom di mobile kecil, 3 di tablet kecil, 5 di desktop -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2 sm:gap-2.5">
                        @foreach($moodList as $m)
                            @php $isMoodSelected = ($savedMood === $m['id']); @endphp
                            <button type="button"
                                onclick="selectMood('{{ $m['id'] }}', this)"
                                class="mood-btn min-h-[56px] p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 transition cursor-pointer active:scale-[0.97] {{ $isMoodSelected ? 'border-emerald-600 bg-emerald-50 text-emerald-950 font-bold shadow-sm ring-1 ring-emerald-500' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white font-medium hover:bg-slate-50' }}">
                                <span class="text-xl sm:text-2xl">{{ $m['emoji'] }}</span>
                                <span class="text-[11px] sm:text-xs text-center leading-tight">{{ $m['name'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="space-y-1.5 pt-1 sm:pt-2">
                        <label class="block text-xs font-bold text-slate-800">
                            Refleksi & Niat Positif Hari Ini:
                        </label>
                        <textarea name="refleksi" rows="3"
                            class="w-full rounded-xl sm:rounded-2xl border border-slate-300 p-3.5 sm:p-4 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50/50 min-h-[88px]"
                            placeholder="Contoh: Hari ini saya bangun lebih segar, siap mengikuti pelajaran dengan antusias dan ingin berbuat baik kepada sesama...">{{ old('refleksi', optional($journal)->refleksi) }}</textarea>
                    </div>
                </div>

                <!-- Tombol Simpan Bawah (Full-width di HP, auto di Desktop) -->
                <div class="flex items-center justify-end pt-1">
                    <button type="submit" class="w-full sm:w-auto min-h-[48px] bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-black px-8 py-3.5 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                        <span>💾</span> Simpan Jurnal Bangun Pagi
                    </button>
                </div>

            </div>

            <!-- KOLOM KANAN (SIDEBAR & REKAP MINGGUAN) -->
            <div class="space-y-5 sm:space-y-6">

                <!-- KARTU WEEKLY STREAK -->
                <div class="bg-slate-900 text-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl space-y-4 sm:space-y-5 border border-slate-800">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-400">
                            WEEKLY STREAK
                        </span>
                        <span class="text-xs text-slate-400 font-semibold">Minggu Ini</span>
                    </div>

                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-white">{{ $totalDaysActive ?? 0 }}</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-300">Hari Disiplin</span>
                        </div>
                        <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Bangun pagi konsisten membentuk ritme biologis tubuh yang prima.</p>
                    </div>

                    <!-- 7 Hari (Senin s/d Minggu) responsif tanpa horizontal scroll -->
                    <div class="grid grid-cols-7 gap-1 sm:gap-1.5 pt-1">
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
                            <div class="flex flex-col items-center gap-1 sm:gap-1.5">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs font-black transition mx-auto
                                    {{ $isCompleted
                                        ? 'bg-amber-400 text-slate-950 shadow-md shadow-amber-400/20'
                                        : ($isToday
                                            ? 'bg-slate-800 border-2 border-amber-400 text-white'
                                            : 'bg-slate-800 text-slate-500') }}">
                                    {{ $isCompleted ? '✓' : '' }}
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-bold {{ $isToday ? 'text-amber-400' : 'text-slate-400' }}">
                                    {{ $dayName }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RINGKASAN STATISTIK MINGGUAN -->
                <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm space-y-3 sm:space-y-4">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 border-b border-slate-100 pb-2.5 sm:pb-3 uppercase tracking-wider">
                        Statistik Kedisiplinan Pagi
                    </h4>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div class="bg-slate-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80">
                            <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider">Hari Terisi</p>
                            <p class="text-lg sm:text-xl font-black text-slate-900 mt-0.5 sm:mt-1">{{ $totalDaysActive ?? 0 }} <span class="text-xs font-semibold text-slate-500">Hari</span></p>
                        </div>
                        <div class="bg-slate-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80">
                            <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tepat Waktu</p>
                            <p class="text-lg sm:text-xl font-black text-emerald-600 mt-0.5 sm:mt-1">{{ $tepatWaktuCount ?? 0 }} <span class="text-xs font-semibold text-slate-500">Hari</span></p>
                        </div>
                    </div>
                </div>

                <!-- MANFAAT BANGUN PAGI & KEAMANAN -->
                <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm space-y-3 sm:space-y-4">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 border-b border-slate-100 pb-2.5 sm:pb-3 uppercase tracking-wider">
                        Keamanan & Validasi Waktu
                    </h4>

                    <div class="space-y-2.5 sm:space-y-3">
                        <div class="flex items-start gap-2.5 sm:gap-3 bg-emerald-50/70 p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-emerald-100">
                            <span class="text-base sm:text-lg shrink-0">🛡️</span>
                            <div>
                                <p class="text-xs font-extrabold text-emerald-950">Validasi Server Sentral</p>
                                <p class="text-[10px] sm:text-[11px] text-emerald-800 leading-snug mt-0.5">Penilaian waktu dikunci langsung oleh jam server resmi (WIB) sehingga tidak dapat dimanipulasi melalui pengaturan jam HP.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 sm:gap-3 bg-amber-50/70 p-3.5 rounded-xl sm:rounded-2xl border border-amber-100">
                            <span class="text-base sm:text-lg shrink-0">⏰</span>
                            <div>
                                <p class="text-xs font-extrabold text-amber-950">Batas Waktu Tepat (05:30 WIB)</p>
                                <p class="text-[10px] sm:text-[11px] text-amber-800 leading-snug mt-0.5">Mengisi lewat dari pukul 05:30 WIB otomatis terhitung kesiangan namun jurnal tetap dapat dikirim normal.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

    <!-- FORM HIDDEN UNTUK KOSONGKAN JURNAL -->
    @if($journal)
        <form id="form-reset-bangun" action="{{ route('student.bangun-pagi.destroy') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

</div>

<script>
    // 1. Inisialisasi Deteksi Jam Device vs Jam Server Resmi
    function initAntiCheatClock() {
        const container = document.getElementById('time-sync-container');
        const serverTimeStr = container.dataset.serverTime || '04:30';
        const serverTimestampSec = parseInt(container.dataset.serverTimestamp) || Math.floor(Date.now() / 1000);

        const waktuInput = document.getElementById('waktu_bangun_input');
        const deviceTimeInput = document.getElementById('device_time_input');
        const statusInput = document.getElementById('status_waktu_input');

        const clockDisplay = document.getElementById('live_clock_display');
        const deviceSub = document.getElementById('device_clock_sub');
        const syncBadge = document.getElementById('sync_status_badge');
        const lockIcon = document.getElementById('lock_icon');
        const lockText = document.getElementById('lock_text');

        const card = document.getElementById('status_card');
        const icon = document.getElementById('status_icon');
        const title = document.getElementById('status_title');
        const desc = document.getElementById('status_desc');

        const tamperBanner = document.getElementById('tamper_warning_banner');
        const tamperText = document.getElementById('tamper_warning_text');

        // Ambil waktu dari device HP/laptop pengguna
        const nowDevice = new Date();
        const devH = String(nowDevice.getHours()).padStart(2, '0');
        const devM = String(nowDevice.getMinutes()).padStart(2, '0');
        const deviceClock = `${devH}:${devM}`;
        deviceTimeInput.value = deviceClock;
        deviceSub.textContent = `HP: ${deviceClock}`;

        // Hitung selisih waktu antara Jam Device dan Jam Server Resmi
        const deviceEpochMs = nowDevice.getTime();
        const serverEpochMs = serverTimestampSec * 1000;
        const diffMinutes = Math.abs(deviceEpochMs - serverEpochMs) / (1000 * 60);

        // Ambang batas toleransi: 10 menit
        const isTampered = diffMinutes > 10;

        // Jam yang digunakan untuk penilaian adalah Jam Server Resmi (WIB)
        const activeClock = serverTimeStr;
        waktuInput.value = activeClock;
        clockDisplay.textContent = activeClock;

        // Evaluasi apakah jam server melewati batas 05:30 WIB
        const [servH, servM] = activeClock.split(':').map(Number);
        const serverTotalMinutes = (servH * 60) + servM;
        const cutoffMinutes = (5 * 60) + 30; // 05:30 WIB = 330 menit
        const isLate = serverTotalMinutes > cutoffMinutes;

        if (isTampered) {
            // TERDETEKSI MANIPULASI JAM HP!
            tamperBanner.classList.remove('hidden');
            tamperText.textContent = `Jam di pengaturan HP Anda (${deviceClock} WIB) berselisih ${Math.round(diffMinutes)} menit dari Jam Server Resmi (${serverTimeStr} WIB). Sistem otomatis mengunci waktu ke Jam Server Resmi (${serverTimeStr} WIB) agar penilaian tetap sah dan adil.`;

            syncBadge.className = 'text-[10px] sm:text-[11px] font-bold px-3 py-1 bg-rose-100 text-rose-950 border border-rose-300 rounded-full w-fit';
            syncBadge.textContent = '⚠️ Jam HP Tidak Cocok Server';

            lockIcon.textContent = '⚠️';
            lockText.textContent = `Jam HP (${deviceClock}) dimanipulasi / tidak cocok. Sistem mengunci ke Jam Server Resmi (${serverTimeStr} WIB).`;

            if (isLate) {
                statusInput.value = 'Kesiangan';
                card.className = 'p-4 sm:p-5 rounded-xl sm:rounded-2xl border transition flex flex-col justify-between space-y-2 bg-rose-50 border-rose-200';
                icon.textContent = '⏰';
                title.textContent = 'Kesiangan (Manipulasi Jam Terdeteksi)';
                title.className = 'text-xs font-black uppercase tracking-wider text-rose-950';
                desc.textContent = `Waktu server resmi menunjukkan pukul ${serverTimeStr} WIB (melewati 05:30 WIB). Terhitung kesiangan. Jurnal tetap dapat disimpan.`;
                desc.className = 'text-xs text-rose-900 leading-relaxed font-medium';
            } else {
                statusInput.value = 'Tepat Waktu';
                card.className = 'p-4 sm:p-5 rounded-xl sm:rounded-2xl border transition flex flex-col justify-between space-y-2 bg-amber-50 border-amber-200';
                icon.textContent = '🛡️';
                title.textContent = 'Tepat Waktu (Waktu Server Resmi)';
                title.className = 'text-xs font-black uppercase tracking-wider text-amber-950';
                desc.textContent = `Jam server resmi menunjukkan pukul ${serverTimeStr} WIB (sebelum 05:30 WIB). Terhitung tepat waktu. Catatan: Jam HP Anda (${deviceClock}) tidak cocok dengan server.`;
                desc.className = 'text-xs text-amber-900 leading-relaxed font-medium';
            }
        } else {
            // JAM DEVICE COCOK DENGAN SERVER
            tamperBanner.classList.add('hidden');
            syncBadge.className = 'text-[10px] sm:text-[11px] font-bold px-3 py-1 bg-emerald-100 text-emerald-900 rounded-full w-fit';
            syncBadge.textContent = 'Terverifikasi Server';

            if (isLate) {
                statusInput.value = 'Kesiangan';
                card.className = 'p-4 sm:p-5 rounded-xl sm:rounded-2xl border transition flex flex-col justify-between space-y-2 bg-amber-50 border-amber-200';
                icon.textContent = '⏰';
                title.textContent = 'Kesiangan (> 05:30 WIB)';
                title.className = 'text-xs font-black uppercase tracking-wider text-amber-950';
                desc.textContent = `Waktu pengisian resmi pukul ${serverTimeStr} WIB (melewati batas 05:30 WIB). Terhitung kesiangan, tetapi jurnal tetap dapat disimpan normal.`;
                desc.className = 'text-xs text-amber-900 leading-relaxed font-medium';
            } else {
                statusInput.value = 'Tepat Waktu';
                card.className = 'p-4 sm:p-5 rounded-xl sm:rounded-2xl border transition flex flex-col justify-between space-y-2 bg-emerald-50 border-emerald-200';
                icon.textContent = '🏆';
                title.textContent = 'Tepat Waktu (<= 05:30 WIB)';
                title.className = 'text-xs font-black uppercase tracking-wider text-emerald-950';
                desc.textContent = `Luar biasa! Pengisian pukul ${serverTimeStr} WIB (sebelum 05:30 WIB). Terus pertahankan kedisiplinan bangun pagi!`;
                desc.className = 'text-xs text-emerald-900 leading-relaxed font-medium';
            }
        }
    }

    // 2. Highlight Card Rutinitas yang dicentang
    function updateRoutineUI(checkbox) {
        const card = checkbox.closest('.routine-card');
        if (checkbox.checked) {
            card.classList.remove('border-slate-200', 'bg-white');
            card.classList.add('border-emerald-600', 'bg-emerald-50/40', 'shadow-sm', 'ring-1', 'ring-emerald-500');
        } else {
            card.classList.remove('border-emerald-600', 'bg-emerald-50/40', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            card.classList.add('border-slate-200', 'bg-white');
        }
    }

    // 3. Pilih Mood
    function selectMood(moodId, element) {
        document.getElementById('selected_mood').value = moodId;
        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            btn.classList.add('border-slate-200', 'hover:border-slate-300', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        });
        element.classList.remove('border-slate-200', 'hover:border-slate-300', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        element.classList.add('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
    }

    // 4. Konfirmasi Kosongkan Jurnal
    function confirmReset() {
        if (confirm('Apakah kamu yakin ingin mengosongkan jurnal bangun pagi hari ini? Data di dashboard dan catatan akan direset.')) {
            document.getElementById('form-reset-bangun').submit();
        }
    }

    // 5. Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        initAntiCheatClock();
        document.querySelectorAll('.routine-card input[type="checkbox"]').forEach(cb => {
            updateRoutineUI(cb);
        });
    });
</script>
@endsection
