@extends('layouts.student')

@section('title', 'Dashboard Murid')

@section('content')
<div class="flex min-h-screen bg-gray-50 w-full">
    
    <!-- KONTEN UTAMA DASHBOARD -->
    <main class="flex-1 p-4 md:p-8 space-y-6 overflow-y-auto">
        
        <!-- Header Atas: Judul & Streak Hari Beruntun -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Halo, {{ $user->name }}! 👋</h1>
                <p class="text-gray-500 text-sm mt-1">Sudah siap membangun hari yang luar biasa di SMPN 9 Probolinggo?</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 px-5 py-3 rounded-2xl flex items-center gap-3">
                <span class="text-2xl">📅</span>
                <div>
                    <!-- Ditambahkan ID live-date untuk update waktu secara real-time -->
                    <p id="live-date" class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm font-extrabold text-blue-900">Hari ke-{{ $streakDays ?? 0 }} Beruntun</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: 7 Kebiasaan Hari Ini -->
            <div id="jurnal-section" class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="font-extrabold text-gray-900 flex items-center gap-2">
                        <span>✅</span> Jurnal 7 Kebiasaan Hari Ini
                    </h3>
                    <span id="counter-badge" class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-black rounded-full uppercase tracking-wider">
                        {{ $completedCount ?? 0 }} / 7 Selesai
                    </span>
                </div>

                <!-- Grid 7 Kartu Menu Navigasi Kebiasaan -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    
                    <!-- 1. Bangun Pagi -->
                    <a href="{{ route('student.bangun-pagi') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->bangun_pagi ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">⏰</div>
                        <span class="text-xs font-bold text-gray-800">Bangun Pagi</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->bangun_pagi ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                    <!-- 2. Ibadah -->
                    <a href="{{ route('student.taat-beribadah') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->ibadah ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">✨</div>
                        <span class="text-xs font-bold text-gray-800">Ibadah</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->ibadah ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                    <!-- 3. Olahraga -->
                    <a href="{{ route('student.olahraga') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->olahraga ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">💪</div>
                        <span class="text-xs font-bold text-gray-800">Olahraga</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->olahraga ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                    <!-- 4. Makan Sehat -->
                    <a href="{{ route('student.makan-sehat') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->makan_sehat ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">🥗</div>
                        <span class="text-xs font-bold text-gray-800">Makan Sehat</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->makan_sehat ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                    <!-- 5. Belajar -->
                    <a href="{{ route('student.gemar-belajar') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->belajar ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">📚</div>
                        <span class="text-xs font-bold text-gray-800">Belajar</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->belajar ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                    <!-- 6. Sosialisasi -->
                    <a href="{{ route('student.aktif-masyarakat') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->sosialisasi ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">👥</div>
                        <span class="text-xs font-bold text-gray-800">Sosialisasi</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->sosialisasi ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                    <!-- 7. Tidur Cepat -->
                    <a href="{{ route('student.tidur-cepat') }}" class="p-4 rounded-2xl border transition transform hover:scale-[1.02] flex flex-col items-center text-center gap-3 {{ optional($journal)->tidur_cepat ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-xl font-bold">🌙</div>
                        <span class="text-xs font-bold text-gray-800">Tidur Cepat</span>
                        <span class="w-6 h-6 rounded-full {{ optional($journal)->tidur_cepat ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs">✓</span>
                    </a>

                </div>
            </div>

            <!-- Kolom Kanan: Level Karakter & Inspirasi -->
            <div class="space-y-6">
                <!-- Level Karakter Card -->
                <div class="bg-blue-900 text-white p-6 rounded-3xl shadow-xl space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-blue-300">Level Karakter</span>
                        <span class="text-xl">🏆</span>
                    </div>
                    <div>
                        <h4 class="text-xl font-extrabold">Siswa Teladan</h4>
                        <p class="text-xs text-blue-200 mt-1">Progress: {{ $totalPoints ?? 0 }} / 1000 Poin ({{ $progressPercentage ?? 0 }}%)</p>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-blue-950 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-400 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercentage ?? 0 }}%"></div>
                    </div>
                    <p class="text-[11px] text-blue-200">
                        @if(($totalPoints ?? 0) < 1000)
                            Butuh {{ 1000 - ($totalPoints ?? 0) }} poin lagi untuk mencapai rank berikutnya.
                        @else
                            Selamat! Anda telah mencapai level maksimum!
                        @endif
                    </p>
                </div>

                <!-- Inspirasi Hari Ini -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
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
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                    <span>📊</span> Rekapan Jurnal & Riwayat Pengisian
                </h3>
                <span class="text-xs text-gray-500 font-medium">Histori Jurnal Harian Anda</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4 text-center">Bangun Pagi</th>
                            <th class="py-3 px-4 text-center">Ibadah</th>
                            <th class="py-3 px-4 text-center">Olahraga</th>
                            <th class="py-3 px-4 text-center">Makan Sehat</th>
                            <th class="py-3 px-4 text-center">Belajar</th>
                            <th class="py-3 px-4 text-center">Sosialisasi</th>
                            <th class="py-3 px-4 text-center">Tidur Cepat</th>
                            <th class="py-3 px-4 text-center">Total Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                        {{-- Menggunakan $journals atau fallback ke $journalHistory jika ada --}}
                        @forelse($journals ?? $journalHistory ?? [] as $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4 font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->created_at ?? $item->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->bangun_pagi)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->ibadah)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->olahraga)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->makan_sehat)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->belajar || $item->materi_belajar)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->sosialisasi)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->tidur_cepat)<span class="inline-block w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold text-xs leading-6">✓</span>@else<span class="text-gray-300">-</span>@endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-emerald-600">
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

    </main>
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
        setInterval(updateDate, 1000); // Perbarui setiap 1 detik agar jam detik berjalan real-time
    });
</script>
@endsection