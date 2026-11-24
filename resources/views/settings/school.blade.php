@extends('layouts.app')

@section('title', 'Informasi Sekolah')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Sidebar Navigation -->
        <div class="md:col-span-3 space-y-2">
            <a href="{{ route('settings.index') }}" class="block p-4 text-gray-600 hover:bg-gray-100 rounded-2xl transition">Profil & Keamanan</a>
            <a href="{{ route('settings.school') }}" class="block p-4 bg-blue-900 text-white font-bold rounded-2xl shadow-lg shadow-blue-900/20">Informasi Sekolah</a>
            <a href="{{ route('settings.notifications') }}" class="block p-4 text-gray-600 hover:bg-gray-100 rounded-2xl transition">Notifikasi</a>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-9">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <div class="mb-10">
                    <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Informasi Sekolah</h2>
                    <p class="text-sm text-gray-400">Kelola data profil sekolah yang ditampilkan dalam portal.</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.school.update') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Sekolah</label>
                            <input type="text" name="name" value="{{ $school->name ?? 'SMPN 9 Probolinggo' }}" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Alamat</label>
                            <textarea name="address" rows="3" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">{{ $school->address ?? '' }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Kepala Sekolah</label>
                            <input type="text" name="headmaster" value="{{ $school->headmaster ?? '' }}" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition outline-none">
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="px-10 py-4 bg-blue-900 text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection