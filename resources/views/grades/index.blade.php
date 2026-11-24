@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="w-full px-6 py-6">

    <!-- Header Informasi -->
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <nav class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-900">Dashboard</a> /
                    <span class="text-blue-900">Input Nilai</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Penginputan Nilai</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola pencapaian akademik siswa untuk semester ganjil 2026/2027.</p>
            </div>

            <div class="flex gap-3">
                <div class="border border-gray-100 bg-gray-50 rounded-2xl p-4 flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-gray-400"></i>
                    <div>
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal</div>
                        <div class="font-bold text-gray-800 text-sm">{{ date('d F Y') }}</div>
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

    <!-- Filter & Simpan Bar -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-wrap items-center justify-between gap-6">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="flex items-center gap-3 font-black text-gray-400 uppercase text-[10px] tracking-widest">
                <i class="fas fa-filter"></i> Filter Data:
            </div>
            <form action="{{ route('grades.index') }}" method="GET" class="flex gap-4 flex-wrap">
                <select name="subject" onchange="this.form.submit()" class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-48 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer" required>
                    <option value="">Pilih Mapel</option>
                    @foreach(auth()->user()->subjects as $subj)
                        <option value="{{ $subj->id }}" {{ (isset($selectedSubject) && $selectedSubject == $subj->id) ? 'selected' : '' }}>
                            {{ $subj->name }}
                        </option>
                    @endforeach
                </select>

                <select name="class" onchange="this.form.submit()" class="p-3 bg-gray-50 border border-gray-100 rounded-2xl w-40 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls }}" {{ (isset($selectedClass) && $selectedClass == $cls) ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(isset($selectedSubject))
            <button type="submit" form="gradeForm" class="px-8 py-3 bg-gray-900 text-white rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/10 whitespace-nowrap">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        @endif
    </div>

    <!-- Form Input Nilai -->
    @if(isset($selectedSubject))
    <form action="{{ route('grades.store') }}" method="POST" id="gradeForm" onkeydown="return event.key != 'Enter';">
        @csrf
        <input type="hidden" id="current_subject_id" name="subject_id" value="{{ $selectedSubject }}">
        <input type="hidden" name="class" value="{{ $selectedClass ?? '' }}">

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Daftar Nilai Siswa</h3>
                <div class="flex gap-3">
                    <input type="text" id="columnName" placeholder="Nama Kategori (Contoh: Proyek 1)" class="p-2 border border-gray-100 bg-gray-50 rounded-xl text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-blue-50">
                    <button type="button" onclick="addColumn()" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition">+ Tambah Kolom</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left" id="gradeTable">
                    <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                        <tr id="tableHead">
                            <th class="p-6">No</th>
                            <th class="p-6">NIS</th>
                            <th class="p-6">Nama Siswa</th>
                            <th class="p-6 text-center">Tugas</th>
                            <th class="p-6 text-center">Praktik</th>
                            <th class="p-6 text-center">UH</th>
                            <th class="p-6 text-center">UTS</th>
                            <th class="p-6 text-center">UAS</th>
                            <!-- Kategori Dinamis -->
                            @foreach($categories as $cat)
                                <th class="p-6 text-center text-blue-600">{{ $cat->name }}</th>
                            @endforeach
                            <th class="p-6 text-center">Rata-Rata</th>
                            <th class="p-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm" id="tableBody">
                        @forelse($students as $index => $student)
                        @php $grade = $grades->get($student->id); @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition row-student" data-id="{{ $student->id }}">
                            <td class="p-6 font-bold text-gray-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-6 font-bold text-gray-500">{{ $student->nis }}</td>
                            <td class="p-6 font-bold text-gray-800">{{ $student->name }}</td>
                            <td class="p-4 text-center"><input type="number" name="grades[{{ $student->id }}][tugas]" data-field="tugas" value="{{ $grade->tugas ?? '' }}" class="w-16 p-2 bg-gray-50 border border-gray-100 rounded-lg text-center calc-input font-bold" placeholder="0"></td>
                            <td class="p-4 text-center"><input type="number" name="grades[{{ $student->id }}][praktik]" data-field="praktik" value="{{ $grade->praktik ?? '' }}" class="w-16 p-2 bg-gray-50 border border-gray-100 rounded-lg text-center calc-input font-bold" placeholder="0"></td>
                            <td class="p-4 text-center"><input type="number" name="grades[{{ $student->id }}][ulangan_harian]" data-field="ulangan_harian" value="{{ $grade->ulangan_harian ?? '' }}" class="w-16 p-2 bg-gray-50 border border-gray-100 rounded-lg text-center calc-input font-bold" placeholder="0"></td>
                            <td class="p-4 text-center"><input type="number" name="grades[{{ $student->id }}][uts]" data-field="uts" value="{{ $grade->uts ?? '' }}" class="w-16 p-2 bg-gray-50 border border-gray-100 rounded-lg text-center calc-input font-bold" placeholder="0"></td>
                            <td class="p-4 text-center"><input type="number" name="grades[{{ $student->id }}][uas]" data-field="uas" value="{{ $grade->uas ?? '' }}" class="w-16 p-2 bg-gray-50 border border-gray-100 rounded-lg text-center calc-input font-bold" placeholder="0"></td>
                            
                            <!-- Input Kategori Dinamis -->
                            @foreach($categories as $cat)
                                @php $itemValue = $grade ? $grade->items->where('grade_category_id', $cat->id)->first() : null; @endphp
                                <td class="p-4 text-center">
                                    <input type="number" name="grades[{{ $student->id }}][items][{{ $cat->id }}]" value="{{ $itemValue->value ?? 0 }}" class="w-16 p-2 bg-blue-50/50 border border-blue-100 rounded-lg text-center font-bold text-blue-700">
                                </td>
                            @endforeach

                            <td class="p-6 font-black text-blue-900 text-center text-lg rata-rata-display">
                                {{ number_format($grade->rata_rata ?? 0, 1) }}
                            </td>
                            <td class="p-6 text-center">
                                <span class="status-badge px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest {{ ($grade->status ?? '') == 'Tuntas' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $grade->status ?? 'Belum' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="100%" class="p-12 text-center text-gray-400 italic">Data siswa tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terakhir diperbarui: {{ date('H:i') }} WIB</p>
            </div>
        </div>
    </form>
    @else
        <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-gray-400 font-medium">Silakan pilih Mata Pelajaran dan Kelas untuk mulai menginput nilai.</p>
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
                badge.className = 'status-badge px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest ' + 
                    (data.status === 'Tuntas' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600');
            }
        });
    }

    document.querySelectorAll('.calc-input').forEach(input => {
        input.addEventListener('change', function() { autoSave(this); });
    });

    function addColumn() {
        const columnName = document.getElementById('columnName').value;
        const subjectId = document.getElementById('current_subject_id').value;
        const selectedClass = "{{ $selectedClass ?? '' }}";

        if (!columnName) { alert('Masukkan nama kategori/kolom'); return; }

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
                alert('Gagal menambah kolom');
            }
        });
    }
</script>
@endsection