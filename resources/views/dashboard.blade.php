@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="w-full px-6 py-6" x-data="{ showModal: false, journal: {} }">
    <div class="grid grid-cols-1 gap-8">
        <!-- Kolom Utama -->
        <div class="space-y-8">
            <!-- Header Informasi -->
            <header class="flex justify-between items-center bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $greeting ?? 'Selamat Datang' }}, {{ auth()->user()->name }}</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ now()->translatedFormat('l, d F Y') }} | 
                        @if(isset($totalClasses) && $totalClasses > 0)
                            Anda mengampu <span class="font-bold text-blue-900">{{ $totalClasses }} kelas</span>.
                        @else
                            Belum ada kelas yang terdaftar.
                        @endif
                    </p>
                </div>
                <a href="{{ route('journal.create') }}" class="bg-gray-900 text-white px-8 py-3 rounded-2xl font-bold text-sm hover:bg-gray-800 transition shadow-lg shadow-gray-900/10">
                    + Buat Jurnal Baru
                </a>
            </header>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Jurnal ({{ now()->format('M') }})</p>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $totalJournals ?? 0 }}</h2>
                </div>
                
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Avg. Attendance</p>
                    <h2 class="text-3xl font-extrabold text-green-600 mt-2">{{ $avgAttendance ?? 0 }}%</h2>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Progress</p>
                    <h2 class="text-3xl font-extrabold text-blue-900 mt-2">{{ $progress ?? '0%' }}</h2>
                </div>
            </div>

            <!-- Jurnal Hari Ini -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-6">Jurnal Mengajar Hari Ini</h3>
                
                @forelse($todayJournals ?? [] as $journal)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl mb-3 border border-gray-100 hover:border-blue-200 transition">
                        <div>
                            <p class="font-bold text-gray-800">{{ $journal->class_name }}</p>
                            <p class="text-sm text-gray-500 font-medium truncate max-w-xs">{{ $journal->content }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-1.5 bg-green-50 text-green-600 rounded-2xl text-[10px] font-black uppercase tracking-widest">Selesai</span>
                            <!-- Tombol Lihat Detail -->
                            <button @click="showModal = true; journal = {{ json_encode($journal) }}" class="p-2 text-gray-400 hover:text-blue-900 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <p class="text-gray-400 font-bold text-sm">Belum ada jurnal yang diinput hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Detail Jurnal -->
    <div x-show="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-cloak>
        <div @click.away="showModal = false" class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl">
            <h2 class="text-xl font-black text-gray-900 mb-6">Detail Jurnal</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">Tanggal</label>
                    <p x-text="journal.date" class="font-bold text-gray-800"></p>
                </div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase">Kelas</label><p x-text="journal.class_name" class="font-bold text-gray-800"></p></div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase">Materi</label><p x-text="journal.content" class="text-gray-700 mt-1 p-3 bg-gray-50 rounded-xl"></p></div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase">Catatan</label><p x-text="journal.notes" class="text-gray-700 mt-1 p-3 bg-gray-50 rounded-xl"></p></div>
            </div>
            <button @click="showModal = false" class="mt-8 w-full bg-gray-900 text-white py-3 rounded-2xl font-bold hover:bg-gray-800 transition">Tutup</button>
        </div>
    </div>
</div>
@endsection