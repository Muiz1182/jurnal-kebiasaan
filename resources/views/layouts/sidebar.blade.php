<aside class="w-64 bg-gray-900 text-white p-6 flex flex-col justify-between" 
       x-data="{ openAbsensi: {{ request()->routeIs('attendance.*') ? 'true' : 'false' }}, openNilai: {{ request()->routeIs('grades.*') ? 'true' : 'false' }} }">
    <div>
        <!-- Logo Section -->
        <div class="flex flex-col items-center mb-10 text-center">
            <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo" class="w-16 h-16 mb-4 rounded-2xl shadow-lg border border-gray-800">
            <h2 class="font-black text-sm tracking-tight text-white">SMPN 9 PROBOLINGGO</h2>
            <p class="text-[9px] text-gray-500 uppercase tracking-[0.2em] font-black mt-1">Academic Portal</p>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} rounded-2xl transition font-black uppercase text-[10px] tracking-widest">
                <i class="fas fa-th-large text-sm"></i>
                Dashboard
            </a>

            <a href="{{ route('journal.create') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('journal.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} rounded-2xl transition font-black uppercase text-[10px] tracking-widest">
                <i class="fas fa-book text-sm"></i>
                Jurnal Mengajar
            </a>

            <!-- Dropdown Absensi -->
            <div>
                <button @click="openAbsensi = !openAbsensi" class="w-full flex items-center justify-between p-3 {{ request()->routeIs('attendance.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} rounded-2xl transition font-black uppercase text-[10px] tracking-widest">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users text-sm"></i>
                        <span>Absensi</span>
                    </div>
                    <i class="fas fa-chevron-down text-[8px] transition-transform" :class="openAbsensi ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openAbsensi" x-cloak class="mt-1 ml-4 pl-4 border-l border-gray-800 space-y-1">
                    <a href="{{ route('attendance.index') }}" class="block p-2 text-[10px] font-bold {{ request()->routeIs('attendance.index') ? 'text-white' : 'text-gray-500 hover:text-white' }} transition">Input Data</a>
                    <a href="{{ route('attendance.recap') }}" class="block p-2 text-[10px] font-bold {{ request()->routeIs('attendance.recap') ? 'text-white' : 'text-gray-500 hover:text-white' }} transition">Rekap Absensi</a>
                </div>
            </div>

            <!-- Dropdown Nilai -->
            <div>
                <button @click="openNilai = !openNilai" class="w-full flex items-center justify-between p-3 {{ request()->routeIs('grades.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} rounded-2xl transition font-black uppercase text-[10px] tracking-widest">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-alt text-sm"></i>
                        <span>Input Nilai</span>
                    </div>
                    <i class="fas fa-chevron-down text-[8px] transition-transform" :class="openNilai ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openNilai" x-cloak class="mt-1 ml-4 pl-4 border-l border-gray-800 space-y-1">
                    <a href="{{ route('grades.index') }}" class="block p-2 text-[10px] font-bold {{ request()->routeIs('grades.index') ? 'text-white' : 'text-gray-500 hover:text-white' }} transition">Input Data Nilai</a>
                    <a href="{{ route('grades.recap') }}" class="block p-2 text-[10px] font-bold {{ request()->routeIs('grades.recap') ? 'text-white' : 'text-gray-500 hover:text-white' }} transition">Rekap Nilai</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-800 pt-6">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 text-red-500 hover:text-red-400 transition font-black uppercase text-[10px] tracking-widest">
            <i class="fas fa-sign-out-alt"></i> 
            Logout
        </button>
    </form>
</aside>