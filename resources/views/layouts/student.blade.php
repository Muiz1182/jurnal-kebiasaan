<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Siswa') | SMPN 9 Probolinggo</title>
    <!-- PWA & Mobile Web App Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0A1128">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Jurnal SMPN 9">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Interaktif -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900 min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    <!-- Global Toast Container (Anti-Slop System) -->
    <div x-data="toastManager()" 
         @toast.window="addToast($event.detail)"
         class="fixed top-4 inset-x-4 sm:inset-x-auto sm:top-5 sm:right-5 z-[9999] flex flex-col gap-2.5 max-w-sm sm:max-w-md w-full pointer-events-none"
         style="margin: 0 auto;">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 -translate-y-3 sm:translate-x-4 scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0 scale-100" 
                 x-transition:leave-end="opacity-0 -translate-y-2 sm:translate-x-4 scale-95" 
                 :class="{
                     'bg-slate-900/95 border-emerald-500/40 text-white shadow-emerald-950/20': toast.type === 'success',
                     'bg-slate-900/95 border-rose-500/40 text-white shadow-rose-950/20': toast.type === 'error',
                     'bg-slate-900/95 border-amber-500/40 text-white shadow-amber-950/20': toast.type === 'warning',
                     'bg-slate-900/95 border-sky-500/40 text-white shadow-sky-950/20': toast.type === 'info'
                 }"
                 class="pointer-events-auto rounded-2xl p-4 shadow-2xl border backdrop-blur-md flex items-start gap-3.5 transition-all">
                
                <!-- Icon Badge -->
                <div class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-sm font-bold"
                     :class="{
                         'bg-emerald-500/20 text-emerald-400': toast.type === 'success',
                         'bg-rose-500/20 text-rose-400': toast.type === 'error',
                         'bg-amber-500/20 text-amber-400': toast.type === 'warning',
                         'bg-sky-500/20 text-sky-400': toast.type === 'info'
                     }">
                    <span x-text="toast.icon"></span>
                </div>

                <!-- Message Body -->
                <div class="flex-1 min-w-0 pt-0.5">
                    <p class="text-xs font-black uppercase tracking-wider mb-0.5"
                       :class="{
                           'text-emerald-400': toast.type === 'success',
                           'text-rose-400': toast.type === 'error',
                           'text-amber-400': toast.type === 'warning',
                           'text-sky-400': toast.type === 'info'
                       }" 
                       x-text="toast.title"></p>
                    <div class="text-xs text-slate-200 font-medium leading-relaxed break-words" x-html="toast.message"></div>
                </div>

                <!-- Close Button (Min 36px touch target) -->
                <button type="button" @click="removeToast(toast.id)" 
                        class="shrink-0 w-8 h-8 rounded-xl hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition text-base leading-none cursor-pointer"
                        aria-label="Tutup notifikasi">
                    &times;
                </button>
            </div>
        </template>
    </div>

    <!-- Wrapper Utama Layout Dashboard -->
    <div class="flex h-screen overflow-hidden bg-gray-50 relative">
        
        <!-- BACKDROP OVERLAY UNTUK MOBILE SAAT SIDEBAR TERBUKA -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition.opacity></div>

        <!-- SIDEBAR KIRI (Responsive Mobile Drawer & Desktop Sticky) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto shrink-0">
            <div>
                <!-- Header Logo & Judul Aplikasi -->
                <div class="p-6 flex items-center justify-between border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo" class="w-10 h-10 rounded-2xl object-cover shadow-md border border-gray-100">
                        <div>
                            <h1 class="font-extrabold text-gray-900 text-sm">SMPN 9</h1>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Jurnal Pembiasaan</p>
                        </div>
                    </div>
                    <!-- Tombol Close Sidebar Khusus Mobile -->
                    <button @click="sidebarOpen = false" aria-label="Tutup Menu" class="lg:hidden min-h-[44px] min-w-[44px] flex items-center justify-center text-gray-400 hover:text-gray-600 p-2 rounded-xl">
                        ✕
                    </button>
                </div>

                <!-- Menu Navigasi Sesuai Design UI/UX -->
                <nav class="p-4 space-y-1 overflow-y-auto max-h-[calc(100vh-160px)]">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.dashboard') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v16z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('student.bangun-pagi') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.bangun-pagi') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>⏰</span> Bangun Pagi
                    </a>
                    <a href="{{ route('student.taat-beribadah') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.taat-beribadah') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>✨</span> Taat Beribadah
                    </a>
                    <a href="{{ route('student.olahraga') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.olahraga') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>💪</span> Rajin Olahraga
                    </a>
                    <a href="{{ route('student.makan-sehat') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.makan-sehat') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>🥗</span> Makan Sehat
                    </a>
                    <a href="{{ route('student.gemar-belajar') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.gemar-belajar') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>📚</span> Gemar Belajar
                    </a>
                    <a href="{{ route('student.aktif-masyarakat') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.aktif-masyarakat') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>👥</span> Aktif Masyarakat
                    </a>
                    <a href="{{ route('student.tidur-cepat') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.tidur-cepat') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>🌙</span> Tidur Cepat
                    </a>
                </nav>
            </div>

            <!-- Tombol Aksi Bawah Sidebar (Profil Murid, Pasang App & Keluar) -->
            <div class="p-4 border-t border-gray-100 bg-white space-y-2.5">
                <!-- Kartu Murid Sinkron -->
                <div class="p-2.5 rounded-2xl bg-gray-50 border border-gray-100 flex items-start gap-2.5">
                    @if(Auth::user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->foto_profil))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->foto_profil) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-10 h-10 rounded-2xl object-cover border-2 border-blue-200 shadow-xs shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-2xl bg-blue-900 text-white font-black flex items-center justify-center shrink-0 shadow-sm text-sm border border-blue-700">
                            {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1 space-y-1">
                        <!-- Nama Murid: mengalir ke bawah jika panjang -->
                        <p class="font-extrabold text-xs text-gray-900 leading-snug break-words">
                            {{ Auth::user()->name ?? 'Nama Siswa' }}
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="inline-block px-2 py-0.5 rounded-md bg-blue-100 text-blue-900 text-[10px] font-black uppercase tracking-wider">
                                {{ Auth::user()->kelas ? 'Kelas ' . Auth::user()->kelas : 'Murid' }}
                            </span>
                            @if(Auth::user()->nisn)
                                <span class="inline-block text-[10px] text-gray-500 font-mono font-semibold">
                                    NISN: {{ Auth::user()->nisn }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="button" 
                        onclick="window.dispatchEvent(new CustomEvent('trigger-pwa-install'))"
                        class="w-full min-h-[44px] flex items-center justify-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-900 rounded-xl font-bold text-xs transition cursor-pointer border border-blue-200/60">
                    <span>📲</span> Pasang Aplikasi di HP
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full min-h-[44px] flex items-center justify-center gap-2 px-4 py-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl font-bold text-xs transition cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- AREA KONTEN UTAMA DENGAN NAVBAR PROFIL -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- Navbar Atas: Profil User & Info -->
            <header class="bg-white border-b border-gray-100 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <!-- Tombol Hamburger Menu untuk Mobile -->
                    <button @click="sidebarOpen = true" aria-label="Buka Menu Navigasi" class="lg:hidden min-h-[44px] min-w-[44px] flex items-center justify-center p-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                        ☰
                    </button>
                    <div class="lg:hidden flex items-center gap-2.5">
                        <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo" class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl object-cover shadow-sm border border-gray-100">
                        <span class="font-extrabold text-gray-900 text-xs sm:text-sm">SMPN 9 Probolinggo</span>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Portal Jurnal Pembiasaan Murid</span>
                </div>

                <!-- Bagian Profil User di Kanan Navbar -->
                <div class="flex items-center gap-4 ml-auto">
                    <!-- Status Online -->
                    <div class="hidden sm:flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-gray-600">Online</span>
                    </div>

                    <!-- Profil User dengan Fitur Dropdown & Edit Profile -->
                    <div class="relative pl-3 border-l border-gray-100" x-data="{ profileOpen: false, editModalOpen: false }" @keydown.escape.window="profileOpen = false; editModalOpen = false">
                        <!-- Trigger Dropdown Tombol Profil -->
                        <button type="button" 
                                @click="profileOpen = !profileOpen" 
                                class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-2xl hover:bg-gray-100/80 active:scale-[0.98] transition min-h-[44px] cursor-pointer text-left focus:outline-none focus:ring-2 focus:ring-blue-900/20"
                                :class="{ 'bg-gray-100 ring-2 ring-blue-900/20': profileOpen }"
                                aria-haspopup="true" 
                                :aria-expanded="profileOpen">
                            <!-- Avatar / Foto Profil / Inisial -->
                            @if(Auth::user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->foto_profil))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->foto_profil) }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border border-blue-200 shadow-sm shrink-0">
                            @else
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-900 to-indigo-800 text-white font-extrabold flex items-center justify-center text-sm shadow-md shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                                </div>
                            @endif

                            <!-- Info Singkat User Desktop -->
                            <div class="hidden md:block text-left pr-1 max-w-[200px] lg:max-w-[240px]">
                                <p class="text-xs font-extrabold text-gray-900 leading-snug break-words">
                                    {{ Auth::user()->name ?? 'Nama Siswa' }}
                                </p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                    {{ Auth::user()->kelas ? 'Kelas ' . Auth::user()->kelas : 'Murid' }}
                                </p>
                            </div>

                            <!-- Chevron Arrow Icon -->
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" :class="profileOpen ? 'rotate-180 text-blue-900' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- DROPDOWN MENU KONTEN -->
                        <div x-show="profileOpen" 
                             @click.outside="profileOpen = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute right-0 mt-2.5 w-72 sm:w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 p-2 z-50 overflow-hidden"
                             style="display: none;">
                            
                            <!-- Header Info Kartu Murid -->
                            <div class="bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900 text-white p-4 rounded-2xl relative overflow-hidden mb-2">
                                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                                <div class="flex items-center gap-3 relative z-10">
                                    @if(Auth::user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->foto_profil))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->foto_profil) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white/20 shadow-md shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-white/15 text-white font-black flex items-center justify-center text-lg border border-white/20 shadow-inner shrink-0">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1 space-y-0.5">
                                        <h4 class="text-xs font-black text-white leading-snug break-words">{{ Auth::user()->name ?? 'Nama Siswa' }}</h4>
                                        <p class="text-[10px] text-blue-200 font-medium truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                            <span class="inline-block px-2 py-0.5 rounded-md bg-white/20 text-white text-[9px] font-black tracking-wider uppercase">
                                                {{ Auth::user()->kelas ? 'Kelas ' . Auth::user()->kelas : 'Murid' }}
                                            </span>
                                            @if(Auth::user()->nisn)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-white/10 text-blue-100 text-[9px] font-bold">
                                                    NISN: {{ Auth::user()->nisn }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- List Item Aksi Dropdown -->
                            <div class="space-y-1">
                                <!-- Tombol Buka Modal Edit Profile -->
                                <button type="button" 
                                        @click="profileOpen = false; editModalOpen = true" 
                                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-blue-50 text-slate-800 hover:text-blue-900 transition text-xs font-bold group cursor-pointer text-left">
                                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-900 group-hover:bg-blue-100 flex items-center justify-center text-sm shrink-0">
                                        ✏️
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-extrabold text-gray-900 group-hover:text-blue-950">Edit Profil</p>
                                        <p class="text-[10px] text-gray-400 font-medium">Ubah nama, kelas, NISN, foto & sandi</p>
                                    </div>
                                </button>

                                <div class="my-1 border-t border-gray-100"></div>

                                <!-- Tombol Keluar (Logout) -->
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-rose-50 text-rose-600 hover:text-rose-700 transition text-xs font-bold group cursor-pointer text-left">
                                        <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 group-hover:bg-rose-100 flex items-center justify-center text-sm shrink-0">
                                            🚪
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-xs font-extrabold text-rose-600">Keluar</p>
                                            <p class="text-[10px] text-rose-400 font-medium">Akhiri sesi portal akun murid</p>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- MODAL EDIT PROFILE DIALOG -->
                        <div x-show="editModalOpen" 
                             class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             style="display: none;">
                            
                            <div @click.outside="editModalOpen = false" 
                                 class="bg-white rounded-3xl shadow-2xl border border-gray-100 max-w-lg w-full max-h-[90vh] overflow-y-auto flex flex-col"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                                
                                <!-- Header Modal -->
                                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-md z-10 rounded-t-3xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg font-bold">
                                            👤
                                        </div>
                                        <div>
                                            <h3 class="text-base font-black text-gray-900">Edit Profil Siswa</h3>
                                            <p class="text-[11px] text-gray-400 font-medium">Perbarui data profil & kata sandi akun Anda</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="editModalOpen = false" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center transition cursor-pointer text-lg font-bold">
                                        &times;
                                    </button>
                                </div>

                                <!-- Form Edit Profil -->
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                                    @csrf
                                    @method('PATCH')

                                    <!-- Foto Profil Upload Section -->
                                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100" x-data="{ previewPhoto: null }">
                                        <div class="relative shrink-0">
                                            <template x-if="previewPhoto">
                                                <img :src="previewPhoto" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-400 shadow-sm">
                                            </template>
                                            <template x-if="!previewPhoto">
                                                @if(Auth::user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->foto_profil))
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->foto_profil) }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-100 shadow-sm">
                                                @else
                                                    <div class="w-16 h-16 rounded-2xl bg-blue-900 text-white font-black text-xl flex items-center justify-center border-2 border-blue-200 shadow-inner">
                                                        {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1">Foto Profil</label>
                                            <input type="file" name="foto_profil" accept="image/*" 
                                                   @change="const file = $event.target.files[0]; if(file) { previewPhoto = URL.createObjectURL(file); }"
                                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100 transition cursor-pointer">
                                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Maksimal 2 MB)</p>
                                        </div>
                                    </div>

                                    <!-- Nama Lengkap -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-800">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required 
                                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent bg-slate-50/50">
                                    </div>

                                    <!-- Grid Kelas & NISN -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Kelas -->
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-800">Kelas</label>
                                            <input type="text" name="kelas" value="{{ old('kelas', Auth::user()->kelas) }}" placeholder="Contoh: 7A, 8C, 9B" 
                                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent bg-slate-50/50">
                                        </div>

                                        <!-- NISN -->
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-800">NISN</label>
                                            <input type="text" name="nisn" value="{{ old('nisn', Auth::user()->nisn) }}" placeholder="Nomor Induk Siswa" 
                                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent bg-slate-50/50">
                                        </div>
                                    </div>

                                    <!-- Username / Email -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-800">Username / Email Akun</label>
                                        <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" 
                                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent bg-slate-50/50">
                                        <p class="text-[10px] text-slate-400">Digunakan untuk login ke sistem portal jurnal murid.</p>
                                    </div>

                                    <!-- Ganti Password (Opsional) -->
                                    <div class="pt-2 border-t border-slate-100 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-slate-800">Ganti Password (Opsional)</span>
                                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">Biarkan kosong jika tetap</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="space-y-1">
                                                <label class="block text-[11px] font-semibold text-slate-600">Password Baru</label>
                                                <input type="password" name="password" placeholder="Minimal 6 karakter" 
                                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 bg-slate-50/50">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-[11px] font-semibold text-slate-600">Ulangi Password Baru</label>
                                                <input type="password" name="password_confirmation" placeholder="Ketik ulang password" 
                                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 bg-slate-50/50">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi Modal Footer -->
                                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                        <button type="button" @click="editModalOpen = false" 
                                                class="min-h-[44px] px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition cursor-pointer">
                                            Batal
                                        </button>
                                        <button type="submit" 
                                                class="min-h-[44px] px-6 py-2.5 bg-blue-900 hover:bg-blue-800 active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg shadow-blue-950/20 flex items-center justify-center gap-2 transition cursor-pointer">
                                            <span>💾</span> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Konten Halaman Dinamis -->
            <div class="flex-1">
                @yield('content')
            </div>

        </main>

    </div>

    <!-- Toast Notification Logic -->
    <script>
        function toastManager() {
            return {
                toasts: [
                    @if(session('success'))
                    {
                        id: 'sess_success_' + Date.now(),
                        type: 'success',
                        title: 'Berhasil',
                        icon: '✨',
                        message: {!! json_encode(session('success')) !!},
                        visible: true,
                        duration: 4000
                    },
                    @endif
                    @if(session('error'))
                    {
                        id: 'sess_error_' + Date.now(),
                        type: 'error',
                        title: 'Perhatian',
                        icon: '⚠️',
                        message: {!! json_encode(session('error')) !!},
                        visible: true,
                        duration: 6000
                    },
                    @endif
                    @if(session('warning'))
                    {
                        id: 'sess_warning_' + Date.now(),
                        type: 'warning',
                        title: 'Peringatan',
                        icon: '⚡',
                        message: {!! json_encode(session('warning')) !!},
                        visible: true,
                        duration: 5000
                    },
                    @endif
                    @if(session('info'))
                    {
                        id: 'sess_info_' + Date.now(),
                        type: 'info',
                        title: 'Informasi',
                        icon: 'ℹ️',
                        message: {!! json_encode(session('info')) !!},
                        visible: true,
                        duration: 4000
                    },
                    @endif
                    @if(isset($errors) && $errors->any())
                    {
                        id: 'sess_errors_' + Date.now(),
                        type: 'error',
                        title: 'Validasi Formulir',
                        icon: '⚠️',
                        message: {!! json_encode(implode('<br>', $errors->all())) !!},
                        visible: true,
                        duration: 7000
                    },
                    @endif
                ],
                init() {
                    this.toasts.forEach(t => this.scheduleDismiss(t));
                },
                addToast({ message, type = 'success', title = null, icon = null, duration = null }) {
                    const id = 'toast_' + Date.now() + '_' + Math.random().toString(36).substr(2, 4);
                    const icons = { success: '✨', error: '⚠️', warning: '⚡', info: 'ℹ️' };
                    const titles = { success: 'Berhasil', error: 'Perhatian', warning: 'Peringatan', info: 'Informasi' };
                    const dur = duration || (type === 'error' ? 6000 : 4000);
                    
                    const toast = {
                        id: id,
                        type: type,
                        title: title || titles[type] || 'Notifikasi',
                        icon: icon || icons[type] || '✨',
                        message: message,
                        visible: true,
                        duration: dur
                    };
                    this.toasts.push(toast);
                    this.scheduleDismiss(toast);
                },
                scheduleDismiss(toast) {
                    if (toast.duration > 0) {
                        setTimeout(() => {
                            this.removeToast(toast.id);
                        }, toast.duration);
                    }
                },
                removeToast(id) {
                    const idx = this.toasts.findIndex(t => t.id === id);
                    if (idx !== -1) {
                        this.toasts[idx].visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                }
            };
        }

        window.showToast = function(message, type = 'success', title = null) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message, type, title }
            }));
        };
    </script>

    <!-- PWA Install Prompt Banner & Script -->
    @include('partials.pwa-install-banner')
</body>
</html>