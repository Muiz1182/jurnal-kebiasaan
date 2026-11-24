@extends('layouts.student')

@section('title', 'Dashboard Murid')

@section('content')
<div class="p-3.5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6 max-w-7xl mx-auto w-full min-w-0">

    <!-- Header Atas: Judul & Streak Hari Beruntun -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3.5 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm">
        <div class="space-y-0.5">
            <h1 class="text-xl sm:text-2xl font-black text-gray-900">Halo, {{ $user->name }}! 👋</h1>
            <p class="text-gray-500 text-xs sm:text-sm">Sudah siap membangun hari yang luar biasa di SMPN 9 Probolinggo?</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl flex items-center gap-3 shrink-0 w-full sm:w-auto">
            <span class="text-xl sm:text-2xl shrink-0">📅</span>
            <div class="min-w-0">
                <p id="live-date" class="text-[9px] sm:text-[10px] font-bold text-blue-600 uppercase tracking-widest truncate">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-xs sm:text-sm font-extrabold text-blue-900">Hari ke-{{ $streakDays ?? 0 }} Beruntun</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">

        <!-- Kolom Kiri: 7 Kebiasaan Hari Ini -->
        <div id="jurnal-section" class="lg:col-span-2 bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm space-y-4 sm:space-y-6">
            <div class="flex justify-between items-center gap-2">
                <h3 class="font-black text-gray-900 flex items-center gap-2 text-sm sm:text-base">
                    <span>✅</span> 7 Pembiasaan Hari Ini
                </h3>
                <span id="counter-badge" class="px-2.5 sm:px-3 py-1 bg-emerald-50 text-emerald-700 text-[11px] sm:text-xs font-black rounded-full uppercase tracking-wider shrink-0">
                    {{ $completedCount ?? 0 }} / 7 Selesai
                </span>
            </div>

            <!-- Grid 7 Kartu Menu Navigasi Kebiasaan Responsif -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 sm:gap-3.5">

                <!-- 1. Bangun Pagi -->
                <a href="{{ route('student.bangun-pagi') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->bangun_pagi ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">⏰</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Bangun Pagi</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->bangun_pagi ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

                <!-- 2. Ibadah -->
                <a href="{{ route('student.taat-beribadah') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->ibadah ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">✨</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Ibadah</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->ibadah ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

                <!-- 3. Olahraga -->
                <a href="{{ route('student.olahraga') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->olahraga ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">💪</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Olahraga</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->olahraga ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

                <!-- 4. Makan Sehat -->
                <a href="{{ route('student.makan-sehat') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->makan_sehat ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">🥗</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Makan Sehat</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->makan_sehat ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

                <!-- 5. Belajar -->
                <a href="{{ route('student.gemar-belajar') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->belajar ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">📚</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Belajar</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->belajar ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

                <!-- 6. Sosialisasi -->
                <a href="{{ route('student.aktif-masyarakat') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->sosialisasi ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">👥</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Sosialisasi</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->sosialisasi ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

                <!-- 7. Tidur Cepat -->
                <a href="{{ route('student.tidur-cepat') }}" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border transition active:scale-[0.98] flex flex-col items-center text-center justify-between gap-2 min-h-[110px] {{ optional($journal)->tidur_cepat ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-sm' }}">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg sm:text-xl font-bold">🌙</div>
                    <span class="text-xs font-bold text-gray-800 leading-tight">Tidur Cepat</span>
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ optional($journal)->tidur_cepat ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-black">✓</span>
                </a>

            </div>
        </div>

        <!-- Kolom Kanan: Level Karakter & Inspirasi -->
        <div class="space-y-5 sm:space-y-6">
            <!-- Level Karakter Card -->
            <div class="bg-blue-900 text-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl space-y-3 sm:space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-300">Level Karakter</span>
                    <span class="text-xl">🏆</span>
                </div>
                <div>
                    <h4 class="text-lg sm:text-xl font-black">Siswa Teladan</h4>
                    <p class="text-xs text-blue-200 mt-0.5">Progress: {{ $totalPoints ?? 0 }} / 1000 Poin ({{ $progressPercentage ?? 0 }}%)</p>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-blue-950 rounded-full h-2 overflow-hidden">
                    <div class="bg-emerald-400 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercentage ?? 0 }}%"></div>
                </div>
                <p class="text-[11px] text-blue-200">
                    @if(($totalPoints ?? 0) < 1000)
                        Butuh {{ 1000 - ($totalPoints ?? 0) }} poin lagi untuk rank berikutnya.
                    @else
                        Selamat! Anda telah mencapai level maksimum!
                    @endif
                </p>
            </div>

            <!-- Inspirasi Hari Ini -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm space-y-2.5">
                <h3 class="font-black text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                    <span>💡</span> Inspirasi Hari Ini
                </h3>
                <p class="text-xs text-gray-600 italic leading-relaxed">
                    "Disiplin adalah jembatan antara tujuan dan pencapaian. Mulailah dari hal terkecil, seperti bangun tepat waktu."
                </p>
                <p class="text-[10px] font-bold text-gray-400">(Tokoh Pendidikan)</p>
            </div>
        </div>

    </div>

    <!-- Bagian Rekapan Jurnal yang Sudah Diisi -->
    <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
            <h3 class="font-black text-gray-900 flex items-center gap-2 text-sm sm:text-base">
                <span>📊</span> Rekapan Jurnal & Riwayat Pengisian
            </h3>
            <span class="text-xs text-gray-500 font-medium">Histori Jurnal Harian Anda</span>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
            <table class="w-full text-left border-collapse min-w-[640px]">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Tanggal</th>
                        <th class="py-2.5 px-2 text-center">Bangun Pagi</th>
                        <th class="py-2.5 px-2 text-center">Ibadah</th>
                        <th class="py-2.5 px-2 text-center">Olahraga</th>
                        <th class="py-2.5 px-2 text-center">Makan Sehat</th>
                        <th class="py-2.5 px-2 text-center">Belajar</th>
                        <th class="py-2.5 px-2 text-center">Sosialisasi</th>
                        <th class="py-2.5 px-2 text-center">Tidur Cepat</th>
                        <th class="py-2.5 px-2 text-center">Total Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs sm:text-sm text-gray-700">
                    @forelse($journals ?? $journalHistory ?? [] as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-2.5 px-3 font-semibold text-gray-900 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at ?? $item->date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->bangun_pagi)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->ibadah)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->olahraga)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->makan_sehat)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->belajar || $item->materi_belajar)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->sosialisasi)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center">
                                @if($item->tidur_cepat)<span class="inline-block w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full font-bold text-[10px] leading-5">✓</span>@else<span class="text-gray-300">-</span>@endif
                            </td>
                            <td class="py-2.5 px-2 text-center font-bold text-emerald-600">
                                {{ 
                                    ($item->bangun_pagi ? 1 : 0) + 
                                    ($item->ibadah ? 1 : 0) + 
                                    ($item->olahraga ? 1 : 0) + 
                                    ($item->makan_sehat ? 1 : 0) + 
                                    (($item->belajar || $item->materi_belajar) ? 1 : 0) + 
                                    ($item->sosialisasi ? 1 : 0) + 
                                    ($item->tidur_cepat ? 1 : 0) 
                                }}/7
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-400 text-xs italic">
                                Belum ada data rekapan jurnal yang tersimpan. Silakan isi melalui menu di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script tambahan untuk Live Time & Jam Real-Time Update -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

        const updateDate = () => {
            const now = new Date();
            const dateString = now.toLocaleDateString('id-ID', dateOptions);
            const timeString = now.toLocaleTimeString('id-ID', timeOptions);

            const dateElement = document.getElementById('live-date');
            if (dateElement) {
                dateElement.textContent = `${dateString} • ${timeString} WIB`;
            }
        };

        updateDate();
        setInterval(updateDate, 1000);
    });
</script>
@endsection
