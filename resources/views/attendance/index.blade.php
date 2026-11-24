@extends('layouts.app')

@section('title', 'Presensi Siswa')

@section('content')
<div class="w-full px-6 py-6">

    <!-- Header Informasi -->
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <nav class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-900">Dashboard</a> /
                    <span class="text-blue-900">Presensi Murid</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Presensi Kelas {{ $selectedClass ?? (auth()->user()->kelas_mengajar ?: '...') }}</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola kehadiran siswa secara digital. Data akan otomatis tersinkronisasi.</p>
            </div>

            <div class="flex gap-3">
                <div class="border border-gray-100 bg-gray-50 rounded-2xl p-4 flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-gray-400"></i>
                    <div>
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal</div>
                        <div class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}</div>
                    </div>
                </div>
                <div class="border border-gray-100 bg-gray-50 rounded-2xl p-4 flex items-center gap-3">
                    <i class="fas fa-users text-gray-400"></i>
                    <div>
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Siswa</div>
                        <div class="font-bold text-gray-800 text-sm">{{ $students->count() }} Peserta Didik</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-3 font-black text-gray-400 uppercase text-[10px] tracking-widest">
            <i class="fas fa-filter"></i> Filter Data:
        </div>
        
        <div class="flex gap-4 flex-wrap w-full md:w-auto items-center">
            <select onchange="window.location.href='?kelas='+this.value+'&date={{ $date }}'" class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-40 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                <option value="">Pilih Kelas</option>
                @foreach($availableClasses as $k)
                    <option value="{{ $k }}" {{ ($selectedClass ?? '') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>

            <form action="{{ route('attendance.index') }}" method="GET" class="flex gap-4">
                <input type="hidden" name="kelas" value="{{ $selectedClass }}">
                <input type="date" name="date" onchange="this.form.submit()" value="{{ $date }}" class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-48 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
            </form>
            
            <div class="ml-auto flex gap-2">
                <!-- Tombol Hadir Semua (Trigger Form) -->
                @if($selectedClass)
                <form action="{{ route('attendance.mark-all') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="kelas" value="{{ $selectedClass }}">
                    <button type="submit" class="px-6 py-3 bg-green-50 text-green-700 rounded-2xl font-bold text-sm hover:bg-green-100 transition">Hadir Semua</button>
                </form>
                @endif

                <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">Simpan Presensi</button>
                </form>
                
                <button type="button" onclick="window.print()" class="px-6 py-3 bg-white border border-gray-100 rounded-2xl font-bold text-gray-700 text-sm hover:bg-gray-50 transition">Cetak</button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="flex flex-wrap gap-4 mb-8">
        @foreach(['H' => ['bg-green-600', 'Hadir'], 'I' => ['bg-yellow-500', 'Izin'], 'S' => ['bg-blue-600', 'Sakit'], 'A' => ['bg-red-600', 'Alpha']] as $code => $data)
        <div class="bg-white px-6 py-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <span class="w-3 h-3 rounded-full {{ $data[0] }}"></span>
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ $data[1] }}: {{ $stats[$code] ?? 0 }}</span>
        </div>
        @endforeach
    </div>

    <!-- Tabel Presensi -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="p-6">No</th>
                    <th class="p-6">NIS</th>
                    <th class="p-6">Nama Siswa</th>
                    <th class="p-6 text-center">Kehadiran</th>
                    <th class="p-6">Keterangan</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($students as $index => $student)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="p-6 font-bold text-gray-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-6 font-bold text-gray-500">{{ $student->nis }}</td>
                    <td class="p-6 font-bold text-gray-800 flex items-center gap-3">
                        <img src="{{ $student->foto_url ?? asset('images/default.png') }}" class="w-8 h-8 rounded-full object-cover">
                        {{ $student->name }}
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2 attendance-group">
                            @foreach(['H' => 'bg-green-600', 'S' => 'bg-blue-600', 'I' => 'bg-yellow-500', 'A' => 'bg-red-600'] as $status => $color)
                            <input type="radio" form="attendanceForm" name="attendance[{{ $student->id }}][status]" value="{{ $status }}" 
                                id="st_{{ $student->id }}_{{ $status }}" class="sr-only status-radio"
                                {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == $status) ? 'checked' : '' }}
                                onchange="updateButtonColor(this, '{{ $color }}')">
                            
                            <label for="st_{{ $student->id }}_{{ $status }}" 
                                class="status-label w-10 h-10 flex items-center justify-center rounded-xl cursor-pointer border border-gray-100 font-black text-[10px] bg-white text-gray-400 transition-all hover:border-gray-300">
                                {{ $status }}
                            </label>
                            @endforeach
                        </div>
                    </td>
                    <td class="p-4">
                        <input type="text" form="attendanceForm" name="attendance[{{ $student->id }}][notes]" 
                            value="{{ $attendances[$student->id]->notes ?? '' }}"
                            placeholder="Keterangan..." class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl text-sm outline-none focus:ring-4 focus:ring-blue-50">
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-12 text-center text-gray-400 italic">Data siswa tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function updateButtonColor(radio, colorClass) {
    const group = radio.closest('.attendance-group');
    group.querySelectorAll('.status-label').forEach(label => {
        label.classList.remove('bg-green-600', 'bg-blue-600', 'bg-yellow-500', 'bg-red-600', 'text-white', 'border-transparent');
        label.classList.add('bg-white', 'text-gray-400');
    });
    const activeLabel = group.querySelector(`label[for="${radio.id}"]`);
    activeLabel.classList.remove('bg-white', 'text-gray-400');
    activeLabel.classList.add(colorClass, 'text-white', 'border-transparent');
}

// Inisialisasi warna saat halaman dimuat
document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
    const map = {'H': 'bg-green-600', 'S': 'bg-blue-600', 'I': 'bg-yellow-500', 'A': 'bg-red-600'};
    updateButtonColor(radio, map[radio.value]);
});
</script>
@endsection