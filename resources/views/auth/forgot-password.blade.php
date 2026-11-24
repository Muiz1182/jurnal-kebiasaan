<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SMPN 9 Probolinggo</title>
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
        <div class="w-full max-w-xl" x-data="{ tab: 'request' }">

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
                            Pusat Bantuan Akun
                        </span>
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                            Lupa Kata Sandi
                        </h1>
                    </div>
                </div>

                <!-- Info Box Verifikasi 1x24 Jam -->
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 mb-6 text-xs sm:text-sm">
                    <div class="flex items-start gap-3">
                        <span class="text-xl shrink-0">🛡️</span>
                        <div class="min-w-0">
                            <p class="font-black text-amber-950">Sistem Verifikasi Admin 1x24 Jam</p>
                            <p class="text-amber-800 text-xs mt-1 leading-relaxed">
                                Demi keamanan akun Anda, permohonan reset sandi diverifikasi terlebih dahulu oleh admin sekolah maksimal dalam 1x24 jam. Begitu disetujui, Anda dapat langsung menetapkan kata sandi baru.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notifikasi Pesan -->
                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-semibold flex items-start gap-3 mb-5">
                        <span class="text-lg shrink-0">✅</span>
                        <div class="min-w-0">
                            <p class="font-black text-emerald-950">Berhasil Diajukan</p>
                            <p class="text-emerald-800 text-xs mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm font-semibold flex items-start gap-3 mb-5">
                        <span class="text-lg shrink-0">ℹ️</span>
                        <div class="min-w-0">
                            <p class="font-black text-blue-950">Informasi</p>
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

                <!-- Switch Tab: Ajukan Permohonan / Cek Status -->
                <div class="grid grid-cols-2 gap-2 p-1.5 bg-slate-100 rounded-2xl mb-6">
                    <button type="button" 
                            @click="tab = 'request'" 
                            :class="tab === 'request' ? 'bg-white text-blue-900 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-800'"
                            class="py-2.5 px-3 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                        <span>📝</span> Ajukan Permohonan
                    </button>
                    <button type="button" 
                            @click="tab = 'check'" 
                            :class="tab === 'check' ? 'bg-white text-blue-900 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-800'"
                            class="py-2.5 px-3 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                        <span>🔍</span> Cek Status Pengajuan
                    </button>
                </div>

                <!-- TAB 1: FORM PENGAJUAN RESET SANDI -->
                <div x-show="tab === 'request'" x-cloak>
                    <form method="POST" action="{{ route('password.request.submit') }}" class="space-y-4">
                        @csrf

                        <!-- Identitas Akun -->
                        <div class="space-y-1.5">
                            <label for="identifier" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Email, Username, atau NISN Akun
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                                    👤
                                </div>
                                <input type="text" 
                                       id="identifier" 
                                       name="identifier" 
                                       value="{{ old('identifier') }}"
                                       required 
                                       placeholder="Contoh: murid123 atau nisn@sekolah.sch.id"
                                       class="w-full pl-11 pr-4 py-3.5 min-h-[48px] bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">
                            </div>
                            <p class="text-[11px] text-slate-500">
                                Masukkan identitas akun yang Anda daftarkan di sistem.
                            </p>
                        </div>

                        <!-- Alasan Pengajuan (Opsional) -->
                        <div class="space-y-1.5">
                            <label for="reason" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Alasan Permohonan (Opsional)
                            </label>
                            <textarea id="reason" 
                                      name="reason" 
                                      rows="3" 
                                      placeholder="Contoh: Lupa password lama setelah ganti perangkat HP..."
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">{{ old('reason') }}</textarea>
                        </div>

                        <!-- Tombol Kirim -->
                        <button type="submit" 
                                class="w-full min-h-[48px] bg-blue-900 hover:bg-blue-800 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-lg shadow-blue-900/25 flex items-center justify-center gap-2 transition cursor-pointer mt-2">
                            <span>📨</span> Kirim Permintaan Verifikasi ke Admin
                        </button>
                    </form>
                </div>

                <!-- TAB 2: FORM CEK STATUS PENGAJUAN -->
                <div x-show="tab === 'check'" x-cloak>
                    <form method="POST" action="{{ route('password.request.check') }}" class="space-y-4">
                        @csrf

                        <div class="space-y-1.5">
                            <label for="check_identifier" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Email, Username, atau NISN yang Pernah Diajukan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                                    🔍
                                </div>
                                <input type="text" 
                                       id="check_identifier" 
                                       name="identifier" 
                                       value="{{ old('identifier') }}"
                                       required 
                                       placeholder="Ketik email, username, atau NISN Anda"
                                       class="w-full pl-11 pr-4 py-3.5 min-h-[48px] bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">
                            </div>
                            <p class="text-[11px] text-slate-500">
                                Sistem akan membuka status verifikasi terakhir dan tombol ganti kata sandi jika sudah disetujui.
                            </p>
                        </div>

                        <button type="submit" 
                                class="w-full min-h-[48px] bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 transition cursor-pointer mt-2">
                            <span>🔎</span> Periksa Status Verifikasi Saya
                        </button>
                    </form>
                </div>

                <!-- Kembali ke Halaman Login -->
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
