@extends('layouts.app')

@section('title', 'Input Jurnal Mengajar')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto">

    <!-- HEADER INFORMASI & FILTER TANGGAL -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900 transition">Dashboard</a>
                <span>&gt;</span>
                <span class="text-blue-900 font-bold">Jurnal Mengajar</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Input Jurnal Pembelajaran
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Dokumentasikan materi dan capaian kegiatan belajar mengajar harian.
            </p>
        </div>

        <!-- Panel Filter Tanggal -->
        <div class="w-full md:w-auto flex items-center gap-3 p-2 bg-slate-50 border border-slate-200 rounded-2xl">
            <div class="px-3 py-1 flex items-center gap-2.5">
                <i class="fas fa-calendar-alt text-slate-400 text-sm"></i>
                <div>
                    <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Tanggal Jurnal</span>
                    <input type="date" 
                           id="datePicker" 
                           value="{{ request('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                           class="bg-transparent text-xs sm:text-sm font-extrabold text-slate-800 border-none p-0 focus:outline-none cursor-pointer">
                </div>
            </div>
            <button type="button" 
                    onclick="updateUrl()" 
                    class="min-h-[44px] px-4 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-black rounded-xl transition cursor-pointer shadow-sm shrink-0">
                Terapkan
            </button>
        </div>
    </div>

    <!-- FORM INPUT JURNAL -->
    <form action="{{ route('journal.store') }}" method="POST" class="space-y-6 sm:space-y-8">
        @csrf
        <input type="hidden" name="date" value="{{ request('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}">

        <!-- CARD 1: INFORMASI KELAS & MAPEL -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                    Informasi Pembelajaran
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Pilihan Mapel -->
                <div class="space-y-1.5">
                    <label for="subjectSelect" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Mata Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="subject[]" id="subjectSelect" multiple required class="w-full">
                        <option value="Tidak ada Jam Mengajar">Tidak ada Jam Mengajar</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->name }}">{{ $subj->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilihan Kelas -->
                <div class="space-y-1.5">
                    <label for="classSelect" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Kelas yang Diampu <span class="text-rose-500">*</span>
                    </label>
                    <select name="class_name[]" id="classSelect" multiple class="w-full">
                        @foreach($availableClasses as $kelas)
                            <option value="{{ $kelas }}" 
                                {{ (is_array($selectedKelas) && in_array($kelas, $selectedKelas)) ? 'selected' : '' }}>
                                Kelas {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Field Ko-Kurikuler Dinamis -->
            <div id="kokurikulerField" class="hidden space-y-1.5 pt-2">
                <label for="kokurikulerSelect" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Jenis Kegiatan Ko-Kurikuler
                </label>
                <select name="kokurikuler_type[]" id="kokurikulerSelect" multiple class="w-full">
                    <option value="Senam Anak Indonesia Hebat">Senam Anak Indonesia Hebat</option>
                    <option value="Literasi">Literasi</option>
                    <option value="Sholat Dhuha Berjamaah">Sholat Dhuha Berjamaah</option>
                    <option value="Program Adiwiyata">Program Adiwiyata</option>
                    <option value="Tadarus Bersama di Kelas">Tadarus Bersama di Kelas</option>
                    <option value="Sholat Dhuhur/Jum'at">Sholat Dhuhur/Jum'at</option>
                    <option value="Permainan Tradisional">Permainan Tradisional</option>
                    <option value="Tahfidz">Tahfidz</option>
                </select>
            </div>

            <!-- Jam Pelajaran -->
            <div class="space-y-1.5 pt-2">
                <label for="lessonHoursSelect" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Jam Ke- <span class="text-rose-500">*</span>
                </label>
                <select name="lesson_hours[]" id="lessonHoursSelect" multiple required class="w-full">
                    @for ($i = 0; $i <= 7; $i++)
                        <option value="{{ $i }}">{{ $i === 0 ? '0 (Ko-Kurikuler / Apel)' : 'Jam ke-' . $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Uraian Materi -->
            <div class="space-y-1.5 pt-2">
                <label for="content" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Materi / Topik Pembelajaran <span class="text-rose-500">*</span>
                </label>
                <textarea id="content" 
                          name="content" 
                          required 
                          rows="4" 
                          placeholder="Tuliskan ringkasan materi pokok dan aktivitas yang diajarkan..."
                          class="w-full p-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition text-slate-800 placeholder:text-slate-400 font-medium"></textarea>
            </div>
        </div>

        <!-- CARD 2: CATATAN & CHECKLIST KELAS -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                    Catatan Dinamika Kelas &amp; Kejadian
                </h2>
            </div>

            <p class="text-xs text-slate-500 font-medium">
                Pilih situasi atau catatan relevan selama jam mengajar berlangsung:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @php
                    $options = [
                        "Proses pembelajaran berjalan dengan lancar dan tertib",
                        "Peserta didik melaksanakan tugas dengan tuntas",
                        "Peserta didik dapat poin positif tercatat di SISPODIS",
                        "Peserta didik dapat poin negatif tercatat di SISPODIS",
                        "Pelaksanaan Kokurikuler berjalan dengan baik",
                        "Masih ada kendala dalam pelaksanaan aktivitas",
                        "Terdapat tugas atau asesmen harian",
                        "Pemahaman tentang perilaku akhlak mulia dan kesantunan",
                        "Penguatan pendidikan karakter dan sopan santun",
                        "Melakukan kegiatan positif pembiasaan lainnya"
                    ];
                @endphp
                @foreach($options as $option)
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 hover:bg-blue-50/50 border border-slate-200/70 hover:border-blue-200 transition cursor-pointer text-xs font-semibold text-slate-700 select-none">
                        <input type="checkbox" 
                               name="notes_checklist[]" 
                               value="{{ $option }}" 
                               class="w-4 h-4 rounded-md border-slate-300 text-blue-900 focus:ring-blue-900 shrink-0 cursor-pointer">
                        <span class="leading-relaxed">{{ $option }}</span>
                    </label>
                @endforeach
            </div>

            <!-- Catatan Tambahan Manual -->
            <div class="space-y-1.5 pt-3">
                <label for="notes_text" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Catatan Tambahan Khusus (Opsional)
                </label>
                <textarea id="notes_text" 
                          name="notes_text" 
                          rows="3" 
                          placeholder="Tuliskan kendala khusus, catatan siswa tertentu, atau tindak lanjut bila ada..."
                          class="w-full p-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition text-slate-800 placeholder:text-slate-400 font-medium"></textarea>
            </div>
        </div>

        <!-- TOMBOL SIMPAN JURNAL -->
        <button type="submit" 
                class="w-full min-h-[52px] bg-blue-900 hover:bg-blue-800 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-xl shadow-blue-900/25 flex items-center justify-center gap-2.5 transition cursor-pointer">
            <i class="fas fa-save text-base"></i>
            <span>Simpan Jurnal Mengajar Hari Ini</span>
        </button>
    </form>

</div>

<script>
    function updateUrl() {
        const date = document.getElementById('datePicker').value;
        const classSelect = document.getElementById('classSelect');
        const selectedClasses = Array.from(classSelect.selectedOptions).map(option => option.value);
        
        let url = window.location.pathname + '?tanggal=' + date;
        if (selectedClasses.length > 0) {
            url += '&' + selectedClasses.map(k => 'kelas[]=' + encodeURIComponent(k)).join('&');
        }
        window.location.href = url;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const subjectEl = document.getElementById('subjectSelect');
        const classEl = document.getElementById('classSelect');
        
        let choicesClass;

        if (typeof Choices !== 'undefined') {
            new Choices(subjectEl, { removeItemButton: true, searchEnabled: true, itemSelectText: '' });
            choicesClass = new Choices(classEl, { removeItemButton: true, searchEnabled: true, itemSelectText: '' });
            new Choices(document.getElementById('kokurikulerSelect'), { removeItemButton: true, searchEnabled: true, itemSelectText: '' });
            new Choices(document.getElementById('lessonHoursSelect'), { removeItemButton: true, searchEnabled: true, itemSelectText: '' });
        }

        if (subjectEl) {
            subjectEl.addEventListener('change', function() {
                const selectedValues = Array.from(subjectEl.selectedOptions).map(option => option.value);
                if (selectedValues.includes('Tidak ada Jam Mengajar')) {
                    if (choicesClass) choicesClass.removeActiveItems();
                    classEl.disabled = true;
                } else {
                    classEl.disabled = false;
                }
                
                const kokuField = document.getElementById('kokurikulerField');
                if (selectedValues.includes('Ko-Kurikuler')) {
                    kokuField.classList.remove('hidden');
                } else {
                    kokuField.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection
