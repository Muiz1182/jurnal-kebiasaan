@extends('layouts.app')

@section('title', 'Notifikasi Guru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- HEADER NOTIFIKASI -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" 
               aria-label="Kembali ke Dashboard"
               class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition shrink-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                    <span>&gt;</span>
                    <span class="text-blue-900 font-bold">Pemberitahuan</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Notifikasi Masuk
                </h1>
            </div>
        </div>
    </div>

    <!-- LIST NOTIFIKASI -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $notification)
            <div class="p-5 sm:p-6 flex items-start gap-4 hover:bg-slate-50/70 transition {{ $notification->read_at ? 'opacity-70' : 'bg-blue-50/20' }}">
                <div class="w-2.5 h-2.5 mt-2 rounded-full shrink-0 {{ $notification->read_at ? 'bg-slate-300' : 'bg-blue-600 animate-pulse' }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-bold text-slate-900 leading-relaxed">
                        {{ $notification->data['message'] ?? 'Informasi akademik baru telah ditambahkan ke sistem.' }}
                    </p>
                    <span class="text-[11px] text-slate-400 font-medium mt-1 block">
                        {{ $notification->created_at->diffForHumans() }} ({{ $notification->created_at->translatedFormat('d M Y, H:i') }} WIB)
                    </span>
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-slate-400">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl text-slate-400">
                    🔔
                </div>
                <p class="font-extrabold text-sm text-slate-700">Tidak ada notifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Anda sudah melihat seluruh pemberitahuan terbaru.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($notifications->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection
