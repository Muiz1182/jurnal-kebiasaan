@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <!-- Feedback Alerts -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Sidebar Navigation -->
        <div class="md:col-span-3 space-y-2">
            <a href="{{ route('settings.index') }}" class="block p-4 bg-blue-900 text-white font-bold rounded-2xl shadow-lg shadow-blue-900/20">Profil & Keamanan</a>
            <a href="{{ route('settings.school') }}" class="block p-4 text-gray-600 hover:bg-gray-100 rounded-2xl transition">Informasi Sekolah</a>
            <a href="{{ route('settings.notifications') }}" class="block p-4 text-gray-600 hover:bg-gray-100 rounded-2xl transition">Notifikasi</a>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-9 space-y-8">
            
            <!-- Import Data Section -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-800">Import Data Murid</h2>
                        <p class="text-xs text-gray-400">Update data murid massal melalui file Excel</p>
                    </div>
                </div>
                
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-end gap-4">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pilih File Excel</label>
                        <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer" required>
                    </div>
                    <button type="submit" class="px-8 py-2.5 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition">Import Data</button>
                    <a href="{{ asset('templates/students_template.xlsx') }}" class="px-8 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition">Download Template</a>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4">Zona Bahaya</h3>
                    <form action="{{ route('settings.reset-all') }}" method="POST" onsubmit="return confirm('PERINGATAN: Ini akan menghapus SEMUA data murid, nilai, dan absensi Anda. Apakah Anda yakin?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-100 transition">Reset Semua Data Saya</button>
                    </form>
                </div>
            </div>
            
            <!-- Edit Profile Section -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-xl font-extrabold mb-8 text-gray-800 tracking-tight">Ubah Profil</h2>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    
                    <div class="flex items-center gap-8 mb-10">
                        <img src="{{ auth()->user()->foto_url ?? asset('images/default.png') }}" class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg object-cover">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Ganti Foto Profil</label>
                            <input type="file" name="foto_profil" class="text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">NIP</label>
                            <input type="text" name="nip" value="{{ auth()->user()->nip }}" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ auth()->user()->jabatan }}" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Kelas Mengajar</label>
                            <input type="text" name="kelas_mengajar" value="{{ auth()->user()->kelas_mengajar }}" placeholder="Contoh: 9-A" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Mata Pelajaran (Multi-Select)</label>
                            <select name="subject_ids[]" multiple class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none h-32" size="4">
                                @foreach(\App\Models\Subject::all() as $subj)
                                    <option value="{{ $subj->id }}" class="py-1" {{ auth()->user()->subjects->contains($subj->id) ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-2 italic">* Tahan Ctrl/Cmd untuk memilih banyak.</p>
                        </div>
                    </div>
                    <button type="submit" class="mt-8 px-10 py-4 bg-blue-900 text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition">Simpan Profil</button>
                </form>
            </div>

            <!-- Password Section -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-xl font-extrabold mb-8 text-gray-800 tracking-tight">Ubah Password</h2>
                <form action="{{ route('settings.password.update') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Password Baru</label>
                            <input type="password" name="password" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                    </div>
                    <button type="submit" class="mt-8 px-10 py-4 bg-blue-900 text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection