@extends('layouts.student')

@section('title', 'Makan Sehat & Bergizi')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">
        
        <form id="form-makan-sehat" action="{{ route('student.makan-sehat.store') }}" method="POST">
            @csrf

            <!-- HEADER BANNER UTAMA -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 p-6 sm:p-8 rounded-3xl shadow-xl text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="space-y-2 z-10 max-w-2xl">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-block px-3.5 py-1 bg-white/10 backdrop-blur-md text-blue-200 text-[10px] font-extrabold rounded-full uppercase tracking-widest border border-white/10">
                            KEBIASAAN 4
                        </span>
                        @if($journal)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 text-emerald-200 text-[10px] font-extrabold rounded-full border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Sudah Tersimpan Hari Ini
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Makan Sehat & Bergizi</h1>
                    <p class="text-blue-200/90 text-xs sm:text-sm italic font-medium">
                        "Penuhi asupan gizi seimbang untuk energi belajar dan daya tahan tubuh optimal."
                    </p>
                </div>

                <!-- Bagian Tanggal -->
                <div class="flex items-center gap-4 z-10 w-full md:w-auto justify-between md:justify-end">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 px-4 py-3 rounded-2xl flex items-center gap-3">
                        <span class="text-xl">📅</span>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-300">HARI INI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI & TENGAH (Menu Makan & Asupan) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. Sarapan Pagi -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="relative h-44 bg-gray-900">
                            <!-- Banner Card Sarapan -->
                            <img src="https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80" alt="Sarapan" class="w-full h-full object-cover opacity-50">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-950/90 via-gray-950/30 to-transparent z-10"></div>
                            <div class="absolute bottom-4 left-6 right-6 z-20 flex justify-between items-end">
                                <div>
                                    <span class="inline-block px-3 py-1 bg-emerald-500 text-slate-950 text-[10px] font-black rounded-full uppercase tracking-wider mb-1">Sarapan • Pagi Hari</span>
                                    <h3 class="text-xl sm:text-2xl font-black text-white">Menu Sarapan Pagi</h3>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <textarea id="input-sarapan" name="sarapan" rows="2" placeholder="Tuliskan menu sarapanmu (Contoh: Nasi merah, telur rebus, tumis bayam, dan pisang...)" 
                                class="w-full rounded-2xl border border-gray-200 p-4 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">{{ old('sarapan', optional($journal)->sarapan) }}</textarea>
                            
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold">
                                    <span>🌾</span> Karbohidrat
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                                    <span>🍳</span> Protein
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold">
                                    <span>🥗</span> Serat
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Makan Siang & Makan Malam -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Makan Siang -->
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-extrabold text-gray-900 text-base">Makan Siang</h3>
                                    <span class="text-xs font-bold text-gray-400">12:00 - 13:30</span>
                                </div>
                                <textarea id="input-makan-siang" name="makan_siang" rows="3" placeholder="Tuliskan menu makan siangmu (Contoh: Nasi, sayur asem, tempe goreng, ayam bakar...)" 
                                    class="w-full rounded-2xl border border-gray-200 p-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">{{ old('makan_siang', optional($journal)->makan_siang) }}</textarea>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold w-fit">
                                <span>💪</span> Lauk Pauk Bergizi
                            </div>
                        </div>

                        <!-- Makan Malam -->
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-extrabold text-gray-900 text-base">Makan Malam</h3>
                                    <span class="text-xs font-bold text-gray-400">18:00 - 19:30</span>
                                </div>
                                <textarea id="input-makan-malam" name="makan_malam" rows="3" placeholder="Tuliskan menu makan malammu (Contoh: Sup wortel kentang, tahu, dan apel...)" 
                                    class="w-full rounded-2xl border border-gray-200 p-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">{{ old('makan_malam', optional($journal)->makan_malam) }}</textarea>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold w-fit">
                                <span>🍃</span> Porsi Sedang & Sehat
                            </div>
                        </div>

                    </div>

                    <!-- Target Porsi Sayur, Buah & Konsumsi Air Putih -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div>
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <h3 class="font-extrabold text-gray-900 text-base">Target Porsi Sayur, Buah & Hidrasi Harian</h3>
                                <span class="text-[11px] font-bold text-blue-900 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">Standar Kemenkes RI: Isi Piringku</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Panduan ukuran porsi standar untuk memenuhi kebutuhan nutrisi dan metabolisme harian siswa.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <!-- 1. Porsi Sayur -->
                            <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex flex-col justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🥬</span>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sayuran</p>
                                            <p class="text-sm font-extrabold text-gray-900">Porsi Sayur</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-[11px] text-gray-600 font-medium">Satuan: <strong>1 Mangkok Sedang (±100g)</strong></p>
                                        <span class="inline-block text-[10px] text-emerald-800 font-bold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Target: 2 - 3 Porsi</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-1">
                                    <button type="button" onclick="decrementValue('porsi_sayur')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center transition cursor-pointer">-</button>
                                    <input type="number" id="porsi_sayur" name="porsi_sayur" min="0" max="20" value="{{ old('porsi_sayur', optional($journal)->porsi_sayur ?? 0) }}" 
                                        oninput="updateNutritionCards()" class="w-12 text-center text-base font-extrabold text-gray-900 border-none outline-none">
                                    <button type="button" onclick="incrementValue('porsi_sayur')" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center transition cursor-pointer">+</button>
                                </div>
                            </div>

                            <!-- 2. Porsi Buah -->
                            <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex flex-col justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🍊</span>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Buah-Buahan</p>
                                            <p class="text-sm font-extrabold text-gray-900">Porsi Buah</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-[11px] text-gray-600 font-medium">Satuan: <strong>1 Buah Sedang / 1 Potong</strong></p>
                                        <span class="inline-block text-[10px] text-emerald-800 font-bold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Target: 2 - 3 Porsi</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-1">
                                    <button type="button" onclick="decrementValue('porsi_buah')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center transition cursor-pointer">-</button>
                                    <input type="number" id="porsi_buah" name="porsi_buah" min="0" max="20" value="{{ old('porsi_buah', optional($journal)->porsi_buah ?? 0) }}" 
                                        oninput="updateNutritionCards()" class="w-12 text-center text-base font-extrabold text-gray-900 border-none outline-none">
                                    <button type="button" onclick="incrementValue('porsi_buah')" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center transition cursor-pointer">+</button>
                                </div>
                            </div>

                            <!-- 3. Air Putih (Gelas) -->
                            <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex flex-col justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💧</span>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Hidrasi</p>
                                            <p class="text-sm font-extrabold text-gray-900">Air Putih</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-[11px] text-gray-600 font-medium">Satuan: <strong>1 Gelas (±250 ml)</strong></p>
                                        <span class="inline-block text-[10px] text-blue-800 font-bold bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">Target: min 8 Gelas (2L)</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-1">
                                    <button type="button" onclick="decrementValue('air_putih')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center transition cursor-pointer">-</button>
                                    <input type="number" id="air_putih" name="air_putih" min="0" max="30" value="{{ old('air_putih', optional($journal)->air_putih ?? 0) }}" 
                                        oninput="updateNutritionCards()" class="w-12 text-center text-base font-extrabold text-gray-900 border-none outline-none">
                                    <button type="button" onclick="incrementValue('air_putih')" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 font-bold flex items-center justify-center transition cursor-pointer">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan Bawah -->
                    <div class="flex items-center justify-end pt-2">
                        <button type="submit" class="w-full sm:w-auto min-h-[48px] bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-black px-8 py-3.5 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                            <span>💾</span> Simpan Jurnal Makan Sehat
                        </button>
                    </div>

                </div>

                <!-- KOLOM KANAN (Sidebar Widget: Evaluasi Gizi & Rating) -->
                <div class="space-y-6">
                    
                    <!-- KARTU STATUS EVALUASI GIZI (HASIL: MAKANAN SEHAT APA NGGAK) -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">HASIL EVALUASI</p>
                                <h3 class="font-extrabold text-gray-900 text-base">Status Makanan Sehat</h3>
                            </div>
                            <span id="evaluasi-icon" class="text-2xl">{{ $evaluasiGizi['icon'] ?? '⚪' }}</span>
                        </div>

                        <!-- Badge Status -->
                        <div id="evaluasi-badge-box" class="p-4 rounded-2xl border transition-all duration-300 {{ $evaluasiGizi['badge'] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                            <p class="text-[10px] font-black uppercase tracking-wider opacity-75">Kategori Gizi</p>
                            <h4 id="evaluasi-status-text" class="text-base font-extrabold mt-0.5 tracking-tight">
                                {{ $evaluasiGizi['status'] ?? 'Belum Ada Catatan' }}
                            </h4>
                        </div>

                        <!-- Penjelasan & Rekomendasi -->
                        <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100 space-y-2">
                            <p class="text-xs font-bold text-gray-700">Analisis Gizi Harian:</p>
                            <p id="evaluasi-advice-text" class="text-xs text-gray-600 leading-relaxed font-normal">
                                {{ $evaluasiGizi['advice'] ?? 'Yuk catat menu sarapan, makan siang, atau makan malammu beserta porsi sayur, buah, dan air putih.' }}
                            </p>
                        </div>

                        <!-- Parameter Ceklis Standar -->
                        <div class="space-y-2 pt-2 border-t border-gray-100 text-xs">
                            <div class="flex items-center justify-between text-gray-600 font-medium">
                                <span>Serat & Buah (min. 5 porsi):</span>
                                <span id="checklist-sayurbuah" class="font-bold text-gray-900">-</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-600 font-medium">
                                <span>Hidrasi (min. 8 gelas):</span>
                                <span id="checklist-air" class="font-bold text-gray-900">-</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-600 font-medium">
                                <span>Makanan Utama:</span>
                                <span id="checklist-makan" class="font-bold text-gray-900">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Nutrisi Card (Dinamis Berdasarkan Data) -->
                    <div class="bg-blue-900 text-white p-6 rounded-3xl shadow-xl space-y-5">
                        <div class="flex justify-between items-center">
                            <h3 class="font-extrabold text-base">Persentase Target Nutrisi</h3>
                            <span class="text-emerald-400 text-xl">🛡️</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Serat & Vitamin -->
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="text-blue-200">Serat & Vitamin (Sayur + Buah)</span>
                                    <span id="label-serat">{{ $seratVitaminPct ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-blue-950 h-2.5 rounded-full overflow-hidden">
                                    <div id="bar-serat" class="bg-emerald-400 h-full rounded-full transition-all duration-300" style="width: {{ $seratVitaminPct ?? 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Hidrasi -->
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="text-blue-200">Hidrasi (Target 8 Gelas)</span>
                                    <span id="label-hidrasi">{{ $hidrasiPct ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-blue-950 h-2.5 rounded-full overflow-hidden">
                                    <div id="bar-hidrasi" class="bg-cyan-400 h-full rounded-full transition-all duration-300" style="width: {{ $hidrasiPct ?? 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Protein & Makanan Utama -->
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="text-blue-200">Kelengkapan Makanan Utama</span>
                                    <span id="label-protein">{{ $proteinPct ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-blue-950 h-2.5 rounded-full overflow-hidden">
                                    <div id="bar-protein" class="bg-amber-400 h-full rounded-full transition-all duration-300" style="width: {{ $proteinPct ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <p id="nutrition-tip" class="text-xs text-blue-200/90 leading-relaxed pt-3 border-t border-blue-800">
                            @if(($seratVitaminPct ?? 0) >= 100 && ($hidrasiPct ?? 0) >= 100)
                                Luar biasa! Target nutrisi serat, vitamin, dan hidrasi harianmu sudah terpenuhi dengan sangat baik.
                            @elseif($journal)
                                Terus lengkapi porsi sayur, buah, dan air putih untuk mencapai target nutrisi 100% hari ini.
                            @else
                                Belum ada data nutrisi yang dimasukkan hari ini. Yuk catat makanan sehatmu!
                            @endif
                        </p>
                    </div>

                    <!-- Cemilan Sehat Card -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-extrabold text-gray-900 text-sm">Cemilan Sehat</h3>
                            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-md">Opsional</span>
                        </div>
                        <textarea name="cemilan" rows="3" placeholder="Tuliskan cemilan sehat hari ini (Contoh: Kacang almond, yogurt plain, jus jeruk tanpa gula...)" 
                            class="w-full rounded-2xl border border-gray-200 p-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50/30 outline-none transition">{{ old('cemilan', optional($journal)->cemilan) }}</textarea>
                    </div>

                </div>

            </div>
        </form>

</div>

<script>
    function incrementValue(id) {
        const input = document.getElementById(id);
        if (!input) return;
        const current = parseInt(input.value) || 0;
        input.value = current + 1;
        updateNutritionCards();
    }

    function decrementValue(id) {
        const input = document.getElementById(id);
        if (!input) return;
        const current = parseInt(input.value) || 0;
        if (current > 0) {
            input.value = current - 1;
            updateNutritionCards();
        }
    }

    function updateNutritionCards() {
        const sayur = parseInt(document.getElementById('porsi_sayur')?.value) || 0;
        const buah = parseInt(document.getElementById('porsi_buah')?.value) || 0;
        const air = parseInt(document.getElementById('air_putih')?.value) || 0;

        const sarapan = document.getElementById('input-sarapan')?.value.trim() || '';
        const siang = document.getElementById('input-makan-siang')?.value.trim() || '';
        const malam = document.getElementById('input-makan-malam')?.value.trim() || '';

        const totalSayurBuah = sayur + buah;
        const mainMealsCount = (sarapan.length > 0 ? 1 : 0) + (siang.length > 0 ? 1 : 0) + (malam.length > 0 ? 1 : 0);

        // Target: 5 porsi sayur + buah
        const seratPct = Math.min(Math.round((totalSayurBuah / 5) * 100), 100);
        // Target: 8 gelas air putih
        const hidrasiPct = Math.min(Math.round((air / 8) * 100), 100);
        // Makanan utama
        const hasMeal = (mainMealsCount > 0);
        const proteinPct = hasMeal ? Math.min(Math.round((mainMealsCount / 3) * 100), 100) : 0;

        // Update progress bars
        const barSerat = document.getElementById('bar-serat');
        const labelSerat = document.getElementById('label-serat');
        if (barSerat && labelSerat) {
            barSerat.style.width = seratPct + '%';
            labelSerat.textContent = seratPct + '%';
        }

        const barHidrasi = document.getElementById('bar-hidrasi');
        const labelHidrasi = document.getElementById('label-hidrasi');
        if (barHidrasi && labelHidrasi) {
            barHidrasi.style.width = hidrasiPct + '%';
            labelHidrasi.textContent = hidrasiPct + '%';
        }

        const barProtein = document.getElementById('bar-protein');
        const labelProtein = document.getElementById('label-protein');
        if (barProtein && labelProtein) {
            barProtein.style.width = proteinPct + '%';
            labelProtein.textContent = proteinPct + '%';
        }

        // Checklist realtime
        const elSayurBuah = document.getElementById('checklist-sayurbuah');
        if (elSayurBuah) {
            elSayurBuah.textContent = `${totalSayurBuah} / 5 Porsi ${totalSayurBuah >= 5 ? '✅' : '⚠️'}`;
            elSayurBuah.className = `font-bold ${totalSayurBuah >= 5 ? 'text-emerald-600' : 'text-amber-600'}`;
        }

        const elAir = document.getElementById('checklist-air');
        if (elAir) {
            elAir.textContent = `${air} / 8 Gelas ${air >= 8 ? '✅' : '⚠️'}`;
            elAir.className = `font-bold ${air >= 8 ? 'text-emerald-600' : 'text-amber-600'}`;
        }

        const elMakan = document.getElementById('checklist-makan');
        if (elMakan) {
            elMakan.textContent = `${mainMealsCount} / 3 Waktu ${mainMealsCount >= 2 ? '✅' : '⚠️'}`;
            elMakan.className = `font-bold ${mainMealsCount >= 2 ? 'text-emerald-600' : 'text-amber-600'}`;
        }

        // Status Evaluasi Gizi: Makanan Sehat / Belum Seimbang
        const badgeBox = document.getElementById('evaluasi-badge-box');
        const statusText = document.getElementById('evaluasi-status-text');
        const iconEl = document.getElementById('evaluasi-icon');
        const adviceEl = document.getElementById('evaluasi-advice-text');

        if (badgeBox && statusText && iconEl && adviceEl) {
            if (mainMealsCount === 0 && totalSayurBuah === 0 && air === 0) {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-gray-100 text-gray-700 border-gray-200';
                statusText.textContent = 'Belum Ada Catatan';
                iconEl.textContent = '⚪';
                adviceEl.textContent = 'Yuk catat menu sarapan, makan siang, atau makan malammu beserta porsi sayur, buah, dan air putih.';
            } else if (totalSayurBuah >= 5 && air >= 8 && mainMealsCount >= 2) {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-emerald-50 text-emerald-800 border-emerald-200';
                statusText.textContent = 'Sangat Sehat & Bergizi Seimbang';
                iconEl.textContent = '🟢';
                adviceEl.textContent = 'Luar biasa! Asupan serat, vitamin, mineral, lauk bergizi, dan hidrasi harianmu telah memenuhi standar gizi seimbang.';
            } else if (totalSayurBuah >= 2 && air >= 5 && mainMealsCount >= 1) {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-amber-50 text-amber-800 border-amber-200';
                statusText.textContent = 'Cukup Sehat (Perlu Peningkatan)';
                iconEl.textContent = '🟡';
                adviceEl.textContent = 'Pola makan sudah cukup baik! Tingkatkan porsi sayur & buah hingga minimal 5 porsi dan minum air putih hingga 8 gelas agar optimal.';
            } else {
                badgeBox.className = 'p-4 rounded-2xl border transition-all duration-300 bg-rose-50 text-rose-800 border-rose-200';
                statusText.textContent = 'Kurang Sehat / Belum Seimbang';
                iconEl.textContent = '🔴';
                adviceEl.textContent = 'Porsi serat, vitamin, atau hidrasi masih kurang. Lengkapi menu makanan utama dengan sayuran, buah-buahan, dan cukupi air putih.';
            }
        }

        const tip = document.getElementById('nutrition-tip');
        if (tip) {
            if (seratPct >= 100 && hidrasiPct >= 100) {
                tip.textContent = "Luar biasa! Target nutrisi serat, vitamin, dan hidrasi harianmu sudah terpenuhi dengan sangat baik.";
            } else if (sayur > 0 || buah > 0 || air > 0 || hasMeal) {
                tip.textContent = "Bagus! Terus lengkapi porsi sayur, buah, dan air putih untuk mencapai target nutrisi 100% hari ini.";
            } else {
                tip.textContent = "Belum ada data nutrisi yang dimasukkan hari ini. Yuk catat makanan sehatmu!";
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        ['input-sarapan', 'input-makan-siang', 'input-makan-malam'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateNutritionCards);
            }
        });
        // Inisialisasi awal saat halaman dibuka
        updateNutritionCards();
    });
</script>
@endsection