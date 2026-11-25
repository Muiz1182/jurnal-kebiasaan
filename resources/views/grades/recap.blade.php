@extends('layouts.app')

@section('title', 'Rekap Nilai Akademik')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- HEADER INFORMASI & AKSI EKSPOR -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <a href="{{ route('grades.index') }}" class="hover:text-blue-900 transition">Input Nilai</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Rekap Nilai</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Rekapitulasi Nilai Siswa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Ringkasan pencapaian akademik dan statistik belajar siswa per semester.
            </p>
        </div>
        
        <div class="flex items-center gap-2.5">
            <a href="{{ route('grades.export', ['class' => $selectedClass, 'subject' => $selectedSubject]) }}" 
               class="min-h-[44px] px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-xs flex items-center gap-2 shadow-sm transition">
                <i class="fas fa-file-excel"></i>
                <span>Ekspor Excel</span>
            </a>
            <button onclick="window.print()" 
                    class="min-h-[44px] px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-bold text-xs flex items-center gap-2 transition cursor-pointer">
                <i class="fas fa-print"></i>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- FILTER DATA KELAS & MAPEL -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-wider shrink-0">
            <i class="fas fa-filter text-blue-900"></i>
            <span>Filter Rekap:</span>
        </div>

        <form action="{{ route('grades.recap') }}" method="GET" class="flex gap-3 flex-wrap flex-1">
            <select name="class" 
                    onchange="this.form.submit()" 
                    class="min-h-[44px] px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer">
                @foreach($classes as $class)
                    <option value="{{ $class }}" {{ $selectedClass == $class ? 'selected' : '' }}>Kelas {{ $class }}</option>
                @endforeach
            </select>

            <select name="subject" 
                    onchange="this.form.submit()" 
                    class="min-h-[44px] px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer">
                @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}" {{ (request('subject') == $subj->id || (!request('subject') && $selectedSubject == $subj->id)) ? 'selected' : '' }}>
                        {{ $subj->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- STATS CARDS: RATA-RATA, SISWA TERBAIK, DISTRIBUSI NILAI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        
        <!-- Rata-Rata Kelas -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Rata-rata Kelas</span>
                @php 
                    $gradesData = $students->pluck('grades')->collapse();
                    $avgGrade = $gradesData->avg('rata_rata') ?? 0; 
                @endphp
                <span class="text-4xl font-black text-slate-900 mt-2 block">
                    {{ number_format($avgGrade, 1) }}
                </span>
                <span class="text-xs text-slate-400 font-bold mt-1 block">Skala Penilaian 0-100</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl shrink-0 border border-blue-100">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
        
        <!-- Siswa Terbaik -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Pencapaian Tertinggi</span>
            @php 
                $bestStudent = $students->filter(function($s) {
                    $grade = $s->grades->first();
                    return $grade && $grade->rata_rata > 0;
                })
                ->sortByDesc(fn($s) => $s->grades->first()->rata_rata)
                ->first(); 
            @endphp
            <p class="text-base font-black text-slate-900 truncate mt-2">
                {{ $bestStudent ? $bestStudent->name : 'Belum ada data' }}
            </p>
            @if($bestStudent && $bestStudent->grades->first())
                <span class="text-xs font-bold text-emerald-600 block mt-0.5">
                    Nilai: {{ number_format($bestStudent->grades->first()->rata_rata, 1) }}
                </span>
            @endif
            <div class="w-full bg-slate-100 h-2 rounded-full mt-3 overflow-hidden">
                <div class="bg-blue-900 h-full rounded-full" style="width: {{ $bestStudent ? 100 : 0 }}%"></div>
            </div>
        </div>
        
        <!-- Distribusi Nilai -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs sm:col-span-2 lg:col-span-1">
            <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Distribusi Ketuntasan</span>
            @php $totalDist = array_sum($distribusi) ?: 1; @endphp
            <div class="flex items-end gap-2 h-12 mt-2">
                <div class="flex-1 bg-rose-400 rounded-t-lg transition hover:bg-rose-500 cursor-pointer" 
                     style="height: {{ max(10, ($distribusi['rendah'] / $totalDist) * 100) }}%" 
                     title="Rendah: {{ $distribusi['rendah'] }} siswa"></div>
                <div class="flex-1 bg-amber-400 rounded-t-lg transition hover:bg-amber-500 cursor-pointer" 
                     style="height: {{ max(10, ($distribusi['sedang'] / $totalDist) * 100) }}%" 
                     title="Sedang: {{ $distribusi['sedang'] }} siswa"></div>
                <div class="flex-1 bg-emerald-500 rounded-t-lg transition hover:bg-emerald-600 cursor-pointer" 
                     style="height: {{ max(10, ($distribusi['tinggi'] / $totalDist) * 100) }}%" 
                     title="Tinggi: {{ $distribusi['tinggi'] }} siswa"></div>
            </div>
            <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-2">
                <span>Rendah ({{ $distribusi['rendah'] }})</span>
                <span>Sedang ({{ $distribusi['sedang'] }})</span>
                <span>Tinggi ({{ $distribusi['tinggi'] }})</span>
            </div>
        </div>

    </div>

    <!-- TABEL REKAPITULASI NILAI LENGKAP -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-[11px] font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-4 sm:px-6 w-14 text-center">No</th>
                        <th class="py-4 px-4 hidden sm:table-cell">NIS</th>
                        <th class="py-4 px-4">Nama Siswa</th>
                        <th class="py-4 px-3 text-center">Tugas</th>
                        <th class="py-4 px-3 text-center">Praktik</th>
                        <th class="py-4 px-3 text-center">UH</th>
                        <th class="py-4 px-3 text-center">UTS</th>
                        <th class="py-4 px-3 text-center">UAS</th>
                        <th class="py-4 px-4 text-center">Rata-Rata</th>
                        <th class="py-4 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($students as $index => $student)
                        @php $grade = $student->grades->first(); @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-4 sm:px-6 text-center font-bold text-slate-400">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-500 hidden sm:table-cell">
                                {{ $student->nis }}
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-900">
                                {{ $student->name }}
                            </td>
                            <td class="py-4 px-3 text-center font-semibold text-slate-700">
                                {{ $grade->tugas ?? '-' }}
                            </td>
                            <td class="py-4 px-3 text-center font-semibold text-slate-700">
                                {{ $grade->praktik ?? '-' }}
                            </td>
                            <td class="py-4 px-3 text-center font-semibold text-slate-700">
                                {{ $grade->ulangan_harian ?? '-' }}
                            </td>
                            <td class="py-4 px-3 text-center font-semibold text-slate-700">
                                {{ $grade->uts ?? '-' }}
                            </td>
                            <td class="py-4 px-3 text-center font-semibold text-slate-700">
                                {{ $grade->uas ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center font-black text-blue-900 text-sm">
                                {{ number_format($grade->rata_rata ?? 0, 1) }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[11px] font-black {{ ($grade->status ?? '') == 'Tuntas' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $grade->status ?? 'Belum' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-16 text-center text-slate-400">
                                Tidak ada data siswa untuk kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
