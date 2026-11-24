@extends('layouts.admin')

@section('title', 'Tambah Guru Baru')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <!-- Header Halaman -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Tambah Guru Baru</h2>
            <p class="text-xs text-gray-500 font-medium mt-1">Formulir pendaftaran dan pembuatan akun guru baru oleh Administrator.</p>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-2xl text-xs font-bold hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Card Formulir -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-xs text-red-600 font-semibold space-y-1">
                <p class="font-bold">Terjadi beberapa kesalahan:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- REVISI: Set otomatis role sebagai guru -->
            <input type="hidden" name="role" value="guru">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label for="name" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fauzi, S.Pd." required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <!-- NIP / ID Guru (Opsional) -->
                <div class="space-y-2">
                    <label for="nip" class="text-xs font-bold text-gray-700 uppercase tracking-wider">NIP / Nomor Identitas <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP atau Nomor Identitas"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <!-- Username untuk Login -->
                <div class="space-y-2">
                    <label for="username" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Username Akun</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Contoh: ahmadfauzi (digunakan untuk login)" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <!-- Alamat Email -->
                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@smpn9probolinggo.sch.id" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <!-- Password Awal -->
                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Password Akun</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password minimal 8 karakter" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <!-- Nomor Telepon / WhatsApp -->
                <div class="space-y-2">
                    <label for="phone" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <!-- Jabatan / Mata Pelajaran -->
                <div class="space-y-2 md:col-span-2">
                    <label for="jabatan" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Jabatan / Mata Pelajaran (Tugas Utama)</label>
                    <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: GURU INFORMATIKA atau GURU MATEMATIKA" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.teachers.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 transition">
                    Simpan Data & Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection