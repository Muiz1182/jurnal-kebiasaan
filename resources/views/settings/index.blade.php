@extends('layouts.app')

@section('title', 'Pengaturan Profil Guru')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    <!-- HEADER PENGATURAN -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Pengaturan</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Pengaturan Akun &amp; Profil
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Kelola identitas pendidik, data siswa binaan, dan keamanan akun Anda.
            </p>
        </div>
    </div>

    <!-- TABS NAVIGASI SETTINGS (RESPONSIF MOBILE SCROLL) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200/80">
        <a href="{{ route('settings.index') }}" 
           class="min-h-[44px] px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('settings.index') ? 'bg-blue-900 text-white shadow-md shadow-blue-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-user-circle"></i>
            <span>Profil &amp; Keamanan</span>
        </a>
        <a href="{{ route('settings.school') }}" 
           class="min-h-[44px] px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('settings.school') ? 'bg-blue-900 text-white shadow-md shadow-blue-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-school"></i>
            <span>Informasi Sekolah</span>
        </a>
        <a href="{{ route('settings.notifications') }}" 
           class="min-h-[44px] px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('settings.notifications') ? 'bg-blue-900 text-white shadow-md shadow-blue-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="fas fa-bell"></i>
            <span>Notifikasi</span>
        </a>
    </div>

    <!-- GRID KONTEN PENGATURAN -->
    <div class="space-y-6 sm:space-y-8">
        
        <!-- CARD 1: UBAH PROFIL GURU -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                    Informasi Data Pendidik
                </h2>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Foto Profil -->
                <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-blue-900 text-white font-extrabold flex items-center justify-center text-3xl shrink-0 shadow-md border-2 border-white">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ auth()->user()->foto_url }}" alt="Foto Profil" class="w-full h-full object-cover rounded-2xl">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                        @endif
                    </div>
                    <div class="space-y-1.5 text-center sm:text-left flex-1">
                        <label for="foto_profil" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Unggah Foto Profil Baru
                        </label>
                        <input type="file" 
                               id="foto_profil"
                               name="foto_profil" 
                               accept="image/*"
                               class="text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-blue-900 file:text-white hover:file:bg-blue-800 transition cursor-pointer">
                        <p class="text-[11px] text-slate-400">Format JPG, PNG atau WebP (Maksimal 2MB).</p>
                    </div>
                </div>

                <!-- Grid Form Identitas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Nama Lengkap &amp; Gelar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               value="{{ old('name', auth()->user()->name) }}" 
                               required 
                               class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5">
                        <label for="nip" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            NIP / NUPTK
                        </label>
                        <input type="text" 
                               id="nip"
                               name="nip" 
                               value="{{ old('nip', auth()->user()->nip) }}" 
                               placeholder="198xxxxxxxxxxxxxxx"
                               class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5">
                        <label for="jabatan" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Jabatan / Penugasan
                        </label>
                        <input type="text" 
                               id="jabatan"
                               name="jabatan" 
                               value="{{ old('jabatan', auth()->user()->jabatan) }}" 
                               placeholder="Contoh: GURU INFORMATIKA"
                               class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5">
                        <label for="kelas_mengajar" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Kelas Binaan / Mengajar
                        </label>
                        <input type="text" 
                               id="kelas_mengajar"
                               name="kelas_mengajar" 
                               value="{{ old('kelas_mengajar', auth()->user()->kelas_mengajar) }}" 
                               placeholder="Contoh: 9-A, 9-B"
                               class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5">
                        <label for="subjects" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Mata Pelajaran yang Diampu
                        </label>
                        <select id="subjects" 
                                name="subject_ids[]" 
                                multiple 
                                class="w-full min-h-[110px] p-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                            @foreach(\App\Models\Subject::all() as $subj)
                                <option value="{{ $subj->id }}" class="py-1 px-2 font-bold" {{ auth()->user()->subjects->contains($subj->id) ? 'selected' : '' }}>
                                    {{ $subj->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-slate-400 font-medium block">Tekan Ctrl/Cmd untuk memilih lebih dari satu mata pelajaran.</span>
                    </div>
                </div>

                <button type="submit" 
                        class="min-h-[48px] px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-2xl font-extrabold text-xs sm:text-sm shadow-md shadow-blue-900/20 transition cursor-pointer">
                    Simpan Pembaruan Profil
                </button>
            </form>
        </div>

        <!-- CARD 2: IMPORT DATA MURID VIA EXCEL -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <span class="w-1.5 h-6 bg-emerald-600 rounded-full"></span>
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                        Import Data Murid Massal
                    </h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Unggah data peserta didik kelas melalui berkas spreadsheet Excel</p>
                </div>
            </div>

            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
                    <div class="flex-1 space-y-1.5">
                        <label for="excel_file" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Pilih Berkas Excel (.xlsx, .xls)
                        </label>
                        <input type="file" 
                               id="excel_file"
                               name="file" 
                               required
                               accept=".xlsx, .xls, .csv"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition cursor-pointer">
                    </div>
                    <button type="submit" 
                            class="min-h-[44px] px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-xs transition cursor-pointer shadow-sm shrink-0">
                        <i class="fas fa-upload mr-1.5"></i>
                        Proses Import
                    </button>
                    <a href="{{ asset('templates/students_template.xlsx') }}" 
                       class="min-h-[44px] px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs transition flex items-center justify-center gap-1.5 shrink-0">
                        <i class="fas fa-download"></i>
                        Unduh Template
                    </a>
                </div>
            </form>

            <!-- Zona Bahaya: Reset Data Guru -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-xs font-black text-rose-600 uppercase tracking-wider mb-2">Zona Khusus</h3>
                <p class="text-xs text-slate-500 mb-3">Tindakan ini akan mengosongkan seluruh data murid binaan, absensi, dan nilai Anda.</p>
                <form action="{{ route('settings.reset-all') }}" method="POST" onsubmit="return confirm('PERINGATAN: Ini akan menghapus data murid, nilai, dan absensi Anda. Apakah Anda yakin?');">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="min-h-[44px] px-5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-extrabold transition cursor-pointer">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Reset Semua Data Saya
                    </button>
                </form>
            </div>
        </div>

        <!-- CARD 3: KEAMANAN KATA SANDI -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                    Keamanan Kata Sandi
                </h2>
            </div>

            <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-4 max-w-xl">
                @csrf
                
                <div class="space-y-1.5">
                    <label for="current_password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="current_password"
                           name="current_password" 
                           required 
                           placeholder="Masukkan kata sandi lama"
                           class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               required 
                               placeholder="Minimal 6 karakter"
                               class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Ulangi Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation"
                               name="password_confirmation" 
                               required 
                               placeholder="Konfirmasi kata sandi baru"
                               class="w-full min-h-[48px] px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                    </div>
                </div>

                <button type="submit" 
                        class="min-h-[48px] px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-2xl font-extrabold text-xs sm:text-sm shadow-md shadow-blue-900/20 transition cursor-pointer mt-2">
                    Perbarui Kata Sandi
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
