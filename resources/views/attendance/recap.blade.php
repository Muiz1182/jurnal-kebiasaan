@extends('layouts.app')

@section('title', 'Rekapitulasi Absensi')

@section('content')
<div class="w-full px-6 py-6">

    <!-- Header & Filter -->
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <nav class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-900">Dashboard</a> /
                    <a href="{{ route('attendance.index') }}" class="hover:text-blue-900">Presensi</a> /
                    <span class="text-blue-900">Rekapitulasi</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Rekapitulasi Kelas {{ $selectedClass }}</h1>
                <p class="text-gray-500 text-sm mt-1">Laporan kehadiran siswa periode {{ $monthYear == 'all' ? 'Keseluruhan' : $monthYear }}.</p>
            </div>

            <form method="GET" action="{{ route('attendance.recap') }}" class="flex gap-2 bg-gray-50 p-2 rounded-2xl border border-gray-100 items-center">
                <select name="kelas" class="p-2 bg-transparent border-none text-sm font-bold text-gray-700 focus:ring-0 outline-none cursor-pointer">
                    @foreach($availableClasses as $kelas)
                        <option value="{{ $kelas }}" {{ $selectedClass == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                    @endforeach
                </select>

                <!-- Updated Select Filter -->
                <select name="month" class="p-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 outline-none cursor-pointer">
                    <option value="all" {{ $monthYear == 'all' ? 'selected' : '' }}>Keseluruhan</option>
                    <option value="{{ date('Y-m') }}" {{ $monthYear == date('Y-m') ? 'selected' : '' }}>Bulan Ini</option>
                </select>

                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition">Filter</button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        @php
            $stats = [
                ['label' => 'TOTAL PERTEMUAN', 'val' => array_sum($summary), 'color' => 'bg-blue-900'],
                ['label' => 'TOTAL HADIR', 'val' => $summary['H'] ?? 0, 'color' => 'bg-green-600'],
                ['label' => 'TOTAL SAKIT', 'val' => $summary['S'] ?? 0, 'color' => 'bg-blue-400'],
                ['label' => 'TOTAL IZIN', 'val' => $summary['I'] ?? 0, 'color' => 'bg-yellow-500'],
                ['label' => 'TOTAL ALPHA', 'val' => $summary['A'] ?? 0, 'color' => 'bg-red-600'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">{{ $stat['label'] }}</div>
            <div class="text-2xl font-extrabold text-gray-900">{{ $stat['val'] }} <span class="text-xs font-bold text-gray-400">Jiwa/Kali</span></div>
            <div class="w-full bg-gray-50 h-1.5 rounded-full mt-4"><div class="{{ $stat['color'] }} h-1.5 rounded-full" style="width: 100%"></div></div>
        </div>
        @endforeach
    </div>

    <!-- Chart Section (Hanya muncul jika bukan mode Keseluruhan) -->
    @if(!$isAll)
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-6 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i> Tren Kehadiran Harian ({{ $monthYear }})
        </h3>
        <div class="h-48 flex items-end gap-1.5">
            @php
                $parts = explode('-', $monthYear);
                $y = $parts[0];
                $m = $parts[1];
            @endphp
            @for($d = 1; $d <= cal_days_in_month(CAL_GREGORIAN, $m, $y); $d++)
                @php 
                    $dateKey = sprintf('%s-%02d-%02d', $y, $m, $d);
                    $totalHadir = $dailyTrends[$dateKey] ?? 0;
                    $height = $totalHadir > 0 ? min(($totalHadir / 40) * 100, 100) : 5;
                @endphp
                <div class="w-full bg-blue-600 rounded-t-lg hover:bg-blue-800 transition-all cursor-pointer" 
                     style="height: {{ $height }}%" 
                     title="Tgl {{ $d }}: {{ $totalHadir }} Hadir">
                </div>
            @endfor
        </div>
    </div>
    @endif

    <!-- Tabel Statistik -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Daftar Statistik Siswa</h3>
            <div class="flex gap-2">
                <!-- Tombol Unduh Excel -->
                <a href="{{ route('attendance.export', ['kelas' => $selectedClass, 'month' => $monthYear]) }}" 
                   class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase hover:bg-emerald-700 transition">
                   Unduh Excel
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase hover:bg-gray-800 transition">Cetak</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="p-6">No</th>
                        <th class="p-6">NIS</th>
                        <th class="p-6">Nama Siswa</th>
                        <th class="p-6 text-center">Hadir</th>
                        <th class="p-6 text-center">Sakit</th>
                        <th class="p-6 text-center">Izin</th>
                        <th class="p-6 text-center">Alpha</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm divide-y divide-gray-50">
                    @foreach($students as $index => $student)
                    @php
                        $h = $student->attendances->where('status', 'H')->count();
                        $s = $student->attendances->where('status', 'S')->count();
                        $i = $student->attendances->where('status', 'I')->count();
                        $a = $student->attendances->where('status', 'A')->count();
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-6 font-bold text-gray-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-6 font-bold text-gray-500">{{ $student->nis }}</td>
                        <td class="p-6 font-bold text-gray-800">{{ $student->name }}</td>
                        <td class="p-6 text-center"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg font-black text-xs">{{ $h }}</span></td>
                        <td class="p-6 text-center font-bold">{{ $s }}</td>
                        <td class="p-6 text-center font-bold">{{ $i }}</td>
                        <td class="p-6 text-center font-bold text-red-600">{{ $a }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection