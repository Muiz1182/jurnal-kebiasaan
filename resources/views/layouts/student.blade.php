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
<body class="bg-gray-50 font-sans antialiased text-gray-900 min-h-screen flex flex-col" x-data="{ sidebarOpen: false, activeForm: 'form-bangun-pagi' }" x-init="activeForm = 'form-bangun-pagi'">

    <!-- Notifikasi Flash Message -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 bg-emerald-600 text-white px-6 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-bold">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 bg-red-600 text-white px-6 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-bold">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

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
                <nav class="p-4 space-y-1 overflow-y-auto max-h-[calc(100vh-220px)]">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.dashboard') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v16z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('student.bangun-pagi') }}" @click="activeForm = 'form-bangun-pagi'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.bangun-pagi') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>⏰</span> Bangun Pagi
                    </a>
                    <a href="{{ route('student.taat-beribadah') }}" @click="activeForm = 'form-taat-beribadah'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.taat-beribadah') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>✨</span> Taat Beribadah
                    </a>
                    <!-- DIREVISI: Mengaktifkan rute ke halaman Rajin Olahraga -->
                    <a href="{{ route('student.olahraga') }}" @click="activeForm = 'form-olahraga'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.olahraga') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>💪</span> Rajin Olahraga
                    </a>
                    <a href="{{ route('student.makan-sehat') }}" @click="activeForm = 'form-makan-sehat'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.makan-sehat') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>🥗</span> Makan Sehat
                    </a>
                    <a href="{{ route('student.gemar-belajar') }}" @click="activeForm = 'form-gemar-belajar'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.gemar-belajar') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>📚</span> Gemar Belajar
                    </a>
                    <a href="{{ route('student.aktif-masyarakat') }}" @click="activeForm = 'form-aktif-masyarakat'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.aktif-masyarakat') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>👥</span> Aktif Masyarakat
                    </a>
                    <a href="{{ route('student.tidur-cepat') }}" @click="activeForm = 'form-tidur-cepat'" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('student.tidur-cepat') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/10 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} rounded-2xl text-sm transition">
                        <span>🌙</span> Tidur Cepat
                    </a>
                </nav>
            </div>

            <!-- Tombol Aksi Bawah Sidebar (Simpan Jurnal & Keluar) -->
            <div class="p-4 border-t border-gray-50 space-y-2 bg-white">
                <!-- DIREVISI: Tombol Simpan Jurnal Dinamis berdasarkan halaman aktif -->
                <button type="submit" :form="activeForm" class="w-full py-3 bg-emerald-700 text-white rounded-2xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-700/10 hover:bg-emerald-800 transition">
                    Simpan Jurnal
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl font-bold text-xs transition">
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

</body>
</html>