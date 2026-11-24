@extends('layouts.admin')

@section('title', 'Manajemen Guru')

@section('content')
<div class="w-full px-6 py-6 space-y-6">
    
    <!-- Header & Breadcrumb -->
    <div>
        <nav class="text-xs text-gray-400 font-medium mb-1">
            Portal <span class="mx-1">></span> <span class="text-gray-600 font-bold">Teacher Management</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Guru</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola data tenaga pendidik dan akses akun guru SMPN 9 Probolinggo.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-2xl font-bold text-sm hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Data
                </a>
                <a href="{{ route('admin.teachers.create') }}" class="px-5 py-2.5 bg-blue-900 text-white rounded-2xl font-bold text-sm hover:bg-blue-800 transition shadow-lg shadow-blue-900/10 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Guru Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pencarian Cepat -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Pencarian Cepat</h3>
            <form method="GET" action="{{ route('admin.teachers.index') }}" class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Nama atau NIP..." class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition">
            </form>
        </div>

        <!-- Filter Mapel -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Filter Mapel</h3>
            <form method="GET" action="{{ route('admin.teachers.index') }}">
                <select name="subject" onchange="this.form.submit()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition text-gray-600">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj }}" {{ request('subject') == $subj ? 'selected' : '' }}>
                            {{ $subj }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Tabel Data Guru -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-50/50 text-blue-900 text-xs font-black uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Nama Guru</th>
                        <th class="py-4 px-6">NIP</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6">Status Akun</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($teachers as $teacher)
                    @php
                        $fotoPath = $teacher->avatar ?? $teacher->photo ?? $teacher->image ?? $teacher->foto ?? $teacher->profile_photo_path ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 flex items-center gap-3">
                            @if(!empty($fotoPath))
                                <img src="{{ filter_var($fotoPath, FILTER_VALIDATE_URL) ? $fotoPath : (file_exists(public_path('storage/' . $fotoPath)) ? asset('storage/' . $fotoPath) : asset($fotoPath)) }}" alt="{{ $teacher->name }}" class="w-10 h-10 rounded-2xl object-cover shadow-md shadow-blue-900/10">
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-blue-900 text-white font-bold flex items-center justify-center text-xs shadow-md shadow-blue-900/10">
                                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                </div>
                            @endif
                            
                            <div>
                                <p class="font-bold text-gray-900">{{ $teacher->name }}</p>
                                <p class="text-xs text-gray-400">{{ $teacher->email }}</p>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-medium text-gray-600">{{ $teacher->nip ?? '-' }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $teacher->jabatan ?? '-' }}</td>
                        <td class="py-4 px-6">
                            <!-- DIREVISI: Status Aktif berdasarkan kolom is_active database (1 = Aktif, 0 = Tidak Aktif) -->
                            @if($teacher->is_active == 1)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right relative" x-data="{ open: false }">
                            <!-- Tombol Dropdown Aksi Menggunakan Alpine.js -->
                            <button @click="open = !open" class="p-2 text-gray-400 hover:text-blue-900 transition rounded-xl hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                            </button>

                            <div x-cloak x-show="open" @click.away="open = false" class="absolute right-8 top-10 w-40 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-left">
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition">
                                    <i class="fa-regular fa-pen-to-square mr-2"></i> Edit Data
                                </a>
                                <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                        <i class="fa-regular fa-trash-can mr-2"></i> Hapus Guru
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400 font-medium">Belum ada data guru yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-gray-500 font-medium">
                Menampilkan {{ $teachers->firstItem() ?? 0 }}-{{ $teachers->lastItem() ?? 0 }} dari {{ $teachers->total() }} guru
            </p>
            <div>
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection