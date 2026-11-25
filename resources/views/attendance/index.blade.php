@extends('layouts.app')

@section('title', 'Presensi Siswa')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- HEADER INFORMASI & RINGKASAN SESI -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Presensi Siswa</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Presensi Kelas {{ $selectedClass ?? (auth()->user()->kelas_mengajar ?: '-') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Catat dan kelola kehadiran harian peserta didik secara akurat.
            </p>
        </div>

        <!-- Badges Informasi Tanggal & Siswa -->
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="flex-1 sm:flex-initial p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center text-sm font-black shrink-0">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Tanggal Sesi</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-800">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>

            <div class="flex-1 sm:flex-initial p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-black shrink-0">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Total Murid</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-800">
                        {{ $students->count() }} Siswa Terdaftar
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR & AKSI CEPAT -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Filter Kelas & Tanggal -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            <div class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-wider shrink-0 hidden sm:flex">
                <i class="fas fa-filter text-blue-900"></i>
                <span>Filter:</span>
            </div>

            <select onchange="window.location.href='?kelas='+encodeURIComponent(this.value)+'&date={{ $date }}'" 
                    class="min-h-[48px] px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer">
                <option value="">Pilih Kelas</option>
                @foreach($availableClasses as $k)
                    <option value="{{ $k }}" {{ ($selectedClass ?? '') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>

            <form action="{{ route('attendance.index') }}" method="GET" class="flex-1 sm:max-w-xs">
                <input type="hidden" name="kelas" value="{{ $selectedClass }}">
                <input type="date" 
                       name="date" 
                       value="{{ $date }}" 
                       onchange="this.form.submit()" 
                       class="w-full min-h-[48px] px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer">
            </form>
        </div>

        <!-- Tombol Aksi: Hadir Semua, Simpan, Cetak -->
        <div class="flex flex-wrap items-center gap-2.5 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
            @if($selectedClass)
                <form action="{{ route('attendance.mark-all') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="kelas" value="{{ $selectedClass }}">
                    <button type="submit" 
                            class="min-h-[48px] px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 font-extrabold text-xs flex items-center gap-1.5 transition cursor-pointer">
                        <i class="fas fa-check-double"></i>
                        <span>Hadir Semua</span>
                    </button>
                </form>
            @endif

            <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <button type="submit" 
                        class="min-h-[48px] px-5 py-2.5 rounded-2xl bg-blue-900 hover:bg-blue-800 text-white font-extrabold text-xs sm:text-sm flex items-center gap-2 shadow-md shadow-blue-900/20 transition cursor-pointer">
                    <i class="fas fa-save"></i>
                    <span>Simpan Presensi</span>
                </button>
            </form>

            <button type="button" 
                    onclick="window.print()" 
                    class="min-h-[48px] px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">Cetak</span>
            </button>
        </div>
    </div>

    <!-- STATISTIK RINGKASAN KEHADIRAN (4 PILLS) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        @php
            $statCards = [
                'H' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-900', 'badge' => 'bg-emerald-600', 'label' => 'Hadir'],
                'S' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-900', 'badge' => 'bg-blue-600', 'label' => 'Sakit'],
                'I' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-900', 'badge' => 'bg-amber-500', 'label' => 'Izin'],
                'A' => ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-900', 'badge' => 'bg-rose-600', 'label' => 'Alpa'],
            ];
        @endphp
        @foreach($statCards as $code => $card)
            <div class="{{ $card['bg'] }} {{ $card['border'] }} border p-4 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider {{ $card['text'] }} block">
                        {{ $card['label'] }}
                    </span>
                    <span class="text-2xl font-black {{ $card['text'] }} mt-0.5 block">
                        {{ $stats[$code] ?? 0 }}
                    </span>
                </div>
                <div class="w-8 h-8 rounded-full {{ $card['badge'] }} text-white font-black text-xs flex items-center justify-center shadow-xs">
                    {{ $code }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- TABEL DATA PRESENSI SISWA -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto min-h-[350px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-[11px] font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-4 sm:px-6 w-14 text-center">No</th>
                        <th class="py-4 px-4">Nama Siswa</th>
                        <th class="py-4 px-4 hidden sm:table-cell">NIS</th>
                        <th class="py-4 px-4 text-center min-w-[200px]">Status Kehadiran</th>
                        <th class="py-4 px-4 min-w-[180px]">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($students as $index => $student)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Nomor -->
                            <td class="py-4 px-4 sm:px-6 text-center font-bold text-slate-400">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Nama Siswa & Foto -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-900 text-white font-extrabold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-extrabold text-slate-900 truncate">{{ $student->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-medium sm:hidden">NIS: {{ $student->nis }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- NIS -->
                            <td class="py-4 px-4 font-bold text-slate-500 hidden sm:table-cell">
                                {{ $student->nis }}
                            </td>

                            <!-- Tombol Radio Kehadiran (H, S, I, A) -->
                            <td class="py-4 px-4 text-center">
                                <div class="flex justify-center items-center gap-1.5 sm:gap-2 attendance-group">
                                    @foreach(['H' => ['bg-emerald-600', 'Hadir'], 'S' => ['bg-blue-600', 'Sakit'], 'I' => ['bg-amber-500', 'Izin'], 'A' => ['bg-rose-600', 'Alpa']] as $status => $data)
                                        @php
                                            $isChecked = (isset($attendances[$student->id]) && $attendances[$student->id]->status == $status);
                                        @endphp
                                        <input type="radio" 
                                               form="attendanceForm" 
                                               name="attendance[{{ $student->id }}][status]" 
                                               value="{{ $status }}" 
                                               id="st_{{ $student->id }}_{{ $status }}" 
                                               class="sr-only status-radio"
                                               {{ $isChecked ? 'checked' : '' }}
                                               onchange="updateButtonColor(this, '{{ $data[0] }}')">
                                        
                                        <label for="st_{{ $student->id }}_{{ $status }}" 
                                               title="{{ $data[1] }}"
                                               class="status-label w-10 h-10 min-w-[40px] min-h-[40px] flex items-center justify-center rounded-xl cursor-pointer border font-black text-xs transition-all {{ $isChecked ? $data[0] . ' text-white border-transparent shadow-sm' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-400' }}">
                                            {{ $status }}
                                        </label>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Input Keterangan -->
                            <td class="py-4 px-4">
                                <input type="text" 
                                       form="attendanceForm" 
                                       name="attendance[{{ $student->id }}][notes]" 
                                       value="{{ $attendances[$student->id]->notes ?? '' }}"
                                       placeholder="Catatan..." 
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400">
                                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl">
                                    👥
                                </div>
                                <p class="font-extrabold text-sm text-slate-700">Data siswa tidak ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Pilih kelas lain atau pastikan data siswa sudah terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function updateButtonColor(radio, colorClass) {
    const group = radio.closest('.attendance-group');
    group.querySelectorAll('.status-label').forEach(label => {
        label.classList.remove('bg-emerald-600', 'bg-blue-600', 'bg-amber-500', 'bg-rose-600', 'text-white', 'border-transparent', 'shadow-sm');
        label.classList.add('bg-white', 'text-slate-500', 'border-slate-200');
    });
    const activeLabel = group.querySelector(`label[for="${radio.id}"]`);
    activeLabel.classList.remove('bg-white', 'text-slate-500', 'border-slate-200');
    activeLabel.classList.add(colorClass, 'text-white', 'border-transparent', 'shadow-sm');
}

document.addEventListener('DOMContentLoaded', function() {
    const map = {'H': 'bg-emerald-600', 'S': 'bg-blue-600', 'I': 'bg-amber-500', 'A': 'bg-rose-600'};
    document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
        if (map[radio.value]) {
            updateButtonColor(radio, map[radio.value]);
        }
    });
});
</script>
@endsection
