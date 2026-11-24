@extends('layouts.app')

@section('title', 'Rekap Nilai Akademik')

@section('content')
<div class="w-full px-6 py-6">

    <!-- Header Rekap -->
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <nav class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-900">Dashboard</a> /
                    <a href="{{ route('grades.index') }}" class="hover:text-blue-900">Input Nilai</a> /
                    <span class="text-blue-900">Rekap Nilai</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Rekap Nilai Akademik</h1>
                <p class="text-gray-500 text-sm mt-1">Ringkasan pencapaian belajar siswa per semester.</p>
            </div>
            
            <div class="flex gap-3">
                <!-- Tombol Export Excel yang diperbarui -->
                <a href="{{ route('grades.export', ['class' => $selectedClass, 'subject' => $selectedSubject]) }}" 
                   class="px-6 py-3 bg-green-50 text-green-700 rounded-2xl font-bold hover:bg-green-100 transition flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <button onclick="window.print()" class="px-6 py-3 bg-gray-900 rounded-2xl font-bold text-white hover:bg-gray-800 transition flex items-center gap-2 shadow-lg shadow-gray-900/10">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Data -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-3 font-black text-gray-400 uppercase text-[10px] tracking-widest">
            <i class="fas fa-filter"></i> Filter Data:
        </div>
        <form action="{{ route('grades.recap') }}" method="GET" class="flex gap-4 flex-wrap">
            <select name="class" onchange="this.form.submit()" class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-40 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                @foreach($classes as $class)
                    <option value="{{ $class }}" {{ $selectedClass == $class ? 'selected' : '' }}>Kelas {{ $class }}</option>
                @endforeach
            </select>

            <select name="subject" onchange="this.form.submit()" class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-48 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}" {{ (request('subject') == $subj->id || (!request('subject') && $selectedSubject == $subj->id)) ? 'selected' : '' }}>
                        {{ $subj->name }}
                    </option>
                @endforeach
            </select>
            
            <select class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-40 text-sm font-bold text-gray-400 cursor-not-allowed" disabled>
                <option>Semester Ganjil</option>
            </select>
        </form>
    </div>

    <!-- Cards Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Rata-rata Kelas</div>
            <div class="text-5xl font-extrabold text-gray-900">
                @php 
                    $gradesData = $students->pluck('grades')->collapse();
                    $avgGrade = $gradesData->avg('rata_rata') ?? 0; 
                @endphp
                {{ number_format($avgGrade, 1) }}
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Siswa Terbaik</div>
            @php 
                $bestStudent = $students->filter(function($s) {
                    $grade = $s->grades->first();
                    return $grade && $grade->rata_rata > 0;
                })
                ->sortByDesc(fn($s) => $s->grades->first()->rata_rata)
                ->first(); 
            @endphp
            <div class="text-lg font-bold text-gray-900 truncate mt-1">
                {{ $bestStudent ? $bestStudent->name : 'Belum ada data' }}
            </div>
            <div class="w-full bg-gray-100 h-2 rounded-full mt-4 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $bestStudent ? 100 : 0 }}%"></div>
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Distribusi Nilai</div>
            <div class="flex items-end gap-1 h-12 mt-2">
                @php $totalDist = array_sum($distribusi) ?: 1; @endphp
                <div class="w-full bg-red-100 rounded-t-lg" style="height: {{ ($distribusi['rendah'] / $totalDist) * 100 }}%" title="Rendah"></div>
                <div class="w-full bg-yellow-100 rounded-t-lg" style="height: {{ ($distribusi['sedang'] / $totalDist) * 100 }}%" title="Sedang"></div>
                <div class="w-full bg-blue-600 rounded-t-lg" style="height: {{ ($distribusi['tinggi'] / $totalDist) * 100 }}%" title="Tinggi"></div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="p-6">No</th>
                        <th class="p-6">Nama Siswa</th>
                        <th class="p-6 text-center">Tugas</th>
                        <th class="p-6 text-center">Praktik</th>
                        <th class="p-6 text-center">UH</th>
                        <th class="p-6 text-center">UTS</th>
                        <th class="p-6 text-center">UAS</th>
                        
                        {{-- Header Dinamis --}}
                        @foreach($categories as $category)
                            <th class="p-6 text-center">{{ strtoupper($category->name) }}</th>
                        @endforeach

                        <th class="p-6 text-center">Nilai Akhir</th>
                        <th class="p-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($students as $index => $student)
                    @php $grade = $student->grades->first(); @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="p-6 font-bold text-gray-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-6 font-bold text-gray-800">
                            {{ $student->name }} 
                            <div class="text-[11px] text-gray-400 font-normal">NIS: {{ $student->nis }}</div>
                        </td>
                        <td class="p-6 text-center font-medium">{{ $grade->tugas ?? '-' }}</td>
                        <td class="p-6 text-center font-medium">{{ $grade->praktik ?? '-' }}</td>
                        <td class="p-6 text-center font-medium">{{ $grade->ulangan_harian ?? '-' }}</td>
                        <td class="p-6 text-center font-medium">{{ $grade->uts ?? '-' }}</td>
                        <td class="p-6 text-center font-medium">{{ $grade->uas ?? '-' }}</td>

                        {{-- Data Dinamis --}}
                        @foreach($categories as $category)
                            @php
                                $item = $grade ? $grade->items->where('grade_category_id', $category->id)->first() : null;
                            @endphp
                            <td class="p-6 text-center font-medium">{{ $item ? $item->value : 0 }}</td>
                        @endforeach
                        
                        <td class="p-6 font-black text-blue-900 text-center text-lg">{{ number_format($grade->rata_rata ?? 0, 1) }}</td>
                        <td class="p-6 text-center">
                            @if($grade)
                                <span class="px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest {{ $grade->status == 'Tuntas' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $grade->status }}
                                </span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="100%" class="p-12 text-center text-gray-400 italic">Tidak ada data untuk filter yang dipilih.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection