@extends('layouts.app')

@section('title', 'Rekapitulasi Presensi')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- HEADER & FILTER PERIODE -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <a href="{{ route('attendance.index') }}" class="hover:text-blue-900 transition">Presensi</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Rekapitulasi</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Rekapitulasi Presensi Kelas {{ $selectedClass }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Laporan kehadiran kumulatif siswa periode {{ $monthYear == 'all' ? 'Keseluruhan' : \Carbon\Carbon::parse($monthYear.'-01')->translatedFormat('F Y') }}.
            </p>
        </div>

        <!-- Form Filter Kelas & Bulan -->
        <form method="GET" action="{{ route('attendance.recap') }}" class="flex flex-wrap items-center gap-2.5 p-2 bg-slate-50 rounded-2xl border border-slate-200 w-full lg:w-auto">
            <select name="kelas" class="min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer">
                @foreach($availableClasses as $kelas)
                    <option value="{{ $kelas }}" {{ $selectedClass == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                @endforeach
            </select>

            <select name="month" class="min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer">
                <option value="all" {{ $monthYear == 'all' ? 'selected' : '' }}>Semua Periode</option>
                <option value="{{ date('Y-m') }}" {{ $monthYear == date('Y-m') ? 'selected' : '' }}>Bulan Ini ({{ date('M Y') }})</option>
            </select>

            <button type="submit" class="min-h-[44px] px-5 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-xl font-bold text-xs transition cursor-pointer shadow-sm">
                Terapkan
            </button>
        </form>
    </div>

    <!-- STATS CARDS (5 METRIK) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        @php
            $stats = [
                ['label' => 'Total Sesi', 'val' => array_sum($summary), 'unit' => 'Pertemuan', 'bg' => 'bg-slate-50', 'text' => 'text-slate-900', 'bar' => 'bg-blue-900'],
                ['label' => 'Total Hadir', 'val' => $summary['H'] ?? 0, 'unit' => 'Siswa/Kali', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-900', 'bar' => 'bg-emerald-600'],
                ['label' => 'Total Sakit', 'val' => $summary['S'] ?? 0, 'unit' => 'Siswa/Kali', 'bg' => 'bg-blue-50', 'text' => 'text-blue-900', 'bar' => 'bg-blue-600'],
                ['label' => 'Total Izin', 'val' => $summary['I'] ?? 0, 'unit' => 'Siswa/Kali', 'bg' => 'bg-amber-50', 'text' => 'text-amber-900', 'bar' => 'bg-amber-500'],
                ['label' => 'Total Alpa', 'val' => $summary['A'] ?? 0, 'unit' => 'Siswa/Kali', 'bg' => 'bg-rose-50', 'text' => 'text-rose-900', 'bar' => 'bg-rose-600'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="{{ $stat['bg'] }} p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 block">
                    {{ $stat['label'] }}
                </span>
                <span class="text-2xl sm:text-3xl font-black {{ $stat['text'] }} mt-1 block">
                    {{ $stat['val'] }}
                </span>
                <span class="text-[11px] text-slate-400 font-bold block mt-0.5">
                    {{ $stat['unit'] }}
                </span>
                <div class="w-full bg-slate-200/80 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="{{ $stat['bar'] }} h-full rounded-full" style="width: 100%"></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- TREN KEHADIRAN HARIAN (JIKA PILIH BULAN) -->
    @if(!$isAll)
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h3 class="font-black text-base text-slate-900 tracking-tight">Tren Kehadiran Harian</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Jumlah siswa hadir per tanggal pada bulan {{ $monthYear }}</p>
                </div>
            </div>
            
            <div class="h-44 sm:h-52 flex items-end gap-1 sm:gap-1.5 overflow-x-auto pb-2">
                @php
                    $parts = explode('-', $monthYear);
                    $y = $parts[0];
                    $m = $parts[1];
                    $totalDays = cal_days_in_month(CAL_GREGORIAN, $m, $y);
                @endphp
                @for($d = 1; $d <= $totalDays; $d++)
                    @php 
                        $dateKey = sprintf('%s-%02d-%02d', $y, $m, $d);
                        $totalHadir = $dailyTrends[$dateKey] ?? 0;
                        $height = $totalHadir > 0 ? min(($totalHadir / 40) * 100, 100) : 6;
                    @endphp
                    <div class="flex-1 min-w-[12px] flex flex-col items-center gap-1 group">
                        <div class="w-full rounded-t-md transition-all cursor-pointer {{ $totalHadir > 0 ? 'bg-blue-900 hover:bg-blue-700' : 'bg-slate-200 hover:bg-slate-300' }}" 
                             style="height: {{ $height }}%" 
                             title="Tanggal {{ $d }}: {{ $totalHadir }} Siswa Hadir">
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 group-hover:text-blue-900">{{ $d }}</span>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    <!-- TABEL STATISTIK DETAIL PER SISWA -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-black text-base text-slate-900 tracking-tight">Rekapitulasi Kehadiran Tiap Siswa</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Rincian persentase dan akumulasi ketidakhadiran</p>
            </div>
            
            <div class="flex items-center gap-2.5">
                <a href="{{ route('attendance.export', ['kelas' => $selectedClass, 'month' => $monthYear]) }}" 
                   class="min-h-[44px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-file-excel"></i>
                    <span>Ekspor Excel</span>
                </a>
                <button onclick="window.print()" 
                        class="min-h-[44px] px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-print"></i>
                    <span>Cetak</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-[11px] font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-4 sm:px-6 w-14 text-center">No</th>
                        <th class="py-4 px-4">Nama Siswa</th>
                        <th class="py-4 px-4 hidden sm:table-cell">NIS</th>
                        <th class="py-4 px-4 text-center">Hadir</th>
                        <th class="py-4 px-4 text-center">Sakit</th>
                        <th class="py-4 px-4 text-center">Izin</th>
                        <th class="py-4 px-4 text-center">Alpa</th>
                        <th class="py-4 px-4 text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($students as $index => $student)
                        @php
                            $rec = $recapData[$student->id] ?? ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0, 'total' => 0];
                            $pct = $rec['total'] > 0 ? round(($rec['H'] / $rec['total']) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-4 sm:px-6 text-center font-bold text-slate-400">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-900">
                                {{ $student->name }}
                            </td>
                            <td class="py-4 px-4 font-medium text-slate-500 hidden sm:table-cell">
                                {{ $student->nis }}
                            </td>
                            <td class="py-4 px-4 text-center font-extrabold text-emerald-700">
                                {{ $rec['H'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-blue-700">
                                {{ $rec['S'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-amber-700">
                                {{ $rec['I'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-rose-700">
                                {{ $rec['A'] }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $pct >= 85 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($pct >= 70 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $pct }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center text-slate-400">
                                Tidak ada data rekapitulasi untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
