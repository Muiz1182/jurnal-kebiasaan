@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Sidebar Navigation -->
        <div class="md:col-span-3 space-y-2">
            <a href="{{ route('settings.index') }}" class="block p-4 text-gray-600 hover:bg-gray-100 rounded-2xl transition">Profil & Keamanan</a>
            <a href="{{ route('settings.school') }}" class="block p-4 text-gray-600 hover:bg-gray-100 rounded-2xl transition">Informasi Sekolah</a>
            <a href="{{ route('settings.notifications') }}" class="block p-4 bg-blue-900 text-white font-bold rounded-2xl shadow-lg shadow-blue-900/20">Notifikasi</a>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-9">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <div class="mb-10">
                    <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Pengaturan Notifikasi</h2>
                    <p class="text-sm text-gray-400">Pilih bagaimana Anda ingin menerima pembaruan dari sistem.</p>
                </div>
                
                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.notifications.update') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Notifikasi Email -->
                        <label class="flex items-center justify-between p-6 border border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all group">
                            <div>
                                <span class="font-bold text-gray-800 block">Notifikasi Email</span>
                                <span class="text-xs text-gray-400">Terima ringkasan jurnal dan pengumuman via email</span>
                            </div>
                            <input type="checkbox" name="email_notifications" value="1" {{ auth()->user()->email_notifications ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-gray-300 text-blue-900 focus:ring-blue-500 cursor-pointer">
                        </label>
                        
                        <!-- Notifikasi Browser -->
                        <label class="flex items-center justify-between p-6 border border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all group">
                            <div>
                                <span class="font-bold text-gray-800 block">Notifikasi Browser</span>
                                <span class="text-xs text-gray-400">Dapatkan peringatan langsung di perangkat Anda</span>
                            </div>
                            <input type="checkbox" name="browser_notifications" value="1" {{ auth()->user()->browser_notifications ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-gray-300 text-blue-900 focus:ring-blue-500 cursor-pointer">
                        </label>

                        <div class="pt-6">
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