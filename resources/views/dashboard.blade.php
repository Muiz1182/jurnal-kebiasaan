@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ showModal: false, selectedJournal: {} }">

    <!-- HERO BANNER INFORMASI GURU -->
    <div class="relative overflow-hidden rounded-3xl bg-[#0A1128] text-white p-6 sm:p-8 md:p-10 border border-slate-800 shadow-xl">
        <!-- Ambient Background Glow -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 font-extrabold uppercase tracking-wider text-[10px]">
                        Portal Akademik Guru
                    </span>
                    <span class="text-slate-400">•</span>
                    <span class="text-slate-300 font-semibold">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight">
                    {{ $greeting ?? 'Selamat Datang' }}, {{ auth()->user()->name }}!
                </h1>

                <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">
                    @if(isset($totalClasses) && $totalClasses > 0)
                        Anda saat ini mengampu <span class="font-bold text-white">{{ $totalClasses }} kelas aktif</span>. Seluruh progres presensi, penilaian, dan jurnal mengajar dapat dipantau di sini.
                    @else
                        Selamat bertugas. Silakan isi jurnal mengajar dan kelola presensi siswa secara berkala.
                    @endif
                </p>
            </div>

            <!-- Tombol Aksi Cepat -->
            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('journal.create') }}" 
                   class="min-h-[48px] px-6 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2.5 transition active:scale-[0.99] cursor-pointer">
                    <i class="fas fa-plus"></i>
                    <span>Buat Jurnal Mengajar Baru</span>
                </a>
                <a href="{{ route('attendance.index') }}" 
                   class="min-h-[48px] px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/10 font-bold text-xs sm:text-sm flex items-center justify-center gap-2.5 transition cursor-pointer">
                    <i class="fas fa-user-check"></i>
                    <span>Input Presensi Hari Ini</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KARTU STATISTIK UTAMA (3 KOLOM) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        
        <!-- Kartu 1: Total Jurnal Bulan Ini -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Total Jurnal Bulan Ini
                </p>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-black text-slate-900">{{ $totalJournals ?? 0 }}</span>
                    <span class="text-xs text-slate-500 font-bold">kegiatan</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-1">
                    Periode {{ now()->translatedFormat('F Y') }}
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl shrink-0 border border-blue-100">
                <i class="fas fa-book"></i>
            </div>
        </div>

        <!-- Kartu 2: Rata-Rata Kehadiran Siswa -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Rata-Rata Kehadiran Siswa
                </p>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-black text-emerald-600">{{ $avgAttendance ?? 0 }}%</span>
                    <span class="text-xs text-slate-500 font-bold">hadir</span>
                </div>
                <div class="w-36 h-2 rounded-full bg-slate-100 mt-2 overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min(100, $avgAttendance ?? 0) }}%"></div>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl shrink-0 border border-emerald-100">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>

        <!-- Kartu 3: Progres Mengajar Hari Ini -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4 sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Progres Sesi Hari Ini
                </p>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-black text-blue-900">{{ $progress ?? '0%' }}</span>
                    <span class="text-xs text-slate-500 font-bold">terselesaikan</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-1">
                    Berdasarkan jadwal mengajar harian
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-900 flex items-center justify-center text-xl shrink-0 border border-indigo-100">
                <i class="fas fa-tasks"></i>
            </div>
        </div>

    </div>

    <!-- GRID 2 KOLOM: JADWAL MENGAJAR & JURNAL HARI INI -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        
        <!-- KOLOM 1: JADWAL MENGAJAR HARI INI (5 KOLOM DI LG) -->
        <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-black text-base text-slate-900 tracking-tight">Jadwal Mengajar</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Agenda kelas yang Anda ampu</p>
                    </div>
                    <span class="text-xs font-bold text-blue-900 bg-blue-50 px-3 py-1 rounded-full">
                        {{ count($todaySchedules ?? []) }} Jadwal
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($todaySchedules ?? [] as $schedule)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-900 text-white flex items-center justify-center text-xs font-black shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-extrabold text-xs text-slate-900 truncate">
                                        {{ $schedule->subject_name ?? ($schedule->subject->name ?? 'Mata Pelajaran') }}
                                    </p>
                                    <span class="text-[11px] font-black text-blue-900 shrink-0">
                                        Kelas {{ $schedule->class_name }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium mt-1">
                                    {{ $schedule->start_time ? substr($schedule->start_time, 0, 5) : '07:00' }} - {{ $schedule->end_time ? substr($schedule->end_time, 0, 5) : '08:20' }} WIB
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl text-slate-400">
                                📅
                            </div>
                            <p class="font-extrabold text-sm text-slate-700">Tidak ada jadwal hari ini</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                                Jadwal mengajar kelas belum dijadwalkan untuk hari ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-5 mt-4 border-t border-slate-100">
                <a href="{{ route('journal.create') }}" class="text-xs font-extrabold text-blue-900 hover:text-blue-700 hover:underline flex items-center justify-center gap-1.5">
                    <span>Lihat Formulir Jurnal Lengkap</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- KOLOM 2: JURNAL MENGAJAR HARI INI (7 KOLOM DI LG) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-black text-base text-slate-900 tracking-tight">Jurnal Mengajar Hari Ini</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Catatan kegiatan materi di kelas</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full">
                        {{ count($todayJournals ?? []) }} Selesai
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($todayJournals ?? [] as $journal)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-900">
                                        Kelas {{ $journal->class_name }}
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-xs text-slate-500 font-semibold truncate">
                                        {{ $journal->subject ?? 'Mata Pelajaran' }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-slate-800 truncate">
                                    {{ $journal->content ?: 'Kegiatan belajar mengajar tercatat' }}
                                </p>
                                @if($journal->notes)
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                        Catatan: {{ $journal->notes }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-extrabold border border-emerald-200">
                                    Tersimpan
                                </span>
                                <button type="button" 
                                        @click="selectedJournal = {{ json_encode($journal) }}; showModal = true"
                                        class="min-h-[44px] px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>Detail</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl text-slate-400">
                                📖
                            </div>
                            <p class="font-extrabold text-sm text-slate-700">Belum ada jurnal yang diisi hari ini</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                Klik tombol di bawah untuk mendokumentasikan kegiatan belajar mengajar Anda hari ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-5 mt-4 border-t border-slate-100 text-center sm:text-right">
                <a href="{{ route('journal.create') }}" 
                   class="inline-flex items-center gap-2 text-xs font-black text-blue-900 hover:text-blue-700 hover:underline">
                    <span>+ Tambah Jurnal Mengajar Hari Ini</span>
                </a>
            </div>
        </div>

    </div>

    <!-- MODAL DETAIL JURNAL (ALPINE.JS RESPONSIVE) -->
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showModal = false" 
             class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 text-slate-900 shadow-2xl border border-slate-100">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-900 bg-blue-50 px-2.5 py-0.5 rounded-full">
                        Detail Aktivitas
                    </span>
                    <h3 class="text-lg font-black text-slate-900 mt-1">Jurnal Pembelajaran</h3>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 font-extrabold uppercase text-[10px] block">Tanggal</span>
                        <span class="font-bold text-slate-800 text-xs mt-1 block" x-text="selectedJournal.date || '{{ now()->format('Y-m-d') }}'"></span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 font-extrabold uppercase text-[10px] block">Kelas</span>
                        <span class="font-bold text-blue-900 text-xs mt-1 block" x-text="'Kelas ' + (selectedJournal.class_name || '-')"></span>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-extrabold uppercase text-[10px] block mb-1">Materi Pembelajaran</span>
                    <p class="font-semibold text-slate-800 text-xs leading-relaxed" x-text="selectedJournal.content || '-'"></p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-extrabold uppercase text-[10px] block mb-1">Catatan Tambahan Guru</span>
                    <p class="font-medium text-slate-700 text-xs leading-relaxed" x-text="selectedJournal.notes || 'Tidak ada catatan tambahan.'"></p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <button type="button" 
                        @click="showModal = false" 
                        class="w-full min-h-[48px] bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs sm:text-sm rounded-2xl transition cursor-pointer">
                    Tutup Tinjauan
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
