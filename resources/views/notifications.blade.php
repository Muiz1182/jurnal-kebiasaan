@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')

        <div class="p-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center gap-4 mb-8">
                    <a href="{{ route('dashboard') }}" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-2xl font-bold text-blue-900">Notifikasi Anda</h1>
                </div>

                <div class="bg-white rounded-2xl border shadow-sm divide-y">
                    @forelse($notifications as $notification)
                        <div class="p-6 flex gap-4 hover:bg-gray-50 transition">
                            <div class="w-2 h-2 mt-2 rounded-full {{ $notification->read_at ? 'bg-gray-300' : 'bg-blue-600' }}"></div>
                            <div>
                                <p class="text-gray-800 font-medium">
                                    {{ $notification->data['message'] ?? 'Ada informasi baru.' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <p>Tidak ada notifikasi baru saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
@endsection