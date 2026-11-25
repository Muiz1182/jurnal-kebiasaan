@extends('layouts.app')

@section('title', 'Input Nilai Siswa')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- HEADER INFORMASI & RINGKASAN AKADEMIK -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Penginputan Nilai</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Penilaian Akademik Siswa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Kelola nilai tugas, praktik, ulangan harian, UTS, dan UAS semester aktif.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center text-sm font-black shrink-0">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Tanggal Input</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-800">{{ date('d F Y') }}</span>
                </div>
            </div>

            <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-black shrink-0">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Total Murid</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-800">{{ $students->count() }} Siswa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR & SIMPAN PERUBAHAN -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Filter Mapel & Kelas -->
        <form action="{{ route('grades.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            <div class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-wider shrink-0 hidden sm:flex">
                <i class="fas fa-filter text-blue-900"></i>
                <span>Filter:</span>
            </div>

            <select name="subject" 
                    onchange="this.form.submit()" 
                    class="min-h-[48px] px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer" 
                    required>
                <option value="">Pilih Mata Pelajaran</option>
                @foreach(auth()->user()->subjects as $subj)
                    <option value="{{ $subj->id }}" {{ (isset($selectedSubject) && $selectedSubject == $subj->id) ? 'selected' : '' }}>
                        {{ $subj->name }}
                    </option>
                @endforeach
            </select>

            <select name="class" 
                    onchange="this.form.submit()" 
                    class="min-h-[48px] px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 cursor-pointer" 
                    required>
                <option value="">Pilih Kelas</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls }}" {{ (isset($selectedClass) && $selectedClass == $cls) ? 'selected' : '' }}>
                        Kelas {{ $cls }}
                    </option>
                @endforeach
            </select>
        </form>

        <!-- Tombol Aksi Simpan & Rekap -->
        <div class="flex items-center gap-2.5 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
            @if(isset($selectedSubject))
                <button type="submit" 
                        form="gradeForm" 
                        class="min-h-[48px] px-6 py-2.5 rounded-2xl bg-blue-900 hover:bg-blue-800 text-white font-extrabold text-xs sm:text-sm flex items-center gap-2 shadow-md shadow-blue-900/20 transition cursor-pointer">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            @endif

            <a href="{{ route('grades.recap', ['class' => $selectedClass ?? '', 'subject' => $selectedSubject ?? '']) }}" 
               class="min-h-[48px] px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition">
                <i class="fas fa-chart-bar"></i>
                <span class="hidden sm:inline">Rekap Nilai</span>
            </a>
        </div>
    </div>

    <!-- FORM INPUT NILAI -->
    @if(isset($selectedSubject))
    <form action="{{ route('grades.store') }}" method="POST" id="gradeForm" onkeydown="return event.key != 'Enter';">
        @csrf
        <input type="hidden" id="current_subject_id" name="subject_id" value="{{ $selectedSubject }}">
        <input type="hidden" name="class" value="{{ $selectedClass ?? '' }}">

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            
            <!-- Toolbar Tambah Kolom Kategori Dinamis -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-black text-base text-slate-900 tracking-tight">Lembar Penilaian Siswa</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Nilai otomatis tersimpan dan dihitung rata-ratanya</p>
                </div>

                <div class="flex items-center gap-2">
                    <input type="text" 
                           id="columnName" 
                           placeholder="Kategori baru (cth: Proyek 1)" 
                           class="min-h-[44px] px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900">
                    <button type="button" 
                            onclick="addColumn()" 
                            class="min-h-[44px] px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-xl font-extrabold text-xs transition cursor-pointer shadow-sm shrink-0">
                        + Tambah Kolom
                    </button>
                </div>
            </div>

            <!-- Tabel Input Nilai -->
            <div class="overflow-x-auto min-h-[350px]">
                <table class="w-full text-left border-collapse" id="gradeTable">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-500 text-[11px] font-extrabold uppercase tracking-wider border-b border-slate-200" id="tableHead">
                            <th class="py-4 px-4 sm:px-6 w-14 text-center">No</th>
                            <th class="py-4 px-4 hidden sm:table-cell">NIS</th>
                            <th class="py-4 px-4 min-w-[180px]">Nama Siswa</th>
                            <th class="py-4 px-3 text-center min-w-[70px]">Tugas</th>
                            <th class="py-4 px-3 text-center min-w-[70px]">Praktik</th>
                            <th class="py-4 px-3 text-center min-w-[70px]">UH</th>
                            <th class="py-4 px-3 text-center min-w-[70px]">UTS</th>
                            <th class="py-4 px-3 text-center min-w-[70px]">UAS</th>
                            <!-- Kolom Kategori Dinamis -->
                            @foreach($categories as $cat)
                                <th class="py-4 px-3 text-center text-blue-900 min-w-[80px]">{{ $cat->name }}</th>
                            @endforeach
                            <th class="py-4 px-4 text-center min-w-[90px]">Rata-Rata</th>
                            <th class="py-4 px-4 text-center min-w-[100px]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm" id="tableBody">
                        @forelse($students as $index => $student)
                        @php $grade = $grades->get($student->id); @endphp
                        <tr class="hover:bg-slate-50/80 transition row-student" data-id="{{ $student->id }}">
                            <td class="py-4 px-4 sm:px-6 text-center font-bold text-slate-400">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-500 hidden sm:table-cell">
                                {{ $student->nis }}
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-extrabold text-slate-900">{{ $student->name }}</p>
                                <p class="text-[10px] text-slate-400 font-medium sm:hidden">NIS: {{ $student->nis }}</p>
                            </td>

                            <!-- Input Komponen Nilai Standar -->
                            <td class="py-3 px-2 text-center">
                                <input type="number" 
                                       inputmode="decimal"
                                       name="grades[{{ $student->id }}][tugas]" 
                                       data-field="tugas" 
                                       value="{{ $grade->tugas ?? '' }}" 
                                       class="w-16 h-10 px-2 bg-slate-50 border border-slate-200 rounded-xl text-center calc-input font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition" 
                                       placeholder="0">
                            </td>
                            <td class="py-3 px-2 text-center">
                                <input type="number" 
                                       inputmode="decimal"
                                       name="grades[{{ $student->id }}][praktik]" 
                                       data-field="praktik" 
                                       value="{{ $grade->praktik ?? '' }}" 
                                       class="w-16 h-10 px-2 bg-slate-50 border border-slate-200 rounded-xl text-center calc-input font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition" 
                                       placeholder="0">
                            </td>
                            <td class="py-3 px-2 text-center">
                                <input type="number" 
                                       inputmode="decimal"
                                       name="grades[{{ $student->id }}][ulangan_harian]" 
                                       data-field="ulangan_harian" 
                                       value="{{ $grade->ulangan_harian ?? '' }}" 
                                       class="w-16 h-10 px-2 bg-slate-50 border border-slate-200 rounded-xl text-center calc-input font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition" 
                                       placeholder="0">
                            </td>
                            <td class="py-3 px-2 text-center">
                                <input type="number" 
                                       inputmode="decimal"
                                       name="grades[{{ $student->id }}][uts]" 
                                       data-field="uts" 
                                       value="{{ $grade->uts ?? '' }}" 
                                       class="w-16 h-10 px-2 bg-slate-50 border border-slate-200 rounded-xl text-center calc-input font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition" 
                                       placeholder="0">
                            </td>
                            <td class="py-3 px-2 text-center">
                                <input type="number" 
                                       inputmode="decimal"
                                       name="grades[{{ $student->id }}][uas]" 
                                       data-field="uas" 
                                       value="{{ $grade->uas ?? '' }}" 
                                       class="w-16 h-10 px-2 bg-slate-50 border border-slate-200 rounded-xl text-center calc-input font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition" 
                                       placeholder="0">
                            </td>
                            
                            <!-- Input Kategori Dinamis -->
                            @foreach($categories as $cat)
                                @php $itemValue = $grade ? $grade->items->where('grade_category_id', $cat->id)->first() : null; @endphp
                                <td class="py-3 px-2 text-center">
                                    <input type="number" 
                                           inputmode="decimal"
                                           name="grades[{{ $student->id }}][items][{{ $cat->id }}]" 
                                           value="{{ $itemValue->value ?? 0 }}" 
                                           class="w-16 h-10 px-2 bg-blue-50/70 border border-blue-200 rounded-xl text-center font-bold text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                                </td>
                            @endforeach

                            <!-- Rata-Rata -->
                            <td class="py-4 px-4 text-center font-black text-blue-900 text-base rata-rata-display">
                                {{ number_format($grade->rata_rata ?? 0, 1) }}
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 text-center">
                                <span class="status-badge px-3 py-1 rounded-full text-[11px] font-black {{ ($grade->status ?? '') == 'Tuntas' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $grade->status ?? 'Belum' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="100%" class="py-16 text-center text-slate-400">
                                Tidak ada data siswa untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 sm:p-5 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-semibold">
                <span>Auto-save aktif saat nilai diubah.</span>
                <span>Terakhir diperbarui: {{ date('H:i') }} WIB</span>
            </div>
        </div>
    </form>
    @else
        <div class="py-20 text-center bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-2xl border border-blue-100">
                📊
            </div>
            <h3 class="font-black text-base text-slate-800">Pilih Mata Pelajaran dan Kelas</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                Gunakan dropdown di atas untuk membuka formulir lembar penilaian akademik siswa.
            </p>
        </div>
    @endif
</div>

<script>
    function updateFrontEndAverage(row) {
        const inputs = row.querySelectorAll('.calc-input');
        let total = 0;
        let count = 0;
        inputs.forEach(input => {
            if(input.value !== "" && !isNaN(parseFloat(input.value))) {
                total += parseFloat(input.value);
                count++;
            }
        });
        row.querySelector('.rata-rata-display').innerText = count > 0 ? (total / count).toFixed(1) : "0.0";
    }

    function autoSave(input) {
        const row = input.closest('tr');
        updateFrontEndAverage(row);
        const studentId = row.getAttribute('data-id');
        const field = input.getAttribute('data-field');
        const value = input.value;
        const subjectId = document.getElementById('current_subject_id').value;

        fetch('{{ route("grades.autosave") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ student_id: studentId, subject_id: subjectId, field: field, value: value })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success && data.status) {
                const badge = row.querySelector('.status-badge');
                badge.innerText = data.status;
                badge.className = 'status-badge px-3 py-1 rounded-full text-[11px] font-black ' + 
                    (data.status === 'Tuntas' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200');
            }
        });
    }

    document.querySelectorAll('.calc-input').forEach(input => {
        input.addEventListener('change', function() { autoSave(this); });
    });

    function addColumn() {
        const columnName = document.getElementById('columnName').value.trim();
        const subjectId = document.getElementById('current_subject_id').value;
        const selectedClass = "{{ $selectedClass ?? '' }}";

        if (!columnName) { 
            alert('Masukkan nama kategori atau kolom baru.'); 
            return; 
        }

        fetch('{{ route("grades.addColumn") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ column_name: columnName, subject_id: subjectId, class: selectedClass })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload(); 
            } else {
                alert('Gagal menambah kolom kategori');
            }
        });
    }
</script>
@endsection
