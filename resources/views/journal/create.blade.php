@extends('layouts.app')

@section('title', 'Input Jurnal Baru')

@section('content')
<div class="w-full px-6 py-6">
    <!-- Header Informasi -->
    <div class="mb-8 p-8 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <nav class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-900">Dashboard</a> /
                <span class="text-blue-900">Jurnal Mengajar</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Input Jurnal Mengajar Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Lengkapi detail kegiatan belajar mengajar untuk kelas yang Anda ampu hari ini.</p>
        </div>

        <!-- Panel Filter (Tanggal & Kelas) -->
        <div class="flex flex-col md:flex-row items-center gap-4 border border-gray-100 p-4 rounded-2xl bg-gray-50">
            <div>
                <p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Pilih Tanggal</p>
                <input type="date" name="journal_date" id="datePicker" 
                    value="{{ request('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                    class="bg-transparent text-sm font-bold text-gray-800 border-none focus:ring-0 cursor-pointer p-0">
            </div>
            <button type="button" onclick="updateUrl()" class="px-4 py-2 bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-800 transition">
                Terapkan
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-red-50 border border-red-100 text-red-600 rounded-3xl">
            <p class="font-black uppercase text-[10px] tracking-widest mb-2">Oops! Terjadi kesalahan:</p>
            <ul class="list-disc ml-5 text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('journal.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        <input type="hidden" name="date" value="{{ request('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}">
        
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1 h-5 bg-blue-900 rounded-full"></span> Informasi Pembelajaran
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Mata Pelajaran</label>
                        <select name="subject[]" id="subjectSelect" multiple required class="w-full">
                            <option value="Tidak ada Jam Mengajar">Tidak ada Jam Mengajar</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->name }}">{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kelas</label>
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

                <div id="kokurikulerField" class="mt-4 hidden">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenis Kegiatan Ko-Kurikuler</label>
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

                <div class="mt-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jam Ke</label>
                    <select name="lesson_hours[]" id="lessonHoursSelect" multiple required class="w-full">
                        @for ($i = 0; $i <= 7; $i++)
                            <option value="{{ $i }}">{{ $i === 0 ? '0 (Ko-Kurikuler)' : $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mt-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Materi</label>
                    <textarea name="content" required rows="4" class="w-full p-3 border border-gray-100 rounded-2xl bg-gray-50 focus:ring-4 focus:ring-blue-50 outline-none font-bold text-gray-700" placeholder="Tuliskan ringkasan materi..."></textarea>
                </div>
            </div>

            <!-- Bagian Catatan Checklist Baru -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="font-black text-gray-800 uppercase text-[10px] tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 bg-blue-900 rounded-full"></span> Catatan Kejadian / Kendala
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    @php
                        $options = [
                            "Proses pembelajaran berjalan dengan lancar dan tertib",
                            "Peserta didik melaksanakan tugas dengan tuntas",
                            "Peserta didik dapat poin positif tercatat di SISPODIS",
                            "Peserta didik dapat poin negatif tercatat di SISPODIS",
                            "Pelaksanaan Kokurikuler berjalan dengan baik",
                            "Masih ada masalah dengan Pelaksanaan Kokurikuler",
                            "Ada tugas / assesmen harian",
                            "Pemahaman tentang perilaku akhlak mulia/kesantunan",
                            "Mengajar Penguatan Pendidikan Karakter dan akhlak mulia",
                            "Melakukan kegiatan positif lainnya"
                        ];
                    @endphp
                    @foreach($options as $option)
                        <label class="flex items-center space-x-2 text-sm text-gray-700 bg-gray-50 p-2 rounded-lg border border-gray-100 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="notes_checklist[]" value="{{ $option }}" class="rounded text-blue-900 focus:ring-blue-900">
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>

                <textarea name="notes_text" rows="3" class="w-full p-3 border border-gray-100 rounded-2xl bg-gray-50 focus:ring-4 focus:ring-blue-50 outline-none font-bold text-gray-700" placeholder="Catatan tambahan lainnya..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-gray-800 transition shadow-lg shadow-gray-900/10">
                Simpan Jurnal
            </button>
        </div>
    </form>
</div>

<script>
    function updateUrl() {
        const date = document.getElementById('datePicker').value;
        const kelasSelect = document.getElementById('classSelect');
        const kelas = Array.from(kelasSelect.selectedOptions).map(option => option.value);
        
        let url = window.location.pathname + '?tanggal=' + date;
        if (kelas.length > 0) {
            url += '&' + kelas.map(k => 'kelas[]=' + k).join('&');
        }
        window.location.href = url;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const subjectEl = document.getElementById('subjectSelect');
        const classEl = document.getElementById('classSelect');
        
        let choicesClass;

        if (typeof Choices !== 'undefined') {
            new Choices(subjectEl, { removeItemButton: true, searchEnabled: true });
            choicesClass = new Choices(classEl, { removeItemButton: true, searchEnabled: true, itemSelectText: '' });
            new Choices(document.getElementById('kokurikulerSelect'), { removeItemButton: true, searchEnabled: true });
            new Choices(document.getElementById('lessonHoursSelect'), { removeItemButton: true, searchEnabled: true });
        }

        subjectEl.addEventListener('change', function() {
            const selectedValues = Array.from(subjectEl.selectedOptions).map(option => option.value);
            if (selectedValues.includes('Tidak ada Jam Mengajar')) {
                choicesClass.removeActiveItems();
                classEl.disabled = true;
            } else {
                classEl.disabled = false;
            }
            if (selectedValues.includes('Ko-Kurikuler')) {
                document.getElementById('kokurikulerField').classList.remove('hidden');
            } else {
                document.getElementById('kokurikulerField').classList.add('hidden');
            }
        });
    });
</script>
@endsection