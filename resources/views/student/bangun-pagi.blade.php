@extends('layouts.student')

@section('title', 'Bangun Pagi - Jurnal Harian 7 Kebiasaan Anak Indonesia')

@section('content')
<div class="flex min-h-screen bg-slate-50 w-full">
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 overflow-y-auto max-w-7xl mx-auto">

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
                    <span>⚠️</span> Mohon periksa isian formulir:
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
                        <span class="px-3 py-1 bg-amber-400/20 text-amber-300 text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-amber-400/30">
                            KEBIASAAN 1
                        </span>
                        @if($journal)
                            <span class="px-3 py-1 bg-emerald-500 text-slate-950 text-[11px] font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                                Terisi: {{ $journal->status_waktu }} ({{ $journal->waktu_bangun }} WIB)
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-slate-700">
                                Belum Mengisi Hari Ini
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                        Bangun Pagi
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm font-medium leading-relaxed">
                        Awal hari yang teratur dan disiplin membuka energi positif, kejernihan berpikir, serta kesiapan optimal untuk belajar di sekolah.
                    </p>
                </div>

                <!-- Indikator Tanggal & Tombol Aksi -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="bg-slate-800/90 border border-slate-700 px-4 py-3 rounded-2xl flex items-center gap-3">
                        <span class="text-xl">📅</span>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">TANGGAL HARI INI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>

                    @if($journal)
                        <button type="button" onclick="confirmReset()" class="bg-rose-500/10 hover:bg-rose-500 text-rose-300 hover:text-white border border-rose-500/30 font-bold px-4 py-3 rounded-2xl transition text-xs flex items-center gap-2 cursor-pointer">
                            <span>🗑️</span> Kosongkan
                        </button>
                    @endif

                    <button type="button" onclick="document.getElementById('form-bangun-pagi').submit()" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-6 py-3.5 rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                        <span>💾</span> Simpan Jurnal
                    </button>
                </div>
            </div>
        </div>

        <!-- FORM UTAMA -->
        <form id="form-bangun-pagi" action="{{ route('student.bangun-pagi.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- KOLOM KIRI & TENGAH (FORMULIR INPUT) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- 1. WAKTU BANGUN & EVALUASI OTOMATIS -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">
                                1. Waktu Bangun Pagi
                            </h3>
                            <p class="text-xs text-slate-500">Pilih jam bangunmu pagi ini. Sistem akan mengevaluasi ketepatan waktu secara otomatis.</p>
                        </div>

                        <input type="hidden" name="status_waktu" id="status_waktu_input" value="{{ old('status_waktu', optional($journal)->status_waktu ?? 'Tepat Waktu') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div class="space-y-3">
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
                                    <input type="time" name="waktu_bangun" id="waktu_bangun_input"
                                        value="{{ old('waktu_bangun', $defaultTime) }}" required
                                        onchange="evaluateWakeTime()"
                                        class="bg-transparent text-2xl sm:text-3xl font-black text-slate-900 focus:outline-none cursor-pointer">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">WIB</span>
                                </div>

                                <!-- Preset Cepat -->
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach(['04:00', '04:30', '05:00', '05:30'] as $preset)
                                        <button type="button" onclick="setTimePreset('{{ $preset }}')" class="py-2.5 bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 text-xs font-bold rounded-xl transition cursor-pointer shadow-2xs text-center">
                                            {{ $preset }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Live Card Evaluasi Ketepatan Waktu -->
                            <div id="status_card" class="p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-emerald-50 border-emerald-200">
                                <div class="flex items-center gap-2">
                                    <span id="status_icon" class="text-xl">🏆</span>
                                    <p id="status_title" class="text-xs font-black text-emerald-950 uppercase tracking-wider">
                                        Tepat Waktu (Sangat Baik)
                                    </p>
                                </div>
                                <p id="status_desc" class="text-xs text-emerald-900 leading-relaxed font-medium">
                                    Hebat! Bangun sebelum fajar memberikan energi terbaik dan ketenangan mengawali hari.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. RUTINITAS PAGI POSITIF (CHECKLIST) -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">
                                2. Rutinitas Pagi Positif
                            </h3>
                            <p class="text-xs text-slate-500">Centang kegiatan positif yang sudah kamu lakukan setelah bangun pagi</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- 1. Merapikan Tempat Tidur -->
                            <label class="routine-card p-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition flex items-start gap-3.5">
                                <input type="checkbox" name="merapikan_tempat_tidur" value="1"
                                    {{ old('merapikan_tempat_tidur', optional($journal)->merapikan_tempat_tidur) ? 'checked' : '' }}
                                    onchange="updateRoutineUI(this)"
                                    class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5">
                                <div class="space-y-0.5">
                                    <p class="text-xs sm:text-sm font-extrabold text-slate-900">Merapikan Tempat Tidur</p>
                                    <p class="text-[11px] text-slate-500 leading-snug">Menata kasur dan bantal melatih kedisiplinan sejak awal hari.</p>
                                </div>
                            </label>

                            <!-- 2. Minum Air Putih -->
                            <label class="routine-card p-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition flex items-start gap-3.5">
                                <input type="checkbox" name="minum_air_putih" value="1"
                                    {{ old('minum_air_putih', optional($journal)->minum_air_putih) ? 'checked' : '' }}
                                    onchange="updateRoutineUI(this)"
                                    class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5">
                                <div class="space-y-0.5">
                                    <p class="text-xs sm:text-sm font-extrabold text-slate-900">Minum Segelas Air Putih</p>
                                    <p class="text-[11px] text-slate-500 leading-snug">Mengaktifkan metabolisme organ dan mengganti cairan semalaman.</p>
                                </div>
                            </label>

                            <!-- 3. Mandi Pagi -->
                            <label class="routine-card p-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition flex items-start gap-3.5">
                                <input type="checkbox" name="mandi_pagi" value="1"
                                    {{ old('mandi_pagi', optional($journal)->mandi_pagi) ? 'checked' : '' }}
                                    onchange="updateRoutineUI(this)"
                                    class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5">
                                <div class="space-y-0.5">
                                    <p class="text-xs sm:text-sm font-extrabold text-slate-900">Mandi Pagi & Bersuci</p>
                                    <p class="text-[11px] text-slate-500 leading-snug">Menyegarkan tubuh, melancarkan peredaran darah, dan higienis.</p>
                                </div>
                            </label>

                            <!-- 4. Persiapan Sekolah -->
                            <label class="routine-card p-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition flex items-start gap-3.5">
                                <input type="checkbox" name="persiapan_sekolah" value="1"
                                    {{ old('persiapan_sekolah', optional($journal)->persiapan_sekolah) ? 'checked' : '' }}
                                    onchange="updateRoutineUI(this)"
                                    class="w-5 h-5 rounded-lg text-emerald-600 border-slate-300 focus:ring-emerald-500 mt-0.5">
                                <div class="space-y-0.5">
                                    <p class="text-xs sm:text-sm font-extrabold text-slate-900">Persiapan Peralatan Sekolah</p>
                                    <p class="text-[11px] text-slate-500 leading-snug">Mengecek seragam rapi, buku pelajaran, dan perlengkapan tas.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 3. MOOD PAGI & REFLEKSI -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">
                                3. Mood Pagi Ini & Refleksi Singkat
                            </h3>
                            <p class="text-xs text-slate-500">Pilih perasaanmu saat menyambut hari baru dan tuliskan afirmasi positif</p>
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

                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                            @foreach($moodList as $m)
                                @php $isMoodSelected = ($savedMood === $m['id']); @endphp
                                <button type="button"
                                    onclick="selectMood('{{ $m['id'] }}', this)"
                                    class="mood-btn p-3 rounded-2xl border text-center flex flex-col items-center justify-center gap-1.5 transition cursor-pointer {{ $isMoodSelected ? 'border-emerald-600 bg-emerald-50 text-emerald-950 font-bold shadow-sm ring-1 ring-emerald-500' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white font-medium hover:bg-slate-50' }}">
                                    <span class="text-2xl">{{ $m['emoji'] }}</span>
                                    <span class="text-xs text-center leading-tight">{{ $m['name'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-bold text-slate-800">
                                Refleksi & Niat Positif Hari Ini:
                            </label>
                            <textarea name="refleksi" rows="3"
                                class="w-full rounded-2xl border border-slate-300 p-4 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50/50"
                                placeholder="Contoh: Hari ini saya bangun lebih segar, siap mengikuti pelajaran matematika dengan antusias dan ingin berbuat baik kepada sesama...">{{ old('refleksi', optional($journal)->refleksi) }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Simpan Bawah -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-8 py-3.5 rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-sm cursor-pointer">
                            <span>💾</span> Simpan Jurnal Bangun Pagi
                        </button>
                    </div>

                </div>

                <!-- KOLOM KANAN (SIDEBAR & REKAP MINGGUAN) -->
                <div class="space-y-6">

                    <!-- KARTU WEEKLY STREAK -->
                    <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-xl space-y-5 border border-slate-800">
                        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                            <span class="text-[10px] font-black uppercase tracking-widest text-amber-400">
                                WEEKLY STREAK
                            </span>
                            <span class="text-xs text-slate-400 font-semibold">Minggu Ini</span>
                        </div>

                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-white">{{ $totalDaysActive ?? 0 }}</span>
                                <span class="text-sm font-bold text-slate-300">Hari Disiplin</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Bangun pagi konsisten membentuk ritme biologis tubuh yang prima.</p>
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
                                            ? 'bg-amber-400 text-slate-950 shadow-md shadow-amber-400/20'
                                            : ($isToday
                                                ? 'bg-slate-800 border-2 border-amber-400 text-white'
                                                : 'bg-slate-800 text-slate-500') }}">
                                        {{ $isCompleted ? '✓' : '' }}
                                    </div>
                                    <span class="text-[10px] font-bold {{ $isToday ? 'text-amber-400' : 'text-slate-400' }}">
                                        {{ $dayName }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- RINGKASAN STATISTIK MINGGUAN -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <h4 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider">
                            Statistik Kedisiplinan Pagi
                        </h4>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Hari Terisi</p>
                                <p class="text-xl font-black text-slate-900 mt-1">{{ $totalDaysActive ?? 0 }} <span class="text-xs font-semibold text-slate-500">Hari</span></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tepat Waktu</p>
                                <p class="text-xl font-black text-emerald-600 mt-1">{{ $tepatWaktuCount ?? 0 }} <span class="text-xs font-semibold text-slate-500">Hari</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- MANFAAT BANGUN PAGI -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <h4 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider">
                            Manfaat Utama Bangun Pagi
                        </h4>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3 bg-amber-50/70 p-3.5 rounded-2xl border border-amber-100">
                                <span class="text-lg">☀️</span>
                                <div>
                                    <p class="text-xs font-extrabold text-amber-950">Fokus Belajar Lebih Tajam</p>
                                    <p class="text-[11px] text-amber-800 leading-snug mt-0.5">Otak memiliki gelombang alfa yang optimal di pagi hari untuk menyerap materi pelajaran baru.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-emerald-50/70 p-3.5 rounded-2xl border border-emerald-100">
                                <span class="text-lg">🧘</span>
                                <div>
                                    <p class="text-xs font-extrabold text-emerald-950">Bebas Stres Terburu-buru</p>
                                    <p class="text-[11px] text-emerald-800 leading-snug mt-0.5">Memiliki waktu luang cukup untuk sarapan bergizi, bersiap rapi, dan berangkat tanpa cemas terlambat.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-blue-50/70 p-3.5 rounded-2xl border border-blue-100">
                                <span class="text-lg">🌱</span>
                                <div>
                                    <p class="text-xs font-extrabold text-blue-950">Membangun Disiplin Diri</p>
                                    <p class="text-[11px] text-blue-800 leading-snug mt-0.5">Kebiasaan bangun tepat waktu adalah fondasi utama karakter tangguh pemimpin masa depan.</p>
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

    </main>
</div>

<script>
    // 1. Evaluasi Waktu Bangun & Status
    function evaluateWakeTime() {
        const input = document.getElementById('waktu_bangun_input');
        const statusInput = document.getElementById('status_waktu_input');
        const card = document.getElementById('status_card');
        const icon = document.getElementById('status_icon');
        const title = document.getElementById('status_title');
        const desc = document.getElementById('status_desc');

        const val = input.value || '04:30';
        const clean = val.replace(':', '');

        if (clean <= '0430') {
            statusInput.value = 'Tepat Waktu';
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-emerald-50 border-emerald-200';
            icon.textContent = '🏆';
            title.textContent = 'Tepat Waktu (Sangat Baik)';
            title.className = 'text-xs font-black text-emerald-950 uppercase tracking-wider';
            desc.textContent = 'Hebat! Bangun sebelum fajar/subuh memberikan energi terbaik dan ketenangan mengawali hari.';
            desc.className = 'text-xs text-emerald-900 leading-relaxed font-medium';
        } else if (clean <= '0530') {
            statusInput.value = 'Cukup Baik';
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-blue-50 border-blue-200';
            icon.textContent = '🌅';
            title.textContent = 'Cukup Baik (Sebelum Terbit Fajar)';
            title.className = 'text-xs font-black text-blue-950 uppercase tracking-wider';
            desc.textContent = 'Bagus! Masih cukup waktu untuk beribadah dan bersiap sekolah dengan tenang tanpa tergesa.';
            desc.className = 'text-xs text-blue-900 leading-relaxed font-medium';
        } else if (clean <= '0630') {
            statusInput.value = 'Lewat Sedikit';
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-amber-50 border-amber-200';
            icon.textContent = '⏰';
            title.textContent = 'Lewat Sedikit';
            title.className = 'text-xs font-black text-amber-950 uppercase tracking-wider';
            desc.textContent = 'Sedikit mepet! Usahakan tidur lebih awal malam ini agar bisa bangun lebih segar besok pagi.';
            desc.className = 'text-xs text-amber-900 leading-relaxed font-medium';
        } else {
            statusInput.value = 'Kesiangan';
            card.className = 'p-4 sm:p-5 rounded-2xl border transition space-y-2 bg-rose-50 border-rose-200';
            icon.textContent = '⚠️';
            title.textContent = 'Kesiangan';
            title.className = 'text-xs font-black text-rose-950 uppercase tracking-wider';
            desc.textContent = 'Perlu perbaikan! Bangun kesiangan membuat rutinitas pagi terburu-buru. Evaluasi jam tidur malammu.';
            desc.className = 'text-xs text-rose-900 leading-relaxed font-medium';
        }
    }

    // 2. Preset Cepat Waktu
    function setTimePreset(timeStr) {
        document.getElementById('waktu_bangun_input').value = timeStr;
        evaluateWakeTime();
    }

    // 3. Highlight Card Rutinitas yang dicentang
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

    // 4. Pilih Mood
    function selectMood(moodId, element) {
        document.getElementById('selected_mood').value = moodId;
        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
            btn.classList.add('border-slate-200', 'hover:border-slate-300', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        });
        element.classList.remove('border-slate-200', 'hover:border-slate-300', 'text-slate-700', 'bg-white', 'font-medium', 'hover:bg-slate-50');
        element.classList.add('border-emerald-600', 'bg-emerald-50', 'text-emerald-950', 'font-bold', 'shadow-sm', 'ring-1', 'ring-emerald-500');
    }

    // 5. Konfirmasi Kosongkan Jurnal
    function confirmReset() {
        if (confirm('Apakah kamu yakin ingin mengosongkan jurnal bangun pagi hari ini? Data di dashboard dan catatan akan direset.')) {
            document.getElementById('form-reset-bangun').submit();
        }
    }

    // 6. Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        evaluateWakeTime();
        document.querySelectorAll('.routine-card input[type="checkbox"]').forEach(cb => {
            updateRoutineUI(cb);
        });
    });
</script>
@endsection
