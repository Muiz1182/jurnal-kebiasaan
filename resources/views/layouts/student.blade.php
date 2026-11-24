<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Siswa') | SMPN 9 Probolinggo</title>
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

            <!-- Tombol Aksi Bawah Sidebar (Keluar) -->
            <div class="p-4 border-t border-gray-100 bg-white">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full min-h-[44px] flex items-center justify-center gap-2 px-4 py-2.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl font-bold text-xs transition cursor-pointer">
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

                    <!-- Profil User -->
                    <div class="flex items-center gap-3 pl-3 border-l border-gray-100">
                        <!-- Avatar / Inisial Nama -->
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-900 font-extrabold flex items-center justify-center border border-blue-100 shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                        </div>

                        <!-- Nama & Role User -->
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-extrabold text-gray-900 leading-tight">
                                {{ Auth::user()->name ?? 'Nama Siswa' }}
                            </p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                Murid
                            </p>
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
</body>
</html>