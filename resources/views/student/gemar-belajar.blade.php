@extends('layouts.student')

@section('title', 'Gemar Belajar & Literasi')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">
        
        <!-- Notifikasi Pesan Sukses -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm text-sm font-medium flex items-center gap-3" role="alert">
                <span class="text-xl">✨</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form id="form-gemar-belajar" action="{{ route('student.gemar-belajar.store') }}" method="POST">
            @csrf

            <!-- HEADER BANNER UTAMA -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 p-6 sm:p-8 rounded-3xl shadow-xl text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="space-y-2 z-10 max-w-2xl">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-block px-3.5 py-1 bg-white/10 backdrop-blur-md text-blue-200 text-[10px] font-extrabold rounded-full uppercase tracking-widest border border-white/10">
                            KEBIASAAN 5
                        </span>
                        @if($journal)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 text-emerald-200 text-[10px] font-extrabold rounded-full border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Sudah Tersimpan Hari Ini
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Gemar Belajar & Literasi</h1>
                    <p class="text-blue-200/90 text-xs sm:text-sm italic font-medium">
                        "Luangkan minimal 30 menit setiap hari untuk membaca buku, mengulang pelajaran, atau mengasah keterampilan baru."
                    </p>
                </div>

                <!-- Bagian Tanggal & Tombol Aksi -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full md:w-auto shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl flex items-center gap-3">
                        <span class="text-lg sm:text-xl shrink-0">📅</span>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-300">HARI INI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white truncate">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>

                    @if($journal)
                        <button type="button" onclick="if(confirm('Yakin ingin mengosongkan jurnal gemar belajar hari ini?')) { document.getElementById('delete-journal-form').submit(); }" 
                            class="w-full sm:w-auto min-h-[44px] bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 border border-rose-500/30 font-extrabold px-4 py-2.5 rounded-xl sm:rounded-2xl flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                            <span>🗑️</span> Kosongkan
                        </button>
                    @endif
                </div>
            </div>

            <!-- GRID UTAMA HALAMAN -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI & TENGAH (Materi Baru, Literasi, Keterampilan, Catatan) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. MATERI BARU -->
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-blue-900 font-extrabold">
                                        <span class="text-lg">💡</span>
                                        <h3 class="text-sm tracking-wide uppercase">Materi Baru</h3>
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-800 bg-blue-50 px-2 py-0.5 rounded-md">Pelajaran Sekolah</span>
                                </div>
                                <p class="text-xs text-gray-500 font-medium">Apa konsep baru atau mata pelajaran yang kamu pelajari hari ini?</p>
                                
                                <textarea id="input-materi" name="materi_belajar" rows="4" placeholder="Contoh: Belajar rumus Pythagoras Matematika dan menghafal kosa kata Bahasa Inggris baru..." 
                                    class="w-full rounded-2xl border border-gray-200 p-4 text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50/30 outline-none transition">{{ old('materi_belajar', optional($journal)->materi_belajar) }}</textarea>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-bold w-fit">
                                <span>📚</span> Pemahaman Konsep
                            </div>
                        </div>

                        <!-- 2. BUKU & LITERASI -->
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-emerald-800 font-extrabold">
                                        <span class="text-lg">📖</span>
                                        <h3 class="text-sm tracking-wide uppercase">Buku & Literasi</h3>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md">Membaca Buku</span>
                                </div>
                                <p class="text-xs text-gray-500 font-medium">Judul buku, artikel, atau ensiklopedia yang kamu baca hari ini:</p>
                                
                                <input type="text" id="input-buku" name="judul_buku" value="{{ old('judul_buku', optional($journal)->judul_buku) }}" placeholder="Contoh: Laskar Pelangi / Ensiklopedia Sains" 
                                    class="w-full rounded-2xl border border-gray-200 p-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">

                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Halaman Terakhir</label>
                                        <input type="text" name="halaman_terakhir" value="{{ old('halaman_terakhir', optional($journal)->halaman_terakhir) }}" placeholder="Hal. 45" 
                                            class="w-full rounded-2xl border border-gray-200 p-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Durasi Belajar</label>
                                        <div class="relative">
                                            <input type="number" id="input-durasi" name="durasi_belajar" min="5" max="300" step="5" 
                                                value="{{ old('durasi_belajar', optional($journal)->durasi_belajar ?? 30) }}" 
                                                oninput="updateDurationBadge()"
                                                class="w-full rounded-2xl border border-gray-200 p-3 pr-16 text-sm font-bold text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">
                                            <span class="absolute right-3 top-3.5 text-xs text-gray-500 font-bold">menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Shortcut Preset Menit -->
                            <div class="flex items-center gap-1.5 pt-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Pilih Cepat:</span>
                                <button type="button" onclick="setDuration(15)" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">15m</button>
                                <button type="button" onclick="setDuration(30)" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 transition cursor-pointer">30m (Target)</button>
                                <button type="button" onclick="setDuration(45)" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">45m</button>
                                <button type="button" onclick="setDuration(60)" class="px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 transition cursor-pointer">60m</button>
                            </div>
                        </div>

                    </div>

                    <!-- 3. LATIHAN KETERAMPILAN -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2 text-gray-900 font-extrabold">
                                <span class="text-lg">🎯</span>
                                <h3 class="text-sm tracking-wide uppercase">Latihan Keterampilan & Hobi Positif</h3>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-[10px] font-extrabold rounded-full border border-emerald-200">Karakter & Kreativitas</span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Pilih bidang keterampilan yang kamu latih atau asah hari ini (opsional):</p>

                        <!-- Pilihan Kategori Keterampilan -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-1">
                            @php
                                $skills = [
                                    ['id' => 'musik', 'label' => 'Musik & Suara', 'icon' => '🎵', 'desc' => 'Gitar, Piano, Vokal'],
                                    ['id' => 'seni', 'label' => 'Seni Melukis', 'icon' => '🎨', 'desc' => 'Gambar, Desain, Kaligrafi'],
                                    ['id' => 'teknologi', 'label' => 'Teknologi & Coding', 'icon' => '💻', 'desc' => 'Komputer, Logika AI'],
                                    ['id' => 'bahasa', 'label' => 'Bahasa Asing', 'icon' => '🌐', 'desc' => 'Inggris, Arab, Mandarin'],
                                ];
                                $selectedSkill = old('kategori_keterampilan', optional($journal)->kategori_keterampilan);
                            @endphp

                            @foreach($skills as $skill)
                                <label class="cursor-pointer">
                                    <input type="radio" name="kategori_keterampilan" value="{{ $skill['id'] }}" class="peer sr-only" 
                                        {{ ($selectedSkill == $skill['id']) ? 'checked' : '' }}>
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50/50 hover:bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50/30 peer-checked:text-blue-900 transition flex flex-col items-center justify-center text-center gap-1.5 h-32 shadow-sm">
                                        <span class="text-3xl">{{ $skill['icon'] }}</span>
                                        <span class="text-xs font-bold text-gray-800 peer-checked:text-blue-900">{{ $skill['label'] }}</span>
                                        <span class="text-[10px] text-gray-500 font-medium">{{ $skill['desc'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 4. CATATAN & RANGKUMAN BELAJAR -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2 text-gray-900 font-extrabold">
                                <span class="text-lg">📝</span>
                                <h3 class="text-sm tracking-wide uppercase">Catatan & Rangkuman Belajar</h3>
                            </div>
                            <span class="text-xs font-bold text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-200">Refleksi Belajar</span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Tuliskan ringkasan penting, hafalan, atau catatan yang ingin kamu ingat kembali:</p>
                        
                        <textarea name="catatan" rows="3" placeholder="Contoh: Hari ini memahami cara kerja fotosintesis pada tumbuhan dan menyelesaikan tugas halaman 42..." 
                            class="w-full rounded-2xl border border-gray-200 p-4 text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50/30 outline-none transition">{{ old('catatan', optional($journal)->catatan) }}</textarea>
                    </div>

                    <!-- AKTIVITAS MINGGUAN (Dinamis dari Database) -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-sm tracking-wide uppercase">Aktivitas Mingguan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Grafik durasi belajar per hari dalam minggu ini (Senin - Minggu)</p>
                            </div>
                            <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Target Harian: &ge; 30 Menit</span>
                        </div>

                        <div class="h-40 flex items-end justify-between gap-2 pt-4 px-2 border-b border-gray-100">
                            @php
                                $daysMapping = [
                                    1 => 'Sen',
                                    2 => 'Sel',
                                    3 => 'Rab',
                                    4 => 'Kam',
                                    5 => 'Jum',
                                    6 => 'Sab',
                                    7 => 'Min',
                                ];
                            @endphp
                            
                            @foreach($daysMapping as $dayIndex => $dayLabel)
                                @php
                                    $chartInfo = $weeklyChartData[$dayIndex] ?? ['active' => false, 'height' => 'h-2', 'durasi' => 0];
                                    $isMeetTarget = ($chartInfo['durasi'] >= 30);
                                @endphp
                                <div class="flex flex-col items-center gap-2 flex-1 group relative">
                                    <!-- Tooltip Durasi -->
                                    <div class="absolute -top-7 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-bold px-2 py-0.5 rounded pointer-events-none whitespace-nowrap z-20">
                                        {{ $chartInfo['durasi'] }} menit
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-t-xl relative h-32 flex items-end justify-center overflow-hidden">
                                        <div class="w-full {{ $chartInfo['active'] ? ($isMeetTarget ? 'bg-emerald-500 ' : 'bg-amber-400 ') . $chartInfo['height'] : 'bg-gray-200 h-2' }} rounded-t-xl transition-all duration-500"></div>
                                    </div>
                                    <span class="text-[11px] font-bold {{ $chartInfo['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $dayLabel }}</span>
                                    <span class="text-[9px] font-bold {{ $isMeetTarget ? 'text-emerald-700' : 'text-gray-400' }}">{{ $chartInfo['durasi'] > 0 ? $chartInfo['durasi'] . 'm' : '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Simpan Bawah -->
                    <div class="flex items-center justify-end pt-2">
                        <button type="submit" class="w-full sm:w-auto min-h-[48px] bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-black px-8 py-3.5 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                            <span>💾</span> Simpan Jurnal Gemar Belajar
                        </button>
                    </div>

                </div>

                <!-- KOLOM KANAN (Status Target, Pohon Pengetahuan & Level Literasi) -->
                <div class="space-y-6">
                    
                    <!-- KARTU STATUS TARGET BELAJAR HARI INI -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">EVALUASI HARIAN</p>
                                <h3 class="font-extrabold text-gray-900 text-base">Target Gemar Belajar</h3>
                            </div>
                            <span id="target-icon" class="text-2xl">🎯</span>
                        </div>

                        <!-- Badge Box Dinamis -->
                        <div id="target-badge-box" class="p-4 rounded-2xl border transition-all duration-300 bg-emerald-50 text-emerald-800 border-emerald-200">
                            <p class="text-[10px] font-black uppercase tracking-wider opacity-75">Status Target</p>
                            <h4 id="target-status-text" class="text-base font-extrabold mt-0.5 tracking-tight">
                                {{ (optional($journal)->durasi_belajar ?? 30) >= 30 ? 'Target Terpenuhi (>=30 Menit)' : 'Perlu Peningkatan (<30 Menit)' }}
                            </h4>
                        </div>

                        <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100 space-y-1.5 text-xs">
                            <p class="font-bold text-gray-800">Standar Kebiasaan Positif:</p>
                            <p id="target-advice-text" class="text-gray-600 leading-relaxed font-normal">
                                Belajar teratur 30 menit setiap hari lebih efektif meningkatkan daya ingat dan pemahaman jangka panjang dibanding belajar terburu-buru sebelum ujian.
                            </p>
                        </div>
                    </div>

                    <!-- Pohon Pengetahuan Card -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 flex flex-col items-center text-center relative overflow-hidden">
                        
                        <div class="w-full flex justify-between items-start">
                            <div class="text-left">
                                <h3 class="font-extrabold text-gray-900 text-base">Pohon Pengetahuan</h3>
                                <p class="text-[11px] text-gray-500 font-medium">Semakin rajin belajar, semakin lebat daun pohonmu.</p>
                            </div>
                            <div class="bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-2xl text-center flex-shrink-0">
                                <span class="block text-xs font-black text-emerald-800">{{ $totalSesiBelajar ?? 0 }}</span>
                                <span class="block text-[8px] font-extrabold text-emerald-700 uppercase">SESI SELESAI</span>
                            </div>
                        </div>

                        <!-- Ilustrasi Pohon Estetik Sesuai Desain Literasi -->
                        <div class="py-4 relative w-full flex justify-center items-center">
                            <div class="w-44 h-44 relative flex items-center justify-center">
                                <svg class="w-36 h-36" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Tanah / Dasar -->
                                    <path d="M20 90C35 88 65 88 80 90" stroke="#9CA3AF" stroke-width="3" stroke-linecap="round"/>
                                    <!-- Batang Utama -->
                                    <path d="M50 88V45" stroke="#78350F" stroke-width="6" stroke-linecap="round"/>
                                    <!-- Cabang-Cabang -->
                                    <path d="M50 65L32 50" stroke="#78350F" stroke-width="4.5" stroke-linecap="round"/>
                                    <path d="M50 55L68 42" stroke="#78350F" stroke-width="4.5" stroke-linecap="round"/>
                                    <path d="M50 45L40 32" stroke="#78350F" stroke-width="3.5" stroke-linecap="round"/>
                                    <path d="M50 45L60 30" stroke="#78350F" stroke-width="3.5" stroke-linecap="round"/>

                                    <!-- Daun-daun bertumbuh sesuai sesi belajar -->
                                    <!-- Level 1 (Dasar) -->
                                    <circle cx="32" cy="50" r="{{ ($totalSesiBelajar >= 1) ? 6 : 3 }}" fill="#10B981" fill-opacity="{{ ($totalSesiBelajar >= 1) ? '1' : '0.3' }}"/>
                                    <circle cx="68" cy="42" r="{{ ($totalSesiBelajar >= 2) ? 6 : 3 }}" fill="#059669" fill-opacity="{{ ($totalSesiBelajar >= 2) ? '1' : '0.3' }}"/>
                                    <circle cx="40" cy="32" r="{{ ($totalSesiBelajar >= 3) ? 7 : 4 }}" fill="#34D399" fill-opacity="{{ ($totalSesiBelajar >= 3) ? '1' : '0.3' }}"/>
                                    <circle cx="60" cy="30" r="{{ ($totalSesiBelajar >= 4) ? 7 : 4 }}" fill="#10B981" fill-opacity="{{ ($totalSesiBelajar >= 4) ? '1' : '0.3' }}"/>
                                    <circle cx="50" cy="22" r="{{ ($totalSesiBelajar >= 5) ? 9 : 4 }}" fill="#047857" fill-opacity="{{ ($totalSesiBelajar >= 5) ? '1' : '0.3' }}"/>

                                    <!-- Level 2 (Rajin Membaca - 5+ Sesi) -->
                                    <circle cx="26" cy="42" r="{{ ($totalSesiBelajar >= 6) ? 7 : 0 }}" fill="#6EE7B7"/>
                                    <circle cx="74" cy="35" r="{{ ($totalSesiBelajar >= 8) ? 8 : 0 }}" fill="#10B981"/>
                                    <circle cx="48" cy="14" r="{{ ($totalSesiBelajar >= 10) ? 9 : 0 }}" fill="#059669"/>

                                    <!-- Level 3 (Kutu Buku - 10+ Sesi) - Buah Pengetahuan Emas -->
                                    @if(($totalSesiBelajar ?? 0) >= 10)
                                        <circle cx="35" cy="44" r="3.5" fill="#F59E0B"/>
                                        <circle cx="65" cy="36" r="3.5" fill="#F59E0B"/>
                                    @endif
                                    @if(($totalSesiBelajar ?? 0) >= 20)
                                        <circle cx="50" cy="18" r="4" fill="#F59E0B"/>
                                    @endif
                                </svg>
                            </div>
                        </div>

                        <!-- Progress Bar Level -->
                        <div class="w-full space-y-1.5 px-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-gray-600">Level: <strong class="text-blue-900">{{ $levelBuku ?? 'Pemula' }}</strong></span>
                                <span class="text-emerald-700 font-extrabold">{{ $totalSesiBelajar ?? 0 }} / {{ $nextLevelTarget ?? 5 }} Sesi</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $levelProgress ?? 0 }}%"></div>
                            </div>
                        </div>

                        <!-- 3 Kartu Statistik di Bawah Pohon -->
                        <div class="grid grid-cols-3 gap-2 w-full pt-4 border-t border-gray-100 text-center">
                            <div class="bg-gray-50/70 p-2.5 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-bold text-gray-500 uppercase">Streak</p>
                                <p class="text-xs font-black text-blue-900 mt-0.5">{{ $streakBelajar ?? 0 }} Hari</p>
                            </div>
                            <div class="bg-gray-50/70 p-2.5 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-bold text-gray-500 uppercase">Total Waktu</p>
                                <p class="text-xs font-black text-emerald-700 mt-0.5 truncate" title="{{ $totalMenitBelajar ?? 0 }} Menit">{{ $totalMenitBelajar ?? 0 }} mnt</p>
                            </div>
                            <div class="bg-gray-50/70 p-2.5 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-bold text-gray-500 uppercase">Poin</p>
                                <p class="text-xs font-black text-amber-700 mt-0.5">{{ $totalPoinBelajar ?? 0 }}</p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </form>

        <!-- Form tersembunyi untuk reset jurnal hari ini -->
        @if($journal)
            <form id="delete-journal-form" action="{{ route('student.gemar-belajar.destroy') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif

</div>

<script>
    function setDuration(val) {
        const input = document.getElementById('input-durasi');
        if (input) {
            input.value = val;
            updateDurationBadge();
        }
    }

    function updateDurationBadge() {
        const input = document.getElementById('input-durasi');
        const durasi = parseInt(input?.value) || 0;

        const badgeBox = document.getElementById('target-badge-box');
        const statusText = document.getElementById('target-status-text');
        const iconEl = document.getElementById('target-icon');
        const adviceText = document.getElementById('target-advice-text');

        if (badgeBox && statusText && iconEl && adviceText) {
            if (durasi >= 30) {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-emerald-50 text-emerald-800 border-emerald-200';
                statusText.textContent = `Target Terpenuhi (${durasi} Menit)`;
                iconEl.textContent = '🌟';
                adviceText.textContent = 'Sangat baik! Durasi belajar minimal 30 menit sehari telah terpenuhi. Tetap konsisten untuk mempertahankan kebiasaan belajar efektif.';
            } else if (durasi > 0) {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-amber-50 text-amber-800 border-amber-200';
                statusText.textContent = `Perlu Peningkatan (${durasi} Menit)`;
                iconEl.textContent = '⏱️';
                adviceText.textContent = 'Waktu belajar masih di bawah 30 menit. Coba tambah durasi sedikit lagi agar pemahaman konsepmu semakin matang dan mendalam.';
            } else {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-gray-100 text-gray-700 border-gray-200';
                statusText.textContent = 'Belum Ada Durasi';
                iconEl.textContent = '⚪';
                adviceText.textContent = 'Tentukan durasi belajarmu hari ini (minimal 30 menit) untuk melatih fokus dan daya serap materi.';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateDurationBadge();
    });
</script>
@endsection
