@extends('layouts.student')

@section('title', 'Taat Beribadah')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">
    
    <!-- HEADER HALAMAN -->
    <div class="relative p-4 sm:p-6 md:p-8 rounded-2xl sm:rounded-3xl shadow-sm text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('https://images.unsplash.com/photo-1564121211835-e88c852651ab?auto=format&fit=crop&q=80&w=1200');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-950 via-emerald-950/90 to-teal-900/60 z-0"></div>

        <div class="space-y-1 relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-xl bg-white/25 backdrop-blur-md border border-white/20 text-[10px] font-black uppercase tracking-wider mb-2">KEBIASAAN IBADAH</span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Taat Beribadah</h1>
            <p class="text-emerald-100 text-sm font-medium">
                Membangun kedisiplinan spiritual dan ketaatan beribadah secara teratur setiap hari.
            </p>
        </div>
        <div class="bg-white/20 backdrop-blur-md border border-white/20 px-5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 relative z-10">
            <span class="text-lg">🔥</span>
            <span class="text-xs font-black text-white uppercase tracking-wider">Streak {{ $streakDays ?? 0 }} Hari</span>
        </div>
    </div>

    <!-- 3 KOTAK STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm space-y-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata Ibadah</p>
            <p class="text-2xl font-black text-gray-900">{{ $averageDaily ?? 0 }} <span class="text-sm font-semibold text-gray-400">Sesi</span></p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm space-y-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tepat Waktu</p>
            <p class="text-2xl font-black text-emerald-600">{{ $onTimePercentage ?? 0 }}%</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm space-y-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paling Konsisten</p>
            <p class="text-xl font-extrabold text-emerald-900 truncate">{{ $mostConsistent ?? '-' }}</p>
        </div>
    </div>

    <!-- KONTEN UTAMA FORM -->
    <form action="{{ route('student.taat-beribadah.update') }}" method="POST" id="journal-form" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- KOLOM KIRI -->
        <div class="lg:col-span-2 space-y-6 flex flex-col">
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-5 flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                        <span>✨</span> Log Ibadah Hari Ini
                    </h3>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                        <button type="button" id="select-all-ibadah" class="text-xs font-bold text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-100 transition">
                            Pilih Semua
                        </button>
                        <span class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-xl border border-gray-100">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>

                <!-- Live Clock Bar -->
                <div class="flex items-center justify-between bg-emerald-50/60 border border-emerald-100 px-4 py-2.5 rounded-2xl text-xs font-semibold text-emerald-900">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Waktu Sistem: <strong id="live-clock">{{ \Carbon\Carbon::now()->format('H:i:s') }}</strong> WIB</span>
                    </div>
                    <span class="text-[11px] text-emerald-700/80 font-medium hidden sm:inline">Status halaman sinkron otomatis</span>
                </div>

                <!-- Grid Pilihan Ibadah -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($prayerStatus as $key => $item)
                        @php
                            $isChecked = $item['is_checked'];
                            $isOpen = $item['is_open'];
                            $isUpcoming = $item['is_upcoming'];
                            $isPast = $item['is_past'];
                            $isDisabled = $item['is_disabled'];
                        @endphp
                        <label id="card-{{ $key }}" class="ibadah-card relative flex items-center justify-between p-4 rounded-2xl border transition {{ $isChecked ? 'border-emerald-600 bg-emerald-50/50 text-emerald-950 shadow-sm' : ($isOpen ? 'border-emerald-400 bg-white ring-2 ring-emerald-500/20 shadow-sm hover:border-emerald-500 cursor-pointer' : 'bg-gray-50/80 border-gray-200 opacity-75 cursor-not-allowed') }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-900 flex items-center justify-center text-lg">
                                    {{ $item['icon'] }}
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-gray-800 block">{{ $item['name'] }}</span>
                                    <div id="badge-{{ $key }}" class="mt-1">
                                        @if($isChecked)
                                            <span class="text-[10px] text-emerald-800 bg-emerald-100 font-bold px-2.5 py-0.5 rounded-full border border-emerald-300">✓ Sudah Dikerjakan</span>
                                        @elseif($isOpen)
                                            <span class="text-[10px] text-emerald-800 bg-emerald-100 font-bold px-2 py-0.5 rounded-full border border-emerald-300 flex items-center gap-1 inline-flex">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                                Buka s/d {{ $item['close_at'] }}
                                            </span>
                                        @elseif($isUpcoming)
                                            <span class="text-[10px] text-amber-800 bg-amber-50 font-semibold px-2 py-0.5 rounded-full border border-amber-200">
                                                ⏳ Buka {{ $item['open_at'] }} (Jadwal {{ $item['time'] }})
                                            </span>
                                        @else
                                            <span class="text-[10px] text-red-700 bg-red-50 font-semibold px-2 py-0.5 rounded-full border border-red-200">
                                                🔒 Waktu Habis (Terkunci)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input type="checkbox" 
                                   id="cb-{{ $key }}"
                                   name="{{ $key }}" 
                                   value="1" 
                                   {{ $isChecked ? 'checked' : '' }} 
                                   {{ $isDisabled ? 'disabled' : '' }} 
                                   onchange="toggleIbadahCard(this)" 
                                   class="ibadah-checkbox w-5 h-5 rounded-lg text-emerald-900 focus:ring-emerald-900 border-gray-300 transition {{ $isOpen && !$isChecked ? 'cursor-pointer' : 'cursor-not-allowed' }}">

                            @if($isChecked)
                                <input type="hidden" name="{{ $key }}" value="1">
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Refleksi & Tombol Simpan -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                <h3 class="font-extrabold text-gray-900 text-sm">Catatan Refleksi Ibadah</h3>
                <textarea name="refleksi" rows="4" placeholder="Bagaimana kekhusyukan dan perasaanmu dalam beribadah hari ini?" class="w-full rounded-2xl border-gray-200 border p-4 text-sm text-gray-800 focus:ring-2 focus:ring-emerald-900 focus:border-transparent outline-none resize-none">{{ optional($journal)->refleksi }}</textarea>
            </div>

            <div class="flex items-center justify-end pt-1">
                <button type="submit" class="w-full sm:w-auto min-h-[48px] bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-black px-8 py-3.5 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                    <span>💾</span> Simpan Jurnal Ibadah
                </button>
            </div>

        </div>

        <!-- KOLOM KANAN -->
        <div class="space-y-6">
            @php
                $currentMonth = \Carbon\Carbon::now();
                $daysInMonth = $currentMonth->daysInMonth;
                $startDayOfWeek = $currentMonth->copy()->startOfMonth()->dayOfWeek; 
                $startDayOfWeek = ($startDayOfWeek == 0) ? 6 : $startDayOfWeek - 1; 
            @endphp

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-extrabold text-gray-900 text-sm uppercase">{{ $currentMonth->translatedFormat('F Y') }}</h4>
                </div>

                <div class="grid grid-cols-7 text-center text-[11px] font-bold text-gray-400">
                    <span>M</span><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span>
                </div>

                <div class="grid grid-cols-7 gap-y-2 text-center text-xs font-bold">
                    @for ($i = 0; $i < $startDayOfWeek; $i++)
                        <span class="text-transparent">0</span>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateStr = $currentMonth->copy()->day($day)->toDateString();
                            $isToday = $currentMonth->copy()->day($day)->isToday();
                            $hasFilled = false;
                            if (isset($monthlyJournals)) {
                                if ($monthlyJournals instanceof \Illuminate\Support\Collection) {
                                    $hasFilled = $monthlyJournals->has($dateStr) || $monthlyJournals->contains('tanggal', $dateStr);
                                } elseif (is_array($monthlyJournals)) {
                                    $hasFilled = array_key_exists($dateStr, $monthlyJournals) || in_array($dateStr, $monthlyJournals);
                                }
                            }
                        @endphp

                        @if($isToday)
                            <span class="bg-emerald-900 text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto shadow-md">{{ $day }}</span>
                        @elseif($hasFilled)
                            <span class="bg-emerald-500 text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto shadow-sm">{{ $day }}</span>
                        @else
                            <span class="text-gray-700 py-1.5">{{ $day }}</span>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Bagian pelengkap kanan -->
            <div class="relative rounded-3xl overflow-hidden shadow-sm border border-gray-100 group">
                <div class="h-44 bg-cover bg-center" style="background-image: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.1)), url('https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=600&q=80');"></div>
                <div class="absolute bottom-0 inset-x-0 p-5 text-white space-y-1">
                    <h5 class="font-extrabold text-sm">Kedisiplinan Spiritual</h5>
                    <p class="text-[11px] text-gray-200">INSPIRASI HARIAN</p>
                </div>
            </div>

            <div class="bg-emerald-900 text-white p-6 rounded-3xl shadow-xl space-y-3 relative overflow-hidden">
                <div class="text-3xl font-serif text-emerald-400 leading-none">“</div>
                <p class="text-xs italic leading-relaxed text-emerald-100 -mt-2">
                    "Amalan yang paling dicintai oleh Allah adalah amalan yang rutin dilakukan meskipun sedikit."
                </p>
                <p class="text-[10px] font-bold text-emerald-300 tracking-wider text-right">(Catatan Guru Agama)</p>
            </div>
        </div>
    </form>
</div>

<script>
    const prayerSchedules = @json($prayerStatus);

    function toggleIbadahCard(checkbox) {
        const card = checkbox.closest('.ibadah-card');
        if (!card) return;
        if (checkbox.checked) {
            card.classList.remove('border-gray-200', 'hover:border-emerald-300', 'hover:shadow-sm', 'text-gray-800', 'bg-white');
            card.classList.add('border-emerald-600', 'bg-emerald-50/50', 'text-emerald-950', 'shadow-sm');
        } else {
            card.classList.remove('border-emerald-600', 'bg-emerald-50/50', 'text-emerald-950', 'shadow-sm');
            card.classList.add('border-gray-200', 'hover:border-emerald-300', 'hover:shadow-sm', 'text-gray-800', 'bg-white');
        }
    }

    function checkPrayerLockTimes() {
        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();

        Object.keys(prayerSchedules).forEach(key => {
            const item = prayerSchedules[key];
            const cb = document.getElementById(`cb-${key}`);
            const card = document.getElementById(`card-${key}`);
            const badge = document.getElementById(`badge-${key}`);

            if (item.is_checked) return;

            const [openH, openM] = item.open_at.split(':').map(Number);
            const [closeH, closeM] = item.close_at.split(':').map(Number);
            const openMinutes = openH * 60 + openM;
            const closeMinutes = closeH * 60 + closeM;

            const isOpen = currentMinutes >= openMinutes && currentMinutes <= closeMinutes;
            const isPast = currentMinutes > closeMinutes;
            const isUpcoming = currentMinutes < openMinutes;

            if (isOpen) {
                if (cb) {
                    cb.disabled = false;
                    cb.classList.remove('cursor-not-allowed');
                    cb.classList.add('cursor-pointer');
                }
                if (card) {
                    card.classList.remove('bg-gray-50/80', 'opacity-75', 'cursor-not-allowed');
                    card.classList.add('bg-white', 'border-emerald-400', 'ring-2', 'ring-emerald-500/20', 'shadow-sm', 'cursor-pointer');
                }
                if (badge) {
                    badge.innerHTML = `<span class="text-[10px] text-emerald-800 bg-emerald-100 font-bold px-2 py-0.5 rounded-full border border-emerald-300 flex items-center gap-1 inline-flex"><span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>Buka s/d ${item.close_at}</span>`;
                }
            } else if (isPast) {
                if (cb) {
                    cb.disabled = true;
                    if (!cb.checked) cb.checked = false;
                    cb.classList.remove('cursor-pointer');
                    cb.classList.add('cursor-not-allowed');
                }
                if (card) {
                    card.classList.remove('ring-2', 'ring-emerald-500/20', 'border-emerald-400', 'cursor-pointer');
                    card.classList.add('bg-gray-50/80', 'border-gray-200', 'opacity-75', 'cursor-not-allowed');
                }
                if (badge) {
                    badge.innerHTML = `<span class="text-[10px] text-red-700 bg-red-50 font-semibold px-2 py-0.5 rounded-full border border-red-200">🔒 Waktu Habis (Terkunci)</span>`;
                }
            } else if (isUpcoming) {
                if (cb) {
                    cb.disabled = true;
                    cb.checked = false;
                    cb.classList.remove('cursor-pointer');
                    cb.classList.add('cursor-not-allowed');
                }
                if (card) {
                    card.classList.remove('ring-2', 'ring-emerald-500/20', 'border-emerald-400', 'cursor-pointer');
                    card.classList.add('bg-gray-50/80', 'border-gray-200', 'opacity-75', 'cursor-not-allowed');
                }
                if (badge) {
                    badge.innerHTML = `<span class="text-[10px] text-amber-800 bg-amber-50 font-semibold px-2 py-0.5 rounded-full border border-amber-200">⏳ Buka ${item.open_at} (Jadwal ${item.time})</span>`;
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const selectAllBtn = document.getElementById('select-all-ibadah');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function () {
                const availableCheckboxes = document.querySelectorAll('.ibadah-checkbox:not(:disabled)');
                if (availableCheckboxes.length === 0) {
                    alert('Saat ini belum ada waktu sholat yang terbuka untuk dicentang.');
                    return;
                }
                const allChecked = Array.from(availableCheckboxes).every(cb => cb.checked);
                
                availableCheckboxes.forEach(cb => { 
                    cb.checked = !allChecked; 
                    toggleIbadahCard(cb);
                });
                
                selectAllBtn.textContent = !allChecked ? 'Batal Pilih' : 'Pilih Semua';
            });
        }

        document.querySelectorAll('.ibadah-checkbox').forEach(cb => {
            if (cb.checked) {
                toggleIbadahCard(cb);
            }
        });

        const clockElement = document.getElementById('live-clock');
        if (clockElement) {
            setInterval(() => {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }, 1000);
        }

        // Cek penyesuaian status lock secara berkala
        setInterval(checkPrayerLockTimes, 10000);
    });
</script>
@endsection