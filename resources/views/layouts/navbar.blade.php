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
    
    <!-- Sisi Kanan: Notifikasi, Pengaturan & Profil Guru Dropdown -->
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

        <!-- DROPDOWN PROFIL GURU -->
        <div class="relative pl-2 sm:pl-3 border-l border-slate-200" 
             x-data="{ profileOpen: false }" 
             @keydown.escape.window="profileOpen = false">
            
            <!-- Trigger Tombol Profil Pill -->
            <button type="button" 
                    @click="profileOpen = !profileOpen" 
                    aria-label="Menu Akun Profil Guru"
                    :class="{ 'bg-slate-100 ring-2 ring-blue-900/20': profileOpen }"
                    class="flex items-center gap-2.5 sm:gap-3 p-1 sm:px-2.5 sm:py-1.5 rounded-2xl hover:bg-slate-100/90 active:scale-[0.98] transition min-h-[48px] cursor-pointer text-left focus:outline-none">
                
                <!-- Foto Avatar atau Inisial -->
                @if(auth()->user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->foto_profil))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-2xl object-cover border-2 border-blue-200 shadow-xs shrink-0">
                @else
                    <div class="w-10 h-10 rounded-2xl bg-blue-900 text-white font-extrabold flex items-center justify-center shrink-0 shadow-sm text-sm border border-blue-700">
                        {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                    </div>
                @endif

                <!-- Nama & Jabatan Guru Desktop (Nama mengalir ke bawah jika panjang) -->
                <div class="text-left hidden md:block pr-1 max-w-[200px] lg:max-w-[240px]">
                    <p class="text-xs font-extrabold text-slate-900 leading-snug break-words">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-[10px] text-slate-500 font-semibold truncate mt-0.5">
                        {{ auth()->user()->jabatan ?? 'Tenaga Pendidik' }}
                    </p>
                </div>

                <!-- Chevron Icon -->
                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 shrink-0" 
                   :class="profileOpen ? 'rotate-180 text-blue-900' : ''"></i>
            </button>

            <!-- DROPDOWN MENU KONTEN: HANYA PENGATURAN DAN KELUAR -->
            <div x-show="profileOpen" 
                 @click.away="profileOpen = false" 
                 x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="absolute right-0 mt-3 w-72 sm:w-80 bg-white rounded-3xl shadow-2xl border border-slate-200/80 p-2.5 z-50 overflow-hidden">
                
                <!-- Header Kartu Guru -->
                <div class="bg-gradient-to-br from-[#0A1128] via-blue-950 to-slate-900 text-white p-4 rounded-2xl relative overflow-hidden mb-2">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center gap-3 relative z-10">
                        @if(auth()->user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->foto_profil))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white/20 shadow-md shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-white/15 text-white font-black flex items-center justify-center text-lg border border-white/20 shadow-inner shrink-0">
                                {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1 space-y-0.5">
                            <h4 class="text-xs font-black text-white leading-snug break-words">{{ auth()->user()->name }}</h4>
                            <p class="text-[10px] text-blue-200 font-medium truncate">{{ auth()->user()->email ?? auth()->user()->username }}</p>
                            <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                <span class="inline-block px-2 py-0.5 rounded-md bg-white/20 text-white text-[9px] font-black tracking-wider uppercase">
                                    {{ auth()->user()->jabatan ?? 'Guru' }}
                                </span>
                                @if(auth()->user()->nip)
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-white/10 text-blue-100 text-[9px] font-bold">
                                        NIP: {{ auth()->user()->nip }}
                                    </span>
                                @endif
                                @if(auth()->user()->kelas_mengajar)
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-blue-500/20 text-blue-200 text-[9px] font-bold">
                                        Kelas: {{ auth()->user()->kelas_mengajar }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List Navigasi Aksi Dropdown: Hanya Pengaturan dan Keluar -->
                <div class="space-y-1">
                    <!-- Link Halaman Pengaturan -->
                    <a href="{{ route('settings.index') }}" 
                       class="w-full flex items-center gap-3 px-3.5 py-3 rounded-2xl hover:bg-slate-50 text-slate-800 hover:text-blue-900 transition text-xs font-bold group cursor-pointer text-left min-h-[48px]">
                        <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-900 flex items-center justify-center text-sm shrink-0 transition">
                            ⚙️
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-black text-slate-900 group-hover:text-blue-950">Pengaturan</p>
                            <p class="text-[10px] text-slate-400 font-medium truncate">Kelola data profil, sandi &amp; sistem</p>
                        </div>
                    </a>

                    <div class="my-1 border-t border-slate-100"></div>

                    <!-- Tombol Keluar (Logout) -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 px-3.5 py-3 rounded-2xl hover:bg-rose-50 text-rose-600 hover:text-rose-700 transition text-xs font-bold group cursor-pointer text-left min-h-[48px]">
                            <span class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-100 flex items-center justify-center text-sm shrink-0 transition">
                                🚪
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-rose-600">Keluar</p>
                                <p class="text-[10px] text-rose-400 font-medium truncate">Akhiri sesi portal akun guru</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</nav>
