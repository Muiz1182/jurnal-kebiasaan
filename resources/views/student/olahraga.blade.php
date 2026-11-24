@extends('layouts.student')

@section('title', 'Jurnal Olahraga')

@section('content')
<div class="flex min-h-screen bg-gray-50 w-full">
    
    <main class="flex-1 p-8 space-y-6 overflow-y-auto">
        
        <!-- Notifikasi Pesan Sukses -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm text-sm font-medium flex items-center gap-3" role="alert">
                <span class="text-xl">✨</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('student.olahraga.store') }}" method="POST">
            @csrf

            <!-- HEADER BANNER BIRU TUA (Sesuai Referensi Gambar) -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 p-6 sm:p-8 rounded-3xl shadow-xl text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6 relative overflow-hidden">
                <!-- Efek aksen cahaya latar belakang tipis -->
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="space-y-2 z-10">
                    <span class="inline-block px-3.5 py-1 bg-white/10 backdrop-blur-md text-blue-200 text-[10px] font-extrabold rounded-full uppercase tracking-widest border border-white/10">
                        KEBIASAAN 3
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Jurnal Olahraga</h1>
                    <p class="text-blue-200/90 text-xs sm:text-sm italic font-medium">
                        "Tubuh yang sehat adalah rumah bagi pikiran yang hebat."
                    </p>
                </div>

                <!-- Bagian Tanggal Hari Ini & Tombol Simpan -->
                <div class="flex items-center gap-4 z-10 w-full md:w-auto justify-between md:justify-end">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 px-4 py-3 rounded-2xl flex items-center gap-3">
                        <span class="text-xl">📅</span>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-300">HARI INI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>

                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center gap-2 transition text-xs sm:text-sm">
                        <span>💾</span> Simpan Jurnal
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI & TENGAH -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. Pilih Jenis Olahraga (Multi-select) -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-extrabold text-gray-900 text-base">Pilih Jenis Olahraga <span class="text-xs font-normal text-gray-400">(Bisa pilih lebih dari satu)</span></h3>
                        </div>
                        
                        @php
                            // Ambil data lama jika ada validasi gagal atau dari database
                            $oldSports = old('jenis_olahraga', isset($journal->jenis_olahraga) ? explode(', ', $journal->jenis_olahraga) : []);
                            
                            $defaultSports = [
                                'Lari / Jalan', 'Bersepeda', 'Berenang', 'Sepak Bola', 
                                'Basket', 'Latihan Beban', 'Senam'
                            ];

                            // Mendeteksi apakah ada olahraga custom (di luar daftar default)
                            $customSports = array_filter($oldSports, function($sport) use ($defaultSports) {
                                return !in_array($sport, $defaultSports);
                            });

                            $hasCustomSelected = count($customSports) > 0;
                        @endphp

                        <!-- Container untuk menampung input hidden dinamis agar bisa dikirim sebagai array -->
                        <div id="hidden_sports_container">
                            @foreach($oldSports as $selectedSport)
                                <input type="hidden" name="jenis_olahraga[]" value="{{ trim($selectedSport) }}" class="hidden-sport-input" data-sport="{{ trim($selectedSport) }}">
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                                $sports = [
                                    ['name' => 'Lari / Jalan', 'icon' => '🏃‍♂️'],
                                    ['name' => 'Bersepeda', 'icon' => '🚴‍♂️'],
                                    ['name' => 'Berenang', 'icon' => '🏊‍♂️'],
                                    ['name' => 'Sepak Bola', 'icon' => '⚽'],
                                    ['name' => 'Basket', 'icon' => '🏀'],
                                    ['name' => 'Latihan Beban', 'icon' => '🏋️‍♂️'],
                                    ['name' => 'Senam', 'icon' => '🤸'],
                                    ['name' => 'Lainnya', 'icon' => '➕'],
                                ];
                            @endphp

                            @foreach($sports as $sport)
                                @php
                                    $isSelected = ($sport['name'] === 'Lainnya') ? $hasCustomSelected : in_array($sport['name'], $oldSports);
                                @endphp
                                <button type="button" 
                                    onclick="toggleSport('{{ $sport['name'] }}', this)"
                                    class="sport-btn p-4 rounded-2xl border text-center flex flex-col items-center gap-2 transition {{ $isSelected ? 'border-emerald-600 bg-emerald-50/40 text-emerald-900 shadow-sm' : 'border-gray-200 hover:border-emerald-300 text-gray-700 bg-white' }}"
                                    data-sport-name="{{ $sport['name'] }}">
                                    <span class="text-2xl">{{ $sport['icon'] }}</span>
                                    <span class="text-xs font-bold">{{ $sport['name'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- KOTAK INPUT TAMBAHAN KETIKA 'LAINNYA' DIPILIH -->
                        <div id="custom_sport_wrapper" class="{{ $hasCustomSelected ? '' : 'hidden' }} mt-4 pt-4 border-t border-gray-100">
                            <label class="block text-xs font-bold text-gray-700 mb-2">Tuliskan Jenis Olahraga Lainnya:</label>
                            <div class="flex gap-2">
                                <input type="text" id="custom_sport_input" placeholder="Contoh: Bulu Tangkis, Tenis, dll..." 
                                    class="flex-1 rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30">
                                <button type="button" onclick="addCustomSport()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-5 py-3 rounded-2xl text-xs transition">
                                    Tambah
                                </button>
                            </div>
                        </div>

                        <!-- Daftar Badge Olahraga Lainnya yang Ditambahkan -->
                        <div id="custom_sports_badges" class="flex flex-wrap gap-2 mt-3">
                            @foreach($customSports as $customSport)
                                <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-1.5 rounded-xl text-xs font-bold">
                                    <span>{{ trim($customSport) }}</span> 
                                    <button type="button" onclick="removeCustomSportBadge(this, '{{ trim($customSport) }}')" class="text-red-500 hover:text-red-700 font-bold ml-1">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Grid Durasi & Intensitas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 2. Durasi Latihan -->
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 flex flex-col justify-between">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-base mb-4">Durasi Latihan</h3>
                                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex items-center justify-between">
                                    <input type="number" name="durasi" id="durasi_input" min="1" value="{{ old('durasi', optional($journal)->durasi) }}" placeholder="0" required
                                        class="w-24 bg-transparent text-3xl font-extrabold text-gray-900 focus:outline-none text-center">
                                    <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Menit</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-2 pt-2">
                                @foreach([15, 30, 45, 60] as $mins)
                                    <button type="button" onclick="setDurasi({{ $mins }})" class="py-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 text-xs font-bold rounded-xl transition">
                                        {{ $mins }}m
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- 3. Intensitas -->
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                            <h3 class="font-extrabold text-gray-900 text-base">Intensitas</h3>
                            
                            <div class="space-y-3">
                                @php
                                    $intensities = [
                                        ['name' => 'Rendah', 'desc' => 'Bisa bicara lancar', 'bolts' => 1],
                                        ['name' => 'Sedang', 'desc' => 'Napas agak tersengat', 'bolts' => 2],
                                        ['name' => 'Tinggi', 'desc' => 'Sangat berkeringat', 'bolts' => 3],
                                    ];
                                    $currentIntensitas = old('intensitas', optional($journal)->intensitas);
                                @endphp

                                @foreach($intensities as $item)
                                    <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition {{ $currentIntensitas == $item['name'] ? 'border-emerald-600 bg-emerald-50/30' : 'border-gray-200 hover:border-gray-300' }}">
                                        <div class="identity-wrapper flex items-center gap-3">
                                            <input type="radio" name="intensitas" value="{{ $item['name'] }}" {{ $currentIntensitas == $item['name'] ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500" required onchange="updateIntensitasUI()">
                                            <div>
                                                <p class="text-xs font-bold text-gray-900">{{ $item['name'] }}</p>
                                                <p class="text-[10px] text-gray-400">{{ $item['desc'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex text-emerald-500 text-xs font-bold gap-0.5">
                                            @for($i=0; $i<$item['bolts']; $i++) ⚡ @endfor
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <!-- 4. Perasaan & Catatan -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <h3 class="font-extrabold text-gray-900 text-base">Bagaimana perasaanmu setelah olahraga?</h3>
                        
                        <input type="hidden" name="perasaan" id="selected_perasaan" value="{{ old('perasaan', optional($journal)->perasaan) }}" required>

                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            @php
                                $moods = [
                                    ['name' => 'Lelah', 'emoji' => '😩'],
                                    ['name' => 'Biasa', 'emoji' => '😐'],
                                    ['name' => 'Senang', 'emoji' => '😊'],
                                    ['name' => 'Semangat', 'emoji' => '😁'],
                                    ['name' => 'Luar Biasa', 'emoji' => '✨'],
                                ];
                            @endphp

                            @foreach($moods as $mood)
                                <button type="button" 
                                    onclick="selectMood('{{ $mood['name'] }}', this)"
                                    class="mood-btn p-3 rounded-2xl border text-center flex flex-col items-center gap-1.5 transition {{ (old('perasaan', optional($journal)->perasaan) == $mood['name']) ? 'border-emerald-600 bg-emerald-50/40 text-emerald-900 shadow-sm' : 'border-gray-200 hover:border-emerald-300 text-gray-700 bg-white' }}">
                                    <span class="text-2xl">{{ $mood['emoji'] }}</span>
                                    <span class="text-xs font-bold">{{ $mood['name'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="pt-2">
                            <textarea name="catatan" rows="3" 
                                class="w-full rounded-2xl border border-gray-200 p-4 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30"
                                placeholder="Catatan tambahan (contoh: rute lari baru atau rekor pribadi)...">{{ old('catatan', optional($journal)->catatan) }}</textarea>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN (Sidebar Widget) -->
                <div class="space-y-6">
                    
                    <!-- Weekly Streak Card -->
                    <div class="bg-blue-900 text-white p-6 rounded-3xl shadow-xl space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-300">Weekly Streak</span>
                        </div>
                        <div>
                            <h4 class="text-3xl font-extrabold flex items-baseline gap-2">
                                {{ $totalDaysActive ?? 0 }} <span class="text-xs font-bold text-blue-200">Hari minggu ini</span>
                            </h4>
                        </div>
                        <div class="grid grid-cols-7 gap-1 pt-2">
                            @php
                                $daysMapping = [
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    7 => 'Minggu',
                                ];
                                $todayNum = \Carbon\Carbon::today()->format('N');
                            @endphp
                            
                            @foreach($daysMapping as $dayIndex => $dayLabel)
                                @php
                                    $isCompleted = isset($weeklyChartData[$dayIndex]) && $weeklyChartData[$dayIndex]['active'];
                                    $isToday = ($todayNum == $dayIndex);
                                @endphp
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-8 h-8 rounded-full 
                                        {{ $isCompleted 
                                            ? 'bg-emerald-400 text-blue-950 font-bold' 
                                            : ($isToday 
                                                ? 'bg-white text-blue-900 font-extrabold shadow-md ring-2 ring-emerald-400' 
                                                : 'bg-blue-950 text-blue-400') }} 
                                        flex items-center justify-center text-xs">
                                        {{ $isCompleted ? '✓' : '' }}
                                    </div>
                                    <span class="text-[10px] text-blue-300 font-bold">{{ $dayLabel }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tips Sehat Hari Ini -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <h3 class="font-extrabold text-gray-900 text-sm">Tips Sehat Hari Ini</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 bg-emerald-50/50 p-3 rounded-2xl border border-emerald-100/50">
                                <span class="text-emerald-600 bg-emerald-100 p-1.5 rounded-xl text-xs">💧</span>
                                <p class="text-xs text-gray-600 leading-relaxed">Minum minimal 250ml air putih segera setelah berolahraga.</p>
                            </div>
                            <div class="flex items-start gap-3 bg-emerald-50/50 p-3 rounded-2xl border border-emerald-100/50">
                                <span class="text-emerald-600 bg-emerald-100 p-1.5 rounded-xl text-xs">🧘</span>
                                <p class="text-xs text-gray-600 leading-relaxed">Jangan lupa pemanasan 5 menit untuk menghindari cedera otot.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </main>
</div>

<script>
    // Fungsi untuk multi-select jenis olahraga
    function toggleSport(sportName, element) {
        const container = document.getElementById('hidden_sports_container');
        const customWrapper = document.getElementById('custom_sport_wrapper');

        // Jika tombol yang diklik adalah "Lainnya"
        if (sportName === 'Lainnya') {
            const isSelected = element.classList.contains('border-emerald-600');
            if (!isSelected) {
                customWrapper.classList.remove('hidden');
                element.classList.remove('border-gray-200', 'text-gray-700', 'bg-white');
                element.classList.add('border-emerald-600', 'bg-emerald-50/40', 'text-emerald-900', 'shadow-sm');
            } else {
                customWrapper.classList.add('hidden');
                element.classList.remove('border-emerald-600', 'bg-emerald-50/40', 'text-emerald-900', 'shadow-sm');
                element.classList.add('border-gray-200', 'text-gray-700', 'bg-white');
            }
            return;
        }

        const existingInput = container.querySelector(`input[data-sport="${sportName}"]`);

        if (existingInput) {
            existingInput.remove();
            element.classList.remove('border-emerald-600', 'bg-emerald-50/40', 'text-emerald-900', 'shadow-sm');
            element.classList.add('border-gray-200', 'text-gray-700', 'bg-white');
        } else {
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'jenis_olahraga[]';
            newInput.value = sportName;
            newInput.setAttribute('data-sport', sportName);
            container.appendChild(newInput);

            element.classList.remove('border-gray-200', 'text-gray-700', 'bg-white');
            element.classList.add('border-emerald-600', 'bg-emerald-50/40', 'text-emerald-900', 'shadow-sm');
        }
    }

    // Fungsi untuk menambahkan teks olahraga kustom ke dalam form
    function addCustomSport() {
        const inputField = document.getElementById('custom_sport_input');
        const sportName = inputField.value.trim();
        if (!sportName) return;

        const container = document.getElementById('hidden_sports_container');
        const badgesContainer = document.getElementById('custom_sports_badges');

        // Cek apakah input dengan nama yang sama sudah ada
        if (container.querySelector(`input[data-sport="${sportName}"]`)) {
            inputField.value = '';
            return;
        }

        // Buat input hidden untuk dikirim ke Controller
        const newInput = document.createElement('input');
        newInput.type = 'hidden';
        newInput.name = 'jenis_olahraga[]';
        newInput.value = sportName;
        newInput.setAttribute('data-sport', sportName);
        container.appendChild(newInput);

        // Buat badge visual dengan template literal yang aman
        const badge = document.createElement('div');
        badge.className = 'inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-1.5 rounded-xl text-xs font-bold';
        
        const spanText = document.createElement('span');
        spanText.textContent = sportName;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'text-red-500 hover:text-red-700 font-bold ml-1';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = function() {
            removeCustomSportBadge(badge, sportName);
        };

        badge.appendChild(spanText);
        badge.appendChild(removeBtn);
        badgesContainer.appendChild(badge);

        inputField.value = '';
    }

    // Fungsi terpisah untuk menghapus badge kustom dan input hidden terkait
    function removeCustomSportBadge(badgeElement, sportName) {
        badgeElement.remove();
        const container = document.getElementById('hidden_sports_container');
        const inputEl = container.querySelector(`input[data-sport="${sportName}"]`);
        if (inputEl) inputEl.remove();
    }

    function selectMood(moodName, element) {
        document.getElementById('selected_perasaan').value = moodName;
        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.classList.remove('border-emerald-600', 'bg-emerald-50/40', 'text-emerald-900', 'shadow-sm');
            btn.classList.add('border-gray-200', 'text-gray-700', 'bg-white');
        });
        element.classList.remove('border-gray-200', 'text-gray-700', 'bg-white');
        element.classList.add('border-emerald-600', 'bg-emerald-50/40', 'text-emerald-900', 'shadow-sm');
    }

    function setDurasi(minutes) {
        document.getElementById('durasi_input').value = minutes;
    }

    // Fungsi untuk memperbarui tampilan UI saat radio button intensitas dipilih
    function updateIntensitasUI() {
        document.querySelectorAll('input[name="intensitas"]').forEach(radio => {
            const label = radio.closest('label');
            if (radio.checked) {
                label.classList.remove('border-gray-200', 'hover:border-gray-300');
                label.classList.add('border-emerald-600', 'bg-emerald-50/30');
            } else {
                label.classList.remove('border-emerald-600', 'bg-emerald-50/30');
                label.classList.add('border-gray-200', 'hover:border-gray-300');
            }
        });
    }

    // Jalankan saat halaman dimuat agar state awal dari old() / database ikut merender warna yang benar
    document.addEventListener('DOMContentLoaded', () => {
        updateIntensitasUI();
    });
</script>
@endsection