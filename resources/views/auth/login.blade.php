<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Portal | SMPN 9 Probolinggo</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Interaktivitas UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased selection:bg-blue-600 selection:text-white" x-data="{ showPassword: false }">

    <!-- Top Glow Accent Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/15 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-indigo-600/15 rounded-full blur-[140px]"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-emerald-600/10 rounded-full blur-[130px]"></div>
    </div>

    <!-- MAIN CONTAINER -->
    <main class="relative z-10 flex-1 flex items-center justify-center p-3.5 sm:p-6 lg:p-8">
        <div class="w-full max-w-5xl mx-auto bg-slate-900/80 border border-slate-800/80 rounded-3xl sm:rounded-[32px] shadow-2xl backdrop-blur-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <!-- KOLOM KIRI (VISUAL BRANDING & 7 KEBIASAAN SHOWCASE - DESKTOP) -->
            <div class="lg:col-span-5 bg-gradient-to-br from-blue-950 via-slate-900 to-indigo-950 p-6 sm:p-8 lg:p-10 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-slate-800/80 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-44 h-44 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Header Logo & Identitas Sekolah -->
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center gap-3.5">
                        <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo SMPN 9" 
                             class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl object-cover p-1 bg-white/10 backdrop-blur-md border border-white/20 shadow-lg shrink-0">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-300 text-[10px] font-black uppercase tracking-widest border border-blue-400/30">
                                PORTAL RESMI
                            </span>
                            <h1 class="text-base sm:text-lg font-black text-white tracking-tight leading-tight mt-0.5">
                                SMPN 9 Probolinggo
                            </h1>
                            <p class="text-[11px] text-slate-400 font-medium">Jurnal Pembiasaan Karakter Siswa</p>
                        </div>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="pt-2">
                        <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight leading-snug">
                            7 Kebiasaan Anak Indonesia Hebat
                        </h2>
                        <p class="text-xs text-slate-300/90 leading-relaxed font-normal mt-2">
                            Membangun kedisiplinan spiritual, raga, dan literasi secara terpadu melalui pemantauan jurnal harian siswa berbasis server resmi.
                        </p>
                    </div>
                </div>

                <!-- 7 Kebiasaan Badges Grid -->
                <div class="my-6 pt-4 border-t border-slate-800/80 relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">7 PILAR PEMBIASAAN:</p>
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                            <span class="text-sm">⏰</span>
                            <span class="font-bold text-slate-200">Bangun Pagi</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                            <span class="text-sm">✨</span>
                            <span class="font-bold text-slate-200">Taat Beribadah</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                            <span class="text-sm">💪</span>
                            <span class="font-bold text-slate-200">Rajin Olahraga</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                            <span class="text-sm">🥗</span>
                            <span class="font-bold text-slate-200">Makan Sehat</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                            <span class="text-sm">📚</span>
                            <span class="font-bold text-slate-200">Gemar Belajar</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                            <span class="text-sm">👥</span>
                            <span class="font-bold text-slate-200">Bermasyarakat</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50 col-span-2">
                            <span class="text-sm">🌙</span>
                            <span class="font-bold text-slate-200">Tidur Cepat & Istirahat Berkualitas</span>
                        </div>
                    </div>
                </div>

                <!-- Kutipan Bawah -->
                <div class="pt-3 border-t border-slate-800/80 text-[11px] text-slate-400 italic font-medium relative z-10 flex items-center gap-2">
                    <span class="text-base text-amber-400">💡</span>
                    <span>"Karakter unggul terbentuk dari kebiasaan kecil yang dirawat setiap hari."</span>
                </div>
            </div>

            <!-- KOLOM KANAN (FORMULIR LOGIN INTERAKTIF & MODERN) -->
            <div class="lg:col-span-7 p-6 sm:p-8 lg:p-12 flex flex-col justify-center bg-white text-slate-900">
                <div class="max-w-md w-full mx-auto space-y-6">
                    
                    <!-- Form Title & Header -->
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-900 text-xs font-bold mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Sistem Siap Digunakan
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            Masuk ke Portal
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            Gunakan akun Murid, Guru, atau Administrator Anda untuk melanjutkan.
                        </p>
                    </div>

                    <!-- Notifikasi Pesan Error / Peringatan -->
                    @if ($errors->any())
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-semibold flex items-start gap-3 shadow-sm">
                            <span class="text-lg shrink-0">⚠️</span>
                            <div class="min-w-0">
                                <p class="font-black text-rose-950">Gagal Masuk</p>
                                <p class="text-rose-800 text-xs mt-0.5">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm font-semibold flex items-start gap-3 shadow-sm">
                            <span class="text-lg shrink-0">⚡</span>
                            <div class="min-w-0">
                                <p class="font-black text-amber-950">Perhatian</p>
                                <p class="text-amber-800 text-xs mt-0.5">{{ session('warning') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-semibold flex items-start gap-3 shadow-sm">
                            <span class="text-lg shrink-0">✨</span>
                            <div class="min-w-0">
                                <p class="font-black text-emerald-950">Berhasil</p>
                                <p class="text-emerald-800 text-xs mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Form Login Utama -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4 pt-1">
                        @csrf

                        <!-- Input Email / Username -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Email atau Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                                    👤
                                </div>
                                <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus 
                                       placeholder="Masukkan email atau username"
                                       class="w-full pl-11 pr-4 py-3.5 min-h-[48px] bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- Input Kata Sandi dengan Tombol Toggle Show/Hide -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                    Kata Sandi
                                </label>
                                <a href="#" class="text-xs font-bold text-blue-900 hover:text-blue-700 transition hover:underline">
                                    Lupa Sandi?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                                    🔒
                                </div>
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                       placeholder="Masukkan kata sandi akun"
                                       class="w-full pl-11 pr-12 py-3.5 min-h-[48px] bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">
                                
                                <!-- Tombol Toggle Show Password -->
                                <button type="button" @click="showPassword = !showPassword" 
                                        aria-label="Tampilkan atau sembunyikan kata sandi"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition cursor-pointer min-w-[44px] justify-center">
                                    <span x-show="!showPassword" class="text-sm">👁️</span>
                                    <span x-show="showPassword" class="text-sm" style="display: none;">🙈</span>
                                </button>
                            </div>
                        </div>

                        <!-- Opsi Ingat Saya & Lupa Sandi -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-600 select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded-md border-slate-300 text-blue-900 focus:ring-blue-900 cursor-pointer">
                                <span>Ingat saya</span>
                            </label>

                            <a href="{{ route('password.request') }}" 
                               class="text-xs font-extrabold text-blue-900 hover:text-blue-700 hover:underline transition">
                                Lupa Sandi?
                            </a>
                        </div>

                        <!-- Tombol Masuk Utama -->
                        <button type="submit" 
                                class="w-full min-h-[48px] bg-blue-900 hover:bg-blue-800 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-lg shadow-blue-900/25 flex items-center justify-center gap-2 transition cursor-pointer mt-2">
                            <span>🚀</span> Masuk ke Portal
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative flex items-center justify-center my-6">
                        <div class="border-t border-slate-200 w-full"></div>
                        <span class="bg-white px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest shrink-0">
                            atau belum punya akun?
                        </span>
                    </div>

                    <!-- Tombol Daftar Akun Murid Baru -->
                    <a href="{{ route('register') }}" 
                       class="w-full min-h-[48px] border-2 border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 active:scale-[0.99] text-slate-800 font-extrabold text-xs sm:text-sm rounded-2xl flex items-center justify-center gap-2 transition cursor-pointer">
                        <span>📝</span> Buat Akun Murid Baru
                    </a>

                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER RESMI RESPONSIVE -->
    <footer class="relative z-10 py-4 px-4 sm:px-8 border-t border-slate-800/80 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2.5 text-center sm:text-left text-xs text-slate-500 font-medium">
            <p>&copy; 2026 SMPN 9 Probolinggo. Portal Jurnal Pembiasaan 7 Kebiasaan Siswa.</p>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="inline-flex items-center gap-1.5 text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Server WIB
                </span>
                <span class="text-slate-700">•</span>
                <span class="text-slate-400 font-semibold">Versi 2.0</span>
            </div>
        </div>
    </footer>

    <script>
        // Mencegah error 419 CSRF Token Mismatch akibat restore halaman dari Back-Forward Cache (bfcache)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>