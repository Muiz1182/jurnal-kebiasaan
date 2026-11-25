<!-- OVERLAY BACKDROP UNTUK MOBILE DRAWER -->
<div x-show="sidebarOpen" 
     x-cloak
     @click="sidebarOpen = false" 
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden">
</div>

<!-- SIDEBAR ASIDE -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0A1128] text-white flex flex-col justify-between border-r border-slate-800 shrink-0 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-2xl lg:shadow-none h-full overflow-y-auto"
       x-data="{ 
           openAbsensi: {{ request()->routeIs('attendance.*') ? 'true' : 'false' }}, 
           openNilai: {{ request()->routeIs('grades.*') ? 'true' : 'false' }} 
       }">

    <div>
        <!-- Logo & Brand Header -->
        <div class="p-6 flex items-center justify-between border-b border-slate-800/80">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center p-2 shadow-md shrink-0">
                    <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo SMPN 9" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <span class="text-blue-900 font-black text-xs hidden">SMP9</span>
                </div>
                <div>
                    <h1 class="font-extrabold text-sm tracking-tight text-white leading-tight">Guru Portal</h1>
                    <p class="text-[10px] text-blue-300 font-bold tracking-wider uppercase mt-0.5">SMPN 9 Probolinggo</p>
                </div>
            </div>

            <!-- Tombol Tutup Sidebar di Mobile -->
            <button type="button" 
                    @click="sidebarOpen = false" 
                    aria-label="Tutup menu sidebar"
                    class="lg:hidden w-10 h-10 rounded-xl bg-slate-800/80 text-slate-400 hover:text-white flex items-center justify-center transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Navigasi Utama -->
        <nav class="p-4 space-y-1.5 text-sm font-semibold">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 min-h-[48px] rounded-2xl transition {{ request()->routeIs('dashboard') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/30 font-bold' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <div class="w-5 h-5 flex items-center justify-center text-base">
                    <i class="fas fa-th-large"></i>
                </div>
                <span>Dashboard Guru</span>
            </a>

            <!-- Jurnal Mengajar -->
            <a href="{{ route('journal.create') }}" 
               class="flex items-center gap-3 px-4 py-3 min-h-[48px] rounded-2xl transition {{ request()->routeIs('journal.*') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/30 font-bold' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <div class="w-5 h-5 flex items-center justify-center text-base">
                    <i class="fas fa-book-open"></i>
                </div>
                <span>Jurnal Mengajar</span>
            </a>

            <!-- Dropdown Absensi Siswa -->
            <div class="space-y-1">
                <button type="button" 
                        @click="openAbsensi = !openAbsensi" 
                        class="w-full flex items-center justify-between px-4 py-3 min-h-[48px] rounded-2xl transition cursor-pointer {{ request()->routeIs('attendance.*') ? 'bg-blue-900/40 text-blue-200 border border-blue-800/60 font-bold' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 flex items-center justify-center text-base">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span>Presensi Siswa</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="openAbsensi ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openAbsensi" x-cloak class="pl-11 pr-2 py-1 space-y-1">
                    <a href="{{ route('attendance.index') }}" 
                       class="block px-3 py-2.5 min-h-[44px] rounded-xl text-xs font-bold transition flex items-center {{ request()->routeIs('attendance.index') ? 'bg-blue-900 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                        Input Presensi Harian
                    </a>
                    <a href="{{ route('attendance.recap') }}" 
                       class="block px-3 py-2.5 min-h-[44px] rounded-xl text-xs font-bold transition flex items-center {{ request()->routeIs('attendance.recap') ? 'bg-blue-900 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                        Rekapitulasi Presensi
                    </a>
                </div>
            </div>

            <!-- Dropdown Penilaian Siswa -->
            <div class="space-y-1">
                <button type="button" 
                        @click="openNilai = !openNilai" 
                        class="w-full flex items-center justify-between px-4 py-3 min-h-[48px] rounded-2xl transition cursor-pointer {{ request()->routeIs('grades.*') ? 'bg-blue-900/40 text-blue-200 border border-blue-800/60 font-bold' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 flex items-center justify-center text-base">
                            <i class="fas fa-award"></i>
                        </div>
                        <span>Penilaian Siswa</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="openNilai ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openNilai" x-cloak class="pl-11 pr-2 py-1 space-y-1">
                    <a href="{{ route('grades.index') }}" 
                       class="block px-3 py-2.5 min-h-[44px] rounded-xl text-xs font-bold transition flex items-center {{ request()->routeIs('grades.index') ? 'bg-blue-900 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                        Input Nilai Siswa
                    </a>
                    <a href="{{ route('grades.recap') }}" 
                       class="block px-3 py-2.5 min-h-[44px] rounded-xl text-xs font-bold transition flex items-center {{ request()->routeIs('grades.recap') ? 'bg-blue-900 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                        Rekap &amp; Ekspor Nilai
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Sidebar Footer (Info Profil Guru & Tombol Logout) -->
    <div class="p-4 border-t border-slate-800/80 space-y-3">
        <!-- Tombol Pasang Aplikasi di HP -->
        <button type="button" 
                onclick="window.dispatchEvent(new CustomEvent('trigger-pwa-install'))"
                class="w-full min-h-[44px] flex items-center justify-center gap-2 px-3 py-2 rounded-2xl bg-slate-800/80 hover:bg-slate-700/80 text-blue-300 hover:text-white border border-slate-700/70 text-xs font-bold transition cursor-pointer">
            <span class="text-sm">📲</span>
            <span>Pasang Aplikasi Mobile</span>
        </button>

        <!-- Kartu Guru -->
        <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-900/80 border border-slate-800">
            <div class="w-10 h-10 rounded-xl bg-blue-900 text-white font-extrabold flex items-center justify-center shrink-0 shadow-sm text-sm border border-blue-700">
                {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-xs text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-semibold truncate">{{ auth()->user()->jabatan ?? 'Tenaga Pendidik' }}</p>
            </div>
        </div>

        <!-- Tombol Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full min-h-[48px] flex items-center justify-center gap-2.5 px-4 py-3 rounded-2xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 hover:text-rose-200 border border-rose-500/20 text-xs font-bold transition cursor-pointer">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar dari Portal</span>
            </button>
        </form>
    </div>
</aside>
