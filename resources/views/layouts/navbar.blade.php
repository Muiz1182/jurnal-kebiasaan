<nav class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between shadow-xs">
    
    <!-- Sisi Kiri: Tombol Hamburger Mobile & Judul Halaman -->
    <div class="flex items-center gap-3.5">
        <!-- Tombol Buka Menu Sidebar di Layar Mobile -->
        <button type="button" 
                @click="sidebarOpen = true" 
                aria-label="Buka navigasi menu"
                class="lg:hidden w-11 h-11 min-h-[44px] min-w-[44px] rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-900 bg-blue-50 px-2 py-0.5 rounded-md hidden sm:inline-block">
                    Portal Guru
                </span>
                <span class="text-xs text-slate-400 hidden sm:inline-block">•</span>
                <span class="text-xs text-slate-500 font-semibold hidden md:inline-block">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-tight mt-0.5">
                @yield('title', 'Dashboard Guru')
            </h2>
        </div>
    </div>
    
    <!-- Sisi Kanan: Notifikasi, Pengaturan & Profil Guru -->
    <div class="flex items-center gap-2 sm:gap-4">
        
        <!-- Dropdown Notifikasi -->
        <div class="relative" x-data="{ openNotif: false }">
            <button type="button" 
                    @click="openNotif = !openNotif" 
                    aria-label="Notifikasi"
                    class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition relative cursor-pointer">
                <i class="fas fa-bell text-sm"></i>
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                @if ($unreadCount > 0)
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                @endif
            </button>
            
            <div x-show="openNotif" 
                 @click.away="openNotif = false" 
                 x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 class="absolute right-0 mt-3 w-80 sm:w-96 bg-white border border-slate-200 rounded-3xl shadow-2xl z-50 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/70 flex justify-between items-center">
                    <span class="font-extrabold text-xs text-slate-900">Notifikasi Guru</span>
                    <span class="text-[11px] font-bold text-blue-900 bg-blue-100 px-2 py-0.5 rounded-full">
                        {{ $unreadCount }} Baru
                    </span>
                </div>
                <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                    @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                        <div class="p-4 hover:bg-slate-50 transition text-xs {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/30' }}">
                            <p class="font-bold text-slate-900">{{ $notification->data['message'] ?? 'Informasi kegiatan sekolah baru' }}</p>
                            <span class="text-[10px] text-slate-400 font-semibold mt-1 block">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-xs font-semibold">
                            Belum ada notifikasi baru.
                        </div>
                    @endforelse
                </div>
                <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                    <a href="{{ route('notifications') }}" class="text-xs font-extrabold text-blue-900 hover:text-blue-700 hover:underline">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Tombol Pengaturan Cepat -->
        <a href="{{ route('settings.index') }}" 
           aria-label="Pengaturan Profil"
           class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition">
            <i class="fas fa-cog text-sm"></i>
        </a>

        <!-- Profil Pill Guru -->
        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 pl-2 sm:pl-3 border-l border-slate-200 hover:opacity-90 transition">
            <div class="w-10 h-10 rounded-2xl bg-blue-900 text-white font-extrabold flex items-center justify-center shrink-0 shadow-sm text-sm border border-blue-700">
                {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
            </div>
            <div class="text-left hidden md:block">
                <p class="text-xs font-extrabold text-slate-900 truncate max-w-[150px]">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-500 font-semibold truncate">{{ auth()->user()->jabatan ?? 'Guru' }}</p>
            </div>
        </a>

    </div>
</nav>
