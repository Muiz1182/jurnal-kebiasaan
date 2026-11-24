@extends('layouts.student')

@section('title', 'Tidur Cepat & Istirahat Berkualitas')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">
        
        <form id="form-tidur-cepat" action="{{ route('student.tidur-cepat.store') }}" method="POST">
            @csrf

            <!-- HEADER BANNER UTAMA -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 p-6 sm:p-8 rounded-3xl shadow-xl text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="space-y-2 z-10 max-w-2xl">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-block px-3.5 py-1 bg-white/10 backdrop-blur-md text-blue-200 text-[10px] font-extrabold rounded-full uppercase tracking-widest border border-white/10">
                            KEBIASAAN 7
                        </span>
                        @if($journal)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 text-emerald-200 text-[10px] font-extrabold rounded-full border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Sudah Tersimpan Hari Ini
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Tidur Cepat & Berkualitas</h1>
                    <p class="text-blue-200/90 text-xs sm:text-sm italic font-medium">
                        "Kebiasaan ketujuh: Istirahat tepat waktu maksimal pukul 22:00 agar tubuh segar dan fokus belajar optimal esok hari."
                    </p>
                </div>

                <!-- Bagian Tanggal & Tombol Aksi -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full md:w-auto shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl flex items-center gap-3">
                        <span class="text-lg sm:text-xl shrink-0">🌙</span>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-300">MALAM INI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white truncate">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>

                    @if($journal)
                        <button type="button" onclick="if(confirm('Yakin ingin mengosongkan jurnal tidur cepat hari ini?')) { document.getElementById('delete-journal-form').submit(); }" 
                            class="w-full sm:w-auto min-h-[44px] bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 border border-rose-500/30 font-extrabold px-4 py-2.5 rounded-xl sm:rounded-2xl flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                            <span>🗑️</span> Kosongkan
                        </button>
                    @endif
                </div>
            </div>

            <!-- GRID UTAMA HALAMAN -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI & TENGAH (Waktu Tidur, Kualitas, Rutinitas, Refleksi) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. WAKTU TIDUR & BANGUN -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-5">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-base">Jadwal Istirahat Malam</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Disarankan tidur sebelum pukul 22:00 untuk menjaga hormon pertumbuhan dan memori.</p>
                            </div>
                            <span id="badge-status-tidur" class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ optional($journal)->is_begadang ? '🚨 Begadang' : '✅ Tepat Waktu' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Input Jam Tidur -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Jam Mulai Tidur
                                </label>
                                <input type="time" id="input-jam-tidur" name="jam_tidur" 
                                    value="{{ old('jam_tidur', optional($journal)->jam_tidur ?? '21:30') }}" 
                                    onchange="updateSleepAnalysis()"
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-base font-extrabold text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/30 outline-none transition" required>
                                
                                <!-- Preset Buttons Jam Tidur -->
                                <div class="flex items-center gap-1.5 pt-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Cepat:</span>
                                    <button type="button" onclick="setBedtime('21:00')" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">21:00</button>
                                    <button type="button" onclick="setBedtime('21:30')" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-800 transition cursor-pointer">21:30</button>
                                    <button type="button" onclick="setBedtime('22:00')" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">22:00</button>
                                </div>
                            </div>

                            <!-- Input Target Bangun -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Target Bangun Pagi
                                </label>
                                <input type="time" id="input-target-bangun" name="target_bangun" 
                                    value="{{ old('target_bangun', optional($journal)->target_bangun ?? '04:30') }}" 
                                    onchange="updateSleepAnalysis()"
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-base font-extrabold text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/30 outline-none transition" required>
                                
                                <!-- Preset Buttons Target Bangun -->
                                <div class="flex items-center gap-1.5 pt-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Cepat:</span>
                                    <button type="button" onclick="setWakeup('04:00')" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">04:00</button>
                                    <button type="button" onclick="setWakeup('04:30')" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-800 transition cursor-pointer">04:30</button>
                                    <button type="button" onclick="setWakeup('05:00')" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">05:00</button>
                                </div>
                            </div>
                        </div>

                        <!-- Card Estimasi Durasi Tidur Live -->
                        <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">⏳</span>
                                <div>
                                    <p class="text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Estimasi Waktu Tidur</p>
                                    <h4 id="label-durasi-live" class="text-base font-black text-indigo-950">
                                        {{ optional($journal)->durasi_tidur ?? '7 Jam 0 Menit' }}
                                    </h4>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-indigo-800 bg-white px-3 py-1 rounded-full border border-indigo-200">
                                Rekomendasi SMP: 7 s.d. 9 Jam
                            </span>
                        </div>
                    </div>

                    <!-- 2. KUALITAS ISTIRAHAT -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-base">Kualitas Istirahat</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Bagaimana kualitas tidur yang kamu rasakan?</p>
                            </div>
                            <span class="text-xl">🛌</span>
                        </div>

                        @php
                            $currentKualitas = old('kualitas_istirahat', optional($journal)->kualitas_istirahat ?? 'Nyenyak');
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Opsi Buruk -->
                            <label class="cursor-pointer">
                                <input type="radio" name="kualitas_istirahat" value="Buruk" class="peer sr-only" {{ $currentKualitas == 'Buruk' ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50/50 hover:bg-white peer-checked:border-rose-500 peer-checked:bg-rose-50/40 peer-checked:text-rose-900 transition flex flex-col items-center justify-center text-center gap-1.5 h-28 shadow-sm">
                                    <span class="text-2xl">🔴</span>
                                    <span class="text-xs font-bold text-gray-800 peer-checked:text-rose-900">Kurang Nyenyak</span>
                                    <span class="text-[10px] text-gray-500 font-medium">Gelisah / Sering terbangun</span>
                                </div>
                            </label>

                            <!-- Opsi Cukup -->
                            <label class="cursor-pointer">
                                <input type="radio" name="kualitas_istirahat" value="Cukup" class="peer sr-only" {{ $currentKualitas == 'Cukup' ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50/50 hover:bg-white peer-checked:border-amber-500 peer-checked:bg-amber-50/40 peer-checked:text-amber-900 transition flex flex-col items-center justify-center text-center gap-1.5 h-28 shadow-sm">
                                    <span class="text-2xl">🟡</span>
                                    <span class="text-xs font-bold text-gray-800 peer-checked:text-amber-900">Cukup Nyenyak</span>
                                    <span class="text-[10px] text-gray-500 font-medium">Tidur normal biasa</span>
                                </div>
                            </label>

                            <!-- Opsi Nyenyak -->
                            <label class="cursor-pointer">
                                <input type="radio" name="kualitas_istirahat" value="Nyenyak" class="peer sr-only" {{ $currentKualitas == 'Nyenyak' ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50/50 hover:bg-white peer-checked:border-emerald-600 peer-checked:bg-emerald-50/40 peer-checked:text-emerald-900 transition flex flex-col items-center justify-center text-center gap-1.5 h-28 shadow-sm">
                                    <span class="text-2xl">🟢</span>
                                    <span class="text-xs font-bold text-gray-800 peer-checked:text-emerald-900">Sangat Nyenyak</span>
                                    <span class="text-[10px] text-gray-500 font-medium">Bangun segar & berenergi</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 3. RUTINITAS MENJELANG TIDUR -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-base">Rutinitas Baik Sebelum Tidur</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Centang kebiasaan positif yang kamu lakukan sebelum tidur malam ini:</p>
                            </div>
                            <span class="text-xl">✨</span>
                        </div>

                        @php
                            $routineList = [
                                ['id' => 'ganti_baju', 'icon' => '👕', 'label' => 'Mengganti baju tidur yang bersih'],
                                ['id' => 'sikat_gigi', 'icon' => '🪥', 'label' => 'Menggosok gigi dan mencuci muka'],
                                ['id' => 'berdoa', 'icon' => '🤲', 'label' => 'Berdoa sebelum tidur'],
                                ['id' => 'matikan_lampu', 'icon' => '💡', 'label' => 'Mematikan lampu kamar / lampu tidur redup'],
                                ['id' => 'jauhkan_hp', 'icon' => '📵', 'label' => 'Menjauhkan ponsel / gadget 30 menit sebelum tidur'],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            @foreach($routineList as $item)
                                <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-indigo-50/30 hover:border-indigo-200 transition cursor-pointer">
                                    <input type="checkbox" name="rutinitas[]" value="{{ $item['id'] }}" 
                                        class="rounded-lg border-gray-300 text-indigo-600 focus:ring-0 w-4 h-4 cursor-pointer"
                                        {{ (isset($routines) && is_array($routines) && in_array($item['id'], $routines)) ? 'checked' : '' }}>
                                    <span class="text-lg">{{ $item['icon'] }}</span>
                                    <span class="text-xs font-semibold text-gray-800">{{ $item['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 4. REFLEKSI MALAM -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-base">Refleksi Malam Hari</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Apa hal baik yang kamu syukuri atau pelajari hari ini sebelum beristirahat?</p>
                            </div>
                            <span class="text-xs font-bold text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-200">Refleksi Diri</span>
                        </div>
                        
                        <textarea name="refleksi_malam" rows="3" 
                            class="w-full rounded-2xl border border-gray-200 p-4 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/30 outline-none transition"
                            placeholder="Tuliskan rasa syukur atau tekad perbaikan untuk hari esok...">{{ old('refleksi_malam', optional($journal)->refleksi_malam) }}</textarea>
                    </div>

                    <!-- Tombol Simpan Bawah -->
                    <div class="flex items-center justify-end pt-2">
                        <button type="submit" class="w-full sm:w-auto min-h-[48px] bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-black px-8 py-3.5 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                            <span>💾</span> Simpan Jurnal Tidur Cepat
                        </button>
                    </div>

                </div>

                <!-- KOLOM KANAN (Statistik Pekan Ini, Tips Tidur & Riwayat) -->
                <div class="space-y-6">
                    
                    <!-- RANGKUMAN PEKAN INI (Dinamis dari Database) -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="font-extrabold text-gray-900 text-sm tracking-wide uppercase">Rangkuman Pekan Ini</h3>
                            <span class="text-indigo-600 text-base">📊</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-500 uppercase">Rerata Jam Tidur</span>
                                <span class="text-lg font-black text-gray-900 mt-0.5 block">{{ $summary['rerata_jam_tidur'] ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-500 uppercase">Rerata Durasi</span>
                                <span class="text-lg font-black text-indigo-900 mt-0.5 block">{{ $summary['total_durasi'] ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-500 uppercase">Disiplin Waktu</span>
                                <span class="text-lg font-black text-emerald-700 mt-0.5 block">{{ $summary['konsistensi'] ?? '0%' }}</span>
                            </div>
                            <div class="bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-500 uppercase">Sesi Tercatat</span>
                                <span class="text-lg font-black text-amber-700 mt-0.5 block">{{ $summary['total_sesi'] ?? 0 }} Malam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Tidur Sehat Card -->
                    <div class="bg-indigo-950 text-white p-6 rounded-3xl shadow-xl space-y-3 relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
                        <span class="bg-indigo-600/80 text-indigo-200 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            PANDUAN MEDIS
                        </span>
                        <h4 class="text-base font-extrabold text-white">Manfaat Tidur Tepat Waktu</h4>
                        <p class="text-xs text-indigo-200/90 leading-relaxed font-normal">
                            Tidur sebelum pukul 22:00 memicu pelepasan hormon melatonin dan *human growth hormone* (HGH) yang optimal untuk perkembangan sel otak serta imunitas tubuh remaja.
                        </p>
                    </div>

                    <!-- Riwayat 7 Hari Terakhir -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                        <h3 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider">Riwayat Tidur Terakhir</h3>
                        
                        <div class="space-y-2 pt-1">
                            @forelse($recentJournals as $rj)
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-gray-50/60 border border-gray-100 text-xs">
                                    <div>
                                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($rj->date)->translatedFormat('d M Y') }}</p>
                                        <p class="text-[11px] text-gray-500">Tidur {{ $rj->jam_tidur }} • Bangun {{ $rj->target_bangun }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold {{ $rj->is_begadang ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                            {{ $rj->status_tidur }}
                                        </span>
                                        <p class="text-[10px] text-gray-500 font-bold mt-0.5">{{ $rj->durasi_tidur }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-gray-400 text-xs italic bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    Belum ada catatan tidur sebelumnya.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </form>

        <!-- Form tersembunyi untuk reset jurnal hari ini -->
        @if($journal)
            <form id="delete-journal-form" action="{{ route('student.tidur-cepat.destroy') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif

</div>

<script>
    function setBedtime(val) {
        const el = document.getElementById('input-jam-tidur');
        if (el) {
            el.value = val;
            updateSleepAnalysis();
        }
    }

    function setWakeup(val) {
        const el = document.getElementById('input-target-bangun');
        if (el) {
            el.value = val;
            updateSleepAnalysis();
        }
    }

    function updateSleepAnalysis() {
        const jamTidur = document.getElementById('input-jam-tidur')?.value;
        const targetBangun = document.getElementById('input-target-bangun')?.value;
        const badgeStatus = document.getElementById('badge-status-tidur');
        const labelDurasi = document.getElementById('label-durasi-live');

        if (!jamTidur || !targetBangun) return;

        // Cek begadang
        const isBegadang = (jamTidur >= '22:00' || jamTidur <= '03:00');
        if (badgeStatus) {
            if (isBegadang) {
                badgeStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200';
                badgeStatus.textContent = '🚨 Begadang';
            } else {
                badgeStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200';
                badgeStatus.textContent = '✅ Tepat Waktu';
            }
        }

        // Hitung estimasi durasi
        const [h1, m1] = jamTidur.split(':').map(Number);
        const [h2, m2] = targetBangun.split(':').map(Number);

        let totalMins1 = h1 * 60 + m1;
        let totalMins2 = h2 * 60 + m2;

        if (totalMins2 <= totalMins1) {
            totalMins2 += 24 * 60; // melewati tengah malam
        }

        const diffMins = totalMins2 - totalMins1;
        const hours = Math.floor(diffMins / 60);
        const mins = diffMins % 60;

        if (labelDurasi) {
            if (mins > 0) {
                labelDurasi.textContent = `${hours} Jam ${mins} Menit`;
            } else {
                labelDurasi.textContent = `${hours} Jam`;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateSleepAnalysis();
    });
</script>
@endsection
