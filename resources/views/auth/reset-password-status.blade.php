<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Reset Kata Sandi - SMPN 9 Probolinggo</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#070D1E] text-slate-100 min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white relative overflow-x-hidden">

    <!-- Ambient Glowing Orbs Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-[140px]"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-emerald-600/15 rounded-full blur-[130px]"></div>
    </div>

    <!-- Main Container -->
    <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-xl">

            <!-- Card Utama -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 text-slate-900 shadow-2xl border border-slate-100">
                
                <!-- Logo & Brand Header -->
                <div class="flex items-center gap-3.5 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center p-2 shadow-sm shrink-0">
                        <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo SMPN 9" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <span class="text-blue-900 font-black text-sm hidden">SMP9</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-blue-900 bg-blue-50 px-2.5 py-0.5 rounded-full">
                            Status Permohonan
                        </span>
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                            Verifikasi Reset Sandi
                        </h1>
                    </div>
                </div>

                <!-- Notifikasi Sesi -->
                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-semibold flex items-start gap-3 mb-5">
                        <span class="text-lg shrink-0">✨</span>
                        <div class="min-w-0">
                            <p class="font-black text-emerald-950">Permohonan Terkirim</p>
                            <p class="text-emerald-800 text-xs mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm font-semibold flex items-start gap-3 mb-5">
                        <span class="text-lg shrink-0">ℹ️</span>
                        <div class="min-w-0">
                            <p class="font-black text-blue-950">Status Aktif</p>
                            <p class="text-blue-800 text-xs mt-0.5">{{ session('info') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-semibold flex items-start gap-3 mb-5">
                        <span class="text-lg shrink-0">⚠️</span>
                        <div class="min-w-0">
                            <p class="font-black text-rose-950">Perhatian</p>
                            <p class="text-rose-800 text-xs mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- KONDISI STATUS -->

                {{-- 1. STATUS PENDING (MENUNGGU VERIFIKASI ADMIN) --}}
                @if ($resetRequest->isPending() && !$resetRequest->isExpired())
                    <div class="text-center py-6 px-4 bg-amber-50/70 border border-amber-200/80 rounded-2xl mb-6">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-3xl animate-pulse">
                            ⏳
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-amber-950">
                            Menunggu Verifikasi Admin
                        </h2>
                        <p class="text-xs sm:text-sm text-amber-900 font-medium mt-1.5 max-w-md mx-auto leading-relaxed">
                            Permohonan Anda telah masuk ke sistem antrean admin. Waktu tunggu verifikasi maksimal 1x24 jam.
                        </p>
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-amber-200 text-[11px] font-extrabold text-amber-900 shadow-sm">
                            <span>⏱️</span>
                            <span>Batas Kedaluwarsa: {{ $resetRequest->expires_at ? $resetRequest->expires_at->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Nama Akun</span>
                            <span class="font-black text-slate-800">{{ $resetRequest->user->name }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Username / NISN</span>
                            <span class="font-black text-slate-800">{{ $resetRequest->user->username ?? $resetRequest->user->nisn }}</span>
                        </div>
                        @if ($resetRequest->user->kelas)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                                <span class="text-slate-500 font-bold">Kelas</span>
                                <span class="font-black text-slate-800">{{ $resetRequest->user->kelas }}</span>
                            </div>
                        @endif
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Waktu Pengajuan</span>
                            <span class="font-semibold text-slate-700">{{ $resetRequest->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" 
                                onclick="window.location.reload()" 
                                class="flex-1 min-h-[48px] bg-blue-900 hover:bg-blue-800 text-white font-extrabold text-xs sm:text-sm rounded-2xl flex items-center justify-center gap-2 shadow-md transition cursor-pointer">
                            <span>🔄</span> Muat Ulang Status
                        </button>
                    </div>

                {{-- 2. STATUS APPROVED (SUDAH DISETUJUI ADMIN & MASIH AKTIF) --}}
                @elseif ($resetRequest->isApproved() && !$resetRequest->isExpired())
                    <div class="text-center py-6 px-4 bg-emerald-50 border border-emerald-200 rounded-2xl mb-6">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl">
                            🎉
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-emerald-950">
                            Permohonan Telah Disetujui!
                        </h2>
                        <p class="text-xs sm:text-sm text-emerald-900 font-medium mt-1.5 max-w-md mx-auto leading-relaxed">
                            Admin sekolah telah memverifikasi permohonan Anda. Anda sekarang dapat langsung membuat kata sandi baru.
                        </p>
                        @if ($resetRequest->admin_notes)
                            <div class="mt-3 p-2.5 rounded-xl bg-white border border-emerald-200 text-xs text-emerald-950 font-semibold max-w-sm mx-auto">
                                <span class="font-bold text-emerald-700">Catatan Admin:</span> {{ $resetRequest->admin_notes }}
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Disetujui Pada</span>
                            <span class="font-semibold text-slate-700">{{ $resetRequest->approved_at ? $resetRequest->approved_at->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Batas Waktu Ganti Sandi</span>
                            <span class="font-extrabold text-amber-700">{{ $resetRequest->expires_at ? $resetRequest->expires_at->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Utama: Langsung Ganti Sandi Baru -->
                    <a href="{{ route('password.reset.form', ['token' => $resetRequest->token]) }}" 
                       class="w-full min-h-[48px] bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition cursor-pointer">
                        <span>🔑</span> Buat Kata Sandi Baru Sekarang
                    </a>

                {{-- 3. STATUS COMPLETED (SUDAH PERNAH DIGANTI) --}}
                @elseif ($resetRequest->isCompleted())
                    <div class="text-center py-6 px-4 bg-blue-50 border border-blue-200 rounded-2xl mb-6">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-3xl">
                            ✅
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-blue-950">
                            Kata Sandi Telah Diperbarui
                        </h2>
                        <p class="text-xs sm:text-sm text-blue-900 font-medium mt-1.5 max-w-md mx-auto leading-relaxed">
                            Permohonan ini telah selesai digunakan. Kata sandi akun Anda telah berhasil diubah sebelumnya.
                        </p>
                    </div>

                    <a href="{{ route('login') }}" 
                       class="w-full min-h-[48px] bg-blue-900 hover:bg-blue-800 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-lg shadow-blue-900/25 flex items-center justify-center gap-2 transition cursor-pointer">
                        <span>🚀</span> Masuk ke Portal
                    </a>

                {{-- 4. STATUS REJECTED (DITOLAK ADMIN) --}}
                @elseif ($resetRequest->isRejected())
                    <div class="text-center py-6 px-4 bg-rose-50 border border-rose-200 rounded-2xl mb-6">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center text-3xl">
                            ❌
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-rose-950">
                            Permohonan Ditolak
                        </h2>
                        <p class="text-xs sm:text-sm text-rose-900 font-medium mt-1.5 max-w-md mx-auto leading-relaxed">
                            Admin sekolah menolak permohonan reset sandi ini.
                        </p>
                        @if ($resetRequest->admin_notes)
                            <div class="mt-3 p-3 rounded-xl bg-white border border-rose-200 text-xs text-rose-950 font-semibold max-w-sm mx-auto">
                                <span class="font-bold text-rose-700">Alasan:</span> {{ $resetRequest->admin_notes }}
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('password.request') }}" 
                           class="flex-1 min-h-[48px] bg-slate-900 hover:bg-slate-800 text-white font-black text-xs sm:text-sm rounded-2xl flex items-center justify-center gap-2 transition cursor-pointer">
                            <span>📝</span> Ajukan Ulang Permohonan
                        </a>
                    </div>

                {{-- 5. KEDALUWARSA (LEWAT 1X24 JAM) --}}
                @else
                    <div class="text-center py-6 px-4 bg-slate-100 border border-slate-300 rounded-2xl mb-6">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-3xl">
                            ⌛
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900">
                            Masa Berlaku Kedaluwarsa
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium mt-1.5 max-w-md mx-auto leading-relaxed">
                            Masa aktif verifikasi 1x24 jam telah berakhir. Demi alasan keamanan, silakan ajukan permohonan reset sandi yang baru.
                        </p>
                    </div>

                    <a href="{{ route('password.request') }}" 
                       class="w-full min-h-[48px] bg-blue-900 hover:bg-blue-800 text-white font-black text-sm rounded-2xl shadow-lg shadow-blue-900/25 flex items-center justify-center gap-2 transition cursor-pointer">
                        <span>📝</span> Buat Permohonan Baru
                    </a>
                @endif

                <!-- Kembali ke Login -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-2 text-xs font-extrabold text-blue-900 hover:text-blue-700 hover:underline transition">
                        <span>⬅️</span> Kembali ke Halaman Masuk (Login)
                    </a>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} SMPN 9 Probolinggo. Sistem Jurnal Pembiasaan Karakter.
    </footer>

</body>
</html>
