<nav class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center">
    <!-- Judul Halaman Dinamis -->
    <div class="hidden md:flex items-center">
        <div class="px-6 py-2 border border-gray-100 rounded-2xl bg-gray-50 text-blue-900 font-black text-[10px] uppercase tracking-widest shadow-sm">
            @yield('title', 'Dashboard')
        </div>
    </div>
    
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-6 text-gray-400">
            
            <!-- Notifikasi -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative hover:text-blue-900 transition">
                    <i class="fas fa-bell text-lg"></i>
                    @if(($unreadNotificationsCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] w-4 h-4 flex items-center justify-center rounded-full font-black animate-pulse">
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
                </button>
                
                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-4 w-80 bg-white border border-gray-100 rounded-3xl shadow-xl z-50 overflow-hidden">
                    <div class="p-4 border-b border-gray-50 bg-gray-50/50 font-black text-[10px] uppercase tracking-widest text-blue-900 flex justify-between items-center">
                        Notifikasi Terbaru
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                            <div class="p-4 hover:bg-gray-50 border-b border-gray-50 text-sm {{ $notification->read_at ? 'opacity-50' : 'bg-blue-50/20' }}">
                                <p class="text-gray-800 font-bold text-xs">{{ $notification->data['message'] ?? 'Info baru' }}</p>
                                <span class="text-[9px] text-gray-400 font-black uppercase mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-400 text-xs font-bold">Tidak ada notifikasi</div>
                        @endforelse
                    </div>
                    <div class="p-3 bg-gray-50 text-center">
                        <a href="{{ route('notifications') }}" class="text-blue-900 text-[10px] font-black uppercase tracking-widest hover:underline">Lihat Semua</a>
                    </div>
                </div>
            </div>

            <!-- Pengaturan -->
            <a href="{{ route('settings.index') }}" class="hover:text-blue-900 transition">
                <i class="fas fa-cog text-lg"></i>
            </a>
        </div>

        <!-- Profil User -->
        <div class="flex items-center gap-4 border-l border-gray-100 pl-6">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ auth()->user()->jabatan ?? 'Guru' }}</p>
            </div>
            <img src="{{ auth()->user()->foto_url ?? asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full border-2 border-gray-100 object-cover shadow-sm">
        </div>
    </div>
</nav>