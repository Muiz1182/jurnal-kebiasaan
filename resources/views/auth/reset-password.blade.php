<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Kata Sandi Baru - SMPN 9 Probolinggo</title>
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
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                            Verifikasi Terkonfirmasi
                        </span>
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                            Buat Kata Sandi Baru
                        </h1>
                    </div>
                </div>

                <!-- Info User Akun -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-900 text-white flex items-center justify-center font-black text-base shrink-0 shadow-sm">
                            {{ strtoupper(substr($resetRequest->user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-extrabold text-sm text-slate-900 truncate">{{ $resetRequest->user->name }}</p>
                            <p class="text-xs text-slate-500 font-medium truncate">
                                {{ $resetRequest->user->username ? '@'.$resetRequest->user->username : $resetRequest->user->email }}
                                @if ($resetRequest->user->kelas)
                                    &bull; Kelas {{ $resetRequest->user->kelas }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notifikasi Pesan -->
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
                            <p class="font-black text-rose-950">Gagal Memperbarui</p>
                            <p class="text-rose-800 text-xs mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Form Ganti Sandi Baru -->
                <form method="POST" action="{{ route('password.reset.update', ['token' => $token]) }}" class="space-y-4" x-data="{ showPass1: false, showPass2: false }">
                    @csrf

                    <!-- Input Sandi Baru -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                                🔒
                            </div>
                            <input :type="showPass1 ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   minlength="6"
                                   placeholder="Minimal 6 karakter"
                                   class="w-full pl-11 pr-12 py-3.5 min-h-[48px] bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">
                            
                            <button type="button" @click="showPass1 = !showPass1" 
                                    aria-label="Tampilkan sandi"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition cursor-pointer min-w-[44px] justify-center">
                                <span x-show="!showPass1" class="text-sm">👁️</span>
                                <span x-show="showPass1" class="text-sm" style="display: none;">🙈</span>
                            </button>
                        </div>
                    </div>

                    <!-- Input Konfirmasi Sandi Baru -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Ulangi Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                                🔐
                            </div>
                            <input :type="showPass2 ? 'text' : 'password'" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="6"
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full pl-11 pr-12 py-3.5 min-h-[48px] bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white focus:border-transparent transition">
                            
                            <button type="button" @click="showPass2 = !showPass2" 
                                    aria-label="Tampilkan konfirmasi sandi"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition cursor-pointer min-w-[44px] justify-center">
                                <span x-show="!showPass2" class="text-sm">👁️</span>
                                <span x-show="showPass2" class="text-sm" style="display: none;">🙈</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Simpan Sandi Baru -->
                    <button type="submit" 
                            class="w-full min-h-[48px] bg-blue-900 hover:bg-blue-800 active:scale-[0.99] text-white font-black text-sm rounded-2xl shadow-lg shadow-blue-900/25 flex items-center justify-center gap-2 transition cursor-pointer mt-4">
                        <span>💾</span> Simpan Kata Sandi Baru & Masuk
                    </button>
                </form>

                <!-- Kembali ke Login -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-2 text-xs font-extrabold text-blue-900 hover:text-blue-700 hover:underline transition">
                        <span>⬅️</span> Batal dan Kembali ke Halaman Masuk
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
