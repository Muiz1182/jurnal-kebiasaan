@extends('layouts.student')

@section('title', 'Aktif Masyarakat')

@section('content')
<!-- KONTEN UTAMA AKTIF MASYARAKAT -->
<main class="flex-1 p-8 space-y-6 overflow-y-auto">
    
    <!-- Header Atas: Judul Halaman & Tanggal -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Aktif Masyarakat</h1>
            <p class="text-gray-500 text-sm mt-1">Catat aksi kebaikan sosialmu untuk membangun karakter peduli sesama.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-gray-50 border border-gray-200 px-4 py-2 rounded-2xl flex items-center gap-2 text-xs font-bold text-gray-700">
                <span>📅</span>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Misi Hari Ini, Form Catatan, & Aktivitas Terakhir -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Misi Hari Ini Card -->
            <div class="bg-blue-900 text-white p-6 rounded-3xl shadow-xl space-y-3 relative overflow-hidden">
                <div class="inline-block px-3 py-1 bg-amber-400 text-blue-950 text-[10px] font-black rounded-full uppercase tracking-wider">
                    Misi Hari Ini
                </div>
                <h3 class="text-xl font-extrabold">Tebar Kebaikan di Sekitarmu</h3>
                <p class="text-xs text-blue-100 leading-relaxed max-w-xl">
                    Satu senyuman atau bantuan kecil kepada tetangga adalah langkah besar membangun karakter bangsa yang kuat.
                </p>
            </div>

            <!-- Form Catatan Kegiatan Sosial -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                    <span>📅</span> Catatan Kegiatan Sosial
                </h3>

                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-2xl space-y-1">
                        <p class="font-bold">Mohon periksa kembali form Anda:</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('student.aktif-masyarakat.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jenis Kegiatan</label>
                        <select name="jenis_kegiatan" id="jenis_kegiatan" class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:border-blue-400 bg-white">
                            <option value="Membantu Tetangga" {{ old('jenis_kegiatan') == 'Membantu Tetangga' ? 'selected' : '' }}>Membantu Tetangga</option>
                            <option value="Membantu Menyeberang" {{ old('jenis_kegiatan') == 'Membantu Menyeberang' ? 'selected' : '' }}>Membantu Menyeberang</option>
                            <option value="Kerja Bakti Masjid" {{ old('jenis_kegiatan') == 'Kerja Bakti Masjid' ? 'selected' : '' }}>Kerja Bakti Masjid</option>
                            <option value="Gotong Royong" {{ old('jenis_kegiatan') == 'Gotong Royong' ? 'selected' : '' }}>Gotong Royong</option>
                            <option value="Lainnya" {{ old('jenis_kegiatan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Input teks tambahan jika memilih kategori Lainnya -->
                    <div id="wrapper-lainnya" class="{{ old('jenis_kegiatan') == 'Lainnya' ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Sebutkan Kegiatan Lainnya</label>
                        <input type="text" name="jenis_kegiatan_lainnya" value="{{ old('jenis_kegiatan_lainnya') }}" placeholder="Tuliskan jenis kegiatan sosialmu..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:border-blue-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Apa yang kamu lakukan hari ini?</label>
                        <textarea name="deskripsi" rows="4" placeholder="Ceritakan kebaikan yang kamu lakukan..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:border-blue-400 resize-none">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Input File Tersembunyi untuk Unggah Foto & Lokasi -->
                    <input type="file" name="foto_kegiatan" id="foto_kegiatan" class="hidden" accept="image/*">
                    <input type="hidden" name="lokasi_kegiatan" id="lokasi_kegiatan">

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pt-2">
                        <div class="flex items-center gap-2">
                            <button type="button" id="btn-upload-foto" class="h-10 px-3 rounded-xl border border-gray-200 flex items-center gap-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition" title="Unggah Foto Bukti">
                                <span>📷</span> <span id="label-foto">Unggah Foto</span>
                            </button>
                            <button type="button" id="btn-lokasi" class="h-10 px-3 rounded-xl border border-gray-200 flex items-center gap-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition" title="Sertakan Lokasi">
                                <span>📍</span> <span id="label-lokasi">Sertakan Lokasi</span>
                            </button>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-950 text-white font-extrabold text-xs rounded-2xl hover:bg-blue-900 transition shadow-md">
                            Simpan Log
                        </button>
                    </div>
                </form>
            </div>

            <!-- Aktivitas Terakhir -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-extrabold text-gray-900 text-base">Aktivitas Terakhir</h3>

                <div class="space-y-3">
                    @forelse($activities ?? [] as $activity)
                        <div class="p-4 rounded-2xl border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold shrink-0">🤝</div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-xs font-extrabold text-gray-900">{{ $activity->jenis_kegiatan }}</h4>
                                        @if(isset($activity->status))
                                            @if($activity->status == 'validated' || $activity->status == 'approved')
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md">DIVALIDASI</span>
                                            @elseif($activity->status == 'pending')
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-md">MENUNGGU VALIDASI</span>
                                            @endif
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $activity->deskripsi }}</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-400 shrink-0 self-start sm:self-center">
                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-xs italic bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            Belum ada aktivitas yang dicatat. Silakan isi form di atas untuk menambahkan catatan kegiatan sosialmu!
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Poin Sosial, Dampak Kolektif, & Inspirator Pekan Ini -->
        <div class="space-y-6">
            
            <!-- Poin Sosial Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm text-center space-y-3">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-xl">💚</div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Poin Sosial</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalSocialPoints ?? 0 }}</h2>
                    <p class="text-xs font-bold text-gray-400 mt-1">
                        @if(isset($totalSocialPoints) && $totalSocialPoints > 0)
                            Siswa Aktif Berkontribusi
                        @else
                            Belum ada poin
                        @endif
                    </p>
                </div>
            </div>

            <!-- Dampak Kolektif Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <div class="rounded-2xl overflow-hidden h-32 bg-gray-100 relative">
                    <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=600&q=80" alt="Ilustrasi Dampak Kolektif" class="w-full h-full object-cover">
                </div>
                <div class="text-center space-y-2">
                    <h4 class="text-xs font-extrabold text-gray-900">Dampak Kolektif</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Siswa sekolah telah melakukan aksi sosial bulan ini.</p>
                </div>
                <div class="space-y-1.5">
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-gray-400">
                        <span>0 Aksi</span>
                        <span>Target: 2.000 Aksi</span>
                    </div>
                </div>
            </div>

            <!-- Inspirator Pekan Ini Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider">Inspirator Pekan Ini</h3>

                <div class="space-y-3">
                    @forelse($inspirators ?? [] as $inspirator)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-bold text-xs flex items-center justify-center">{{ $inspirator->initials }}</div>
                                <span class="text-xs font-bold text-gray-800">{{ $inspirator->name }}</span>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full">{{ $inspirator->total_actions }} Aksi</span>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-400 text-xs italic bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            Belum ada data inspirator pekan ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</main>

<!-- Script interaksi form lainnya, foto, dan lokasi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectJenis = document.getElementById('jenis_kegiatan');
        const wrapperLainnya = document.getElementById('wrapper-lainnya');

        if(selectJenis && wrapperLainnya) {
            if(selectJenis.value === 'Lainnya') {
                wrapperLainnya.classList.remove('hidden');
            }

            selectJenis.addEventListener('change', function() {
                if(this.value === 'Lainnya') {
                    wrapperLainnya.classList.remove('hidden');
                } else {
                    wrapperLainnya.classList.add('hidden');
                }
            });
        }

        const btnUpload = document.getElementById('btn-upload-foto');
        const inputFoto = document.getElementById('foto_kegiatan');
        const labelFoto = document.getElementById('label-foto');

        if(btnUpload && inputFoto) {
            btnUpload.addEventListener('click', () => inputFoto.click());
            inputFoto.addEventListener('change', function() {
                if(this.files && this.files[0]) {
                    labelFoto.textContent = 'Foto Terpilih (' + this.files[0].name.substring(0, 10) + '...)';
                    btnUpload.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
                }
            });
        }

        const btnLokasi = document.getElementById('btn-lokasi');
        const inputLokasi = document.getElementById('lokasi_kegiatan');
        const labelLokasi = document.getElementById('label-lokasi');

        if(btnLokasi && inputLokasi) {
            btnLokasi.addEventListener('click', function() {
                if (navigator.geolocation) {
                    labelLokasi.textContent = 'Mencari Lokasi...';
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        inputLokasi.value = lat + ',' + lng;
                        labelLokasi.textContent = 'Lokasi Tersemat ✓';
                        btnLokasi.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                    }, function(error) {
                        labelLokasi.textContent = 'Gagal Mendapatkan Lokasi';
                        alert('Tidak dapat mendeteksi lokasi. Pastikan izin GPS aktif.');
                    });
                } else {
                    alert('Geolocation tidak didukung oleh browser Anda.');
                }
            });
        }
    });
</script>
@endsection