@extends('layouts.student')

@section('title', 'Aktif Bermasyarakat')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">
        
        <!-- Notifikasi Pesan Sukses -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm text-sm font-medium flex items-center gap-3" role="alert">
                <span class="text-xl">✨</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-sm text-xs font-semibold space-y-1">
                <p class="font-bold">Mohon periksa kembali form Anda:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HEADER BANNER UTAMA -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 p-6 sm:p-8 rounded-3xl shadow-xl text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="space-y-2 z-10 max-w-2xl">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-block px-3.5 py-1 bg-white/10 backdrop-blur-md text-blue-200 text-[10px] font-extrabold rounded-full uppercase tracking-widest border border-white/10">
                        KEBIASAAN 6
                    </span>
                    @if($todayActivity)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 text-emerald-200 text-[10px] font-extrabold rounded-full border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Sudah Ada Catatan Hari Ini
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Aktif Bermasyarakat</h1>
                <p class="text-blue-200/90 text-xs sm:text-sm italic font-medium">
                    "Tebar kepedulian di sekitarmu: satu aksi kebaikan kecil adalah fondasi karakter bangsa yang tangguh dan bermartabat."
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

                <a href="#form-aktivitas" class="w-full sm:w-auto min-h-[44px] bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-extrabold px-6 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 transition text-xs sm:text-sm cursor-pointer">
                    <span>✍️</span> Catat Kebaikan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- KOLOM KIRI & TENGAH (Form Input Catatan Sosial & Riwayat) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Formulir Catatan Kegiatan Sosial -->
                <div id="form-aktivitas" class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-lg">Catat Aksi Sosialmu Hari Ini</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Dokumentasikan perbuatan baik yang kamu lakukan di rumah, sekolah, maupun lingkungan masyarakat.</p>
                        </div>
                        <span class="text-2xl">🤝</span>
                    </div>

                    <form action="{{ route('student.aktif-masyarakat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <!-- Pilihan Jenis Kegiatan (Interactive Radio Cards) -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                1. Pilih Kategori Aksi Kebaikan
                            </label>
                            
                            @php
                                $categories = [
                                    ['id' => 'Membantu Tetangga', 'label' => 'Membantu Tetangga', 'icon' => '🏡', 'desc' => 'Menolong keperluan warga'],
                                    ['id' => 'Membantu Menyeberang', 'label' => 'Membantu Menyeberang', 'icon' => '🚸', 'desc' => 'Menolong lansia / anak'],
                                    ['id' => 'Kerja Bakti Masjid', 'label' => 'Kerja Bakti Tempat Ibadah', 'icon' => '🕌', 'desc' => 'Membersihkan masjid/musholla'],
                                    ['id' => 'Gotong Royong', 'label' => 'Gotong Royong Lingkungan', 'icon' => '🧹', 'desc' => 'Bersih selokan / pos ronda'],
                                    ['id' => 'Berbagi Makanan', 'label' => 'Sedekah & Berbagi', 'icon' => '🍱', 'desc' => 'Membagi makanan / rezeki'],
                                    ['id' => 'Lainnya', 'label' => 'Aksi Kebaikan Lainnya', 'icon' => '✨', 'desc' => 'Tuliskan kegiatanmu sendiri'],
                                ];
                                $selectedCategory = old('jenis_kegiatan', 'Membantu Tetangga');
                            @endphp

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($categories as $cat)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_kegiatan" value="{{ $cat['id'] }}" 
                                            class="peer sr-only" {{ ($selectedCategory == $cat['id']) ? 'checked' : '' }}
                                            onchange="toggleLainnya(this.value)">
                                        <div class="p-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 hover:bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50/30 peer-checked:text-blue-900 transition flex flex-col items-center justify-center text-center gap-1.5 h-28 shadow-sm">
                                            <span class="text-2xl">{{ $cat['icon'] }}</span>
                                            <span class="text-xs font-bold text-gray-800 peer-checked:text-blue-900 line-clamp-1">{{ $cat['label'] }}</span>
                                            <span class="text-[10px] text-gray-500 font-medium line-clamp-1">{{ $cat['desc'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Input Teks Tambahan Jika Memilih Kategori Lainnya -->
                        <div id="wrapper-lainnya" class="{{ ($selectedCategory == 'Lainnya') ? '' : 'hidden' }} space-y-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Sebutkan Jenis Kebaikan Lainnya
                            </label>
                            <input type="text" id="input-lainnya" name="jenis_kegiatan_lainnya" value="{{ old('jenis_kegiatan_lainnya') }}" 
                                placeholder="Contoh: Mengumpulkan donasi buku untuk adik kelas..." 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50/30 outline-none transition">
                        </div>

                        <!-- Deskripsi Aksi Sosial -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                2. Ceritakan Kebaikan yang Kamu Lakukan Hari Ini
                            </label>
                            <textarea name="deskripsi" rows="3" placeholder="Ceritakan secara ringkas apa yang kamu lakukan, kepada siapa, dan bagaimana perasaanmu setelah membantu..." 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50/30 outline-none transition" required>{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Lokasi Kegiatan -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                3. Lokasi Kegiatan
                            </label>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <input type="text" id="input-lokasi" name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" 
                                        placeholder="Contoh: RT 02 / RW 04 Kelurahan Sukabumi / Masjid Al-Hikmah" 
                                        class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50/30 outline-none transition">
                                    <span class="absolute left-3.5 top-3.5 text-gray-400">📍</span>
                                </div>
                                <button type="button" id="btn-gps" onclick="detectGPS()" 
                                    class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-bold text-xs flex items-center gap-1.5 transition whitespace-nowrap cursor-pointer">
                                    <span>📡</span> Deteksi GPS
                                </button>
                            </div>
                        </div>

                        <!-- Unggah Foto Bukti & Preview -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                4. Foto Bukti Kegiatan (Opsional)
                            </label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-4 p-4 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50">
                                <div id="preview-container" class="w-20 h-20 rounded-xl bg-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                                    <span id="preview-placeholder" class="text-2xl text-gray-400">📷</span>
                                    <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                </div>
                                <div class="flex-1 text-center sm:text-left space-y-1">
                                    <p class="text-xs font-bold text-gray-800">Unggah foto dokumentasi kegiatan</p>
                                    <p class="text-[11px] text-gray-500">Mendukung format JPG, PNG, atau WEBP (Maksimal 5MB).</p>
                                    <input type="file" name="foto_kegiatan" id="foto_kegiatan" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    <button type="button" onclick="document.getElementById('foto_kegiatan').click()" 
                                        class="mt-1 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition cursor-pointer">
                                        <span>📁</span> Pilih Berkas Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-950 hover:bg-blue-900 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-blue-950/20 flex items-center justify-center gap-2 transition cursor-pointer">
                                <span>💾</span> Simpan Aksi Sosial
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Aktivitas Terakhir Siswa -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base">Riwayat Aksi Kebaikanmu</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Daftar aksi sosial yang telah kamu lakukan dan tercatat di sistem.</p>
                        </div>
                        <span class="text-xs font-bold text-blue-900 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                            Total: {{ $activities->count() }} Aksi
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($activities as $act)
                            <div class="p-4 rounded-2xl border border-gray-100 flex flex-col sm:flex-row justify-between items-start gap-4 bg-gray-50/50 hover:bg-white transition">
                                <div class="flex items-start gap-3.5 flex-1">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl shrink-0 border border-emerald-100">
                                        🤝
                                    </div>
                                    <div class="space-y-1.5 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-sm font-extrabold text-gray-900">{{ $act->jenis_kegiatan }}</h4>
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-md border border-emerald-200">
                                                Divalidasi (+30 Poin)
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $act->deskripsi }}</p>
                                        
                                        <div class="flex items-center gap-3 text-[11px] text-gray-500 font-medium flex-wrap pt-1">
                                            <span>📅 {{ \Carbon\Carbon::parse($act->date)->translatedFormat('d M Y') }}</span>
                                            @if(!empty($act->lokasi_kegiatan))
                                                <span>📍 {{ $act->lokasi_kegiatan }}</span>
                                            @endif
                                            @if(!empty($act->foto_kegiatan))
                                                <a href="{{ asset('storage/' . $act->foto_kegiatan) }}" target="_blank" class="text-blue-700 font-bold hover:underline inline-flex items-center gap-1">
                                                    <span>🖼️</span> Lihat Foto
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-2 shrink-0 self-start sm:self-center">
                                    <span class="text-[10px] font-bold text-gray-400">
                                        {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                                    </span>
                                    <form action="{{ route('student.aktif-masyarakat.destroy', $act->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus catatan aksi sosial ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-bold px-2.5 py-1 rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Hapus catatan ini">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-400 text-xs italic bg-gray-50 rounded-2xl border border-dashed border-gray-200 space-y-2">
                                <span class="text-3xl block">🌱</span>
                                <p>Belum ada aktivitas sosial yang dicatat. Yuk catat aksi kebaikanmu hari ini!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (Statistik, Dampak Kolektif & Inspirator) -->
            <div class="space-y-6">
                
                <!-- Poin Sosial Card -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm text-center space-y-4">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-2xl border border-emerald-100">
                        💚
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Poin Sosial</span>
                        <h2 class="text-4xl font-black text-gray-900 mt-1">{{ $totalSocialPoints ?? 0 }}</h2>
                        <span class="inline-block mt-2 px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-extrabold rounded-full border border-emerald-200">
                            {{ $contributorLevel ?? 'Relawan Pemula' }}
                        </span>
                        <p class="text-[11px] text-gray-500 mt-2 font-medium">
                            Setiap aksi kebaikan bernilai 30 poin sosial untuk portofolio karaktermu.
                        </p>
                    </div>
                </div>

                <!-- Dampak Kolektif Sekolah Card (Real Time dari Database) -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                    <div class="rounded-2xl overflow-hidden h-32 bg-gray-100 relative">
                        <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=600&q=80" alt="Dampak Kolektif" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-4 right-4">
                            <span class="text-[10px] font-extrabold text-amber-300 uppercase tracking-wider">Aksi Bersama</span>
                            <h4 class="text-sm font-black text-white">Dampak Kolektif Bulan Ini</h4>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-xs text-gray-600 leading-relaxed font-medium">
                            Seluruh siswa SMPN 9 telah mengumpulkan <strong>{{ $collectiveMonthlyCount ?? 0 }} aksi kebaikan</strong> bulan ini.
                        </p>
                        
                        <div class="space-y-1.5 pt-1">
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $collectiveProgress ?? 0 }}%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] font-bold text-gray-500">
                                <span>{{ $collectiveMonthlyCount ?? 0 }} Aksi Terkumpul</span>
                                <span>Target: {{ $collectiveTarget ?? 100 }} Aksi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inspirator Pekan Ini Card -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider">Inspirator Pekan Ini</h3>
                        <span class="text-amber-500 text-base">⭐</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($inspirators as $ins)
                            <div class="flex items-center justify-between p-2 rounded-xl hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-900 font-extrabold text-xs flex items-center justify-center border border-blue-200">
                                        {{ $ins->initials }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 line-clamp-1">{{ $ins->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">Kontributor Aktif</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-full border border-emerald-200">
                                    {{ $ins->total_actions }} Aksi
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-400 text-xs italic bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                Belum ada inspirator pekan ini. Jadilah yang pertama menebar kebaikan!
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

</div>

<script>
    function toggleLainnya(val) {
        const wrapper = document.getElementById('wrapper-lainnya');
        const inputLainnya = document.getElementById('input-lainnya');
        if (wrapper) {
            if (val === 'Lainnya') {
                wrapper.classList.remove('hidden');
                inputLainnya?.focus();
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

    function previewImage(input) {
        const previewContainer = document.getElementById('preview-container');
        const previewImg = document.getElementById('image-preview');
        const placeholder = document.getElementById('preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (previewImg && placeholder) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function detectGPS() {
        const inputLokasi = document.getElementById('input-lokasi');
        const btnGps = document.getElementById('btn-gps');

        if (!navigator.geolocation) {
            alert('Peramban Anda tidak mendukung fitur Geolocation.');
            return;
        }

        if (btnGps) {
            btnGps.textContent = 'Mendeteksi...';
        }

        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude.toFixed(5);
            const lng = position.coords.longitude.toFixed(5);
            if (inputLokasi) {
                inputLokasi.value = `Koordinat: ${lat}, ${lng} (Probolinggo)`;
            }
            if (btnGps) {
                btnGps.textContent = '✓ Lokasi Terdeteksi';
                btnGps.classList.remove('bg-gray-100', 'text-gray-700');
                btnGps.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
            }
        }, function (error) {
            alert('Gagal mendeteksi lokasi GPS. Anda dapat mengetikkan nama lokasi secara manual.');
            if (btnGps) {
                btnGps.textContent = '📡 Deteksi GPS';
            }
        });
    }
</script>
@endsection
