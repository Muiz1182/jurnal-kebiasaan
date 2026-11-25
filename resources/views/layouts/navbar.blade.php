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

        <!-- Tombol Pengaturan Cepat -->
        <a href="{{ route('settings.index') }}" 
           aria-label="Pengaturan Profil"
           class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition">
            <i class="fas fa-cog text-sm"></i>
        </a>

        <!-- DROPDOWN PROFIL GURU & MODAL EDIT PROFIL -->
        <div class="relative pl-2 sm:pl-3 border-l border-slate-200" 
             x-data="{ profileOpen: false, modalEditOpen: false }" 
             @keydown.escape.window="profileOpen = false; modalEditOpen = false">
            
            <!-- Trigger Tombol Profil Pill -->
            <button type="button" 
                    @click="profileOpen = !profileOpen" 
                    aria-label="Menu Akun Profil Guru"
                    :class="{ 'bg-slate-100 ring-2 ring-blue-900/20': profileOpen }"
                    class="flex items-center gap-2.5 sm:gap-3 p-1 sm:px-2.5 sm:py-1.5 rounded-2xl hover:bg-slate-100/90 active:scale-[0.98] transition min-h-[44px] cursor-pointer text-left focus:outline-none">
                
                <!-- Foto Avatar atau Inisial -->
                @if(auth()->user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->foto_profil))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-2xl object-cover border-2 border-blue-200 shadow-xs shrink-0">
                @else
                    <div class="w-10 h-10 rounded-2xl bg-blue-900 text-white font-extrabold flex items-center justify-center shrink-0 shadow-sm text-sm border border-blue-700">
                        {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                    </div>
                @endif

                <!-- Nama & Jabatan Guru Desktop -->
                <div class="text-left hidden md:block pr-1">
                    <p class="text-xs font-extrabold text-slate-900 truncate max-w-[150px] leading-tight">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-[10px] text-slate-500 font-semibold truncate mt-0.5">
                        {{ auth()->user()->jabatan ?? 'Tenaga Pendidik' }}
                    </p>
                </div>

                <!-- Chevron Icon -->
                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" 
                   :class="profileOpen ? 'rotate-180 text-blue-900' : ''"></i>
            </button>

            <!-- DROPDOWN MENU KONTEN -->
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
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-black text-white truncate">{{ auth()->user()->name }}</h4>
                            <p class="text-[10px] text-blue-200 font-medium truncate mt-0.5">{{ auth()->user()->email ?? auth()->user()->username }}</p>
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

                <!-- List Navigasi Aksi Dropdown -->
                <div class="space-y-1">
                    <!-- Tombol Buka Modal Edit Profil Cepat -->
                    <button type="button" 
                            @click="profileOpen = false; modalEditOpen = true" 
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-blue-50 text-slate-800 hover:text-blue-900 transition text-xs font-bold group cursor-pointer text-left">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-900 group-hover:bg-blue-100 flex items-center justify-center text-sm shrink-0">
                            ✏️
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-slate-900 group-hover:text-blue-950">Edit Profil Cepat</p>
                            <p class="text-[10px] text-slate-400 font-medium">Ubah nama, NIP, jabatan, foto &amp; sandi</p>
                        </div>
                    </button>

                    <!-- Link Halaman Pengaturan Profil Lengkap -->
                    <a href="{{ route('settings.index') }}" 
                       class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-50 text-slate-800 hover:text-blue-900 transition text-xs font-bold group cursor-pointer text-left">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-900 flex items-center justify-center text-sm shrink-0">
                            ⚙️
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-slate-900 group-hover:text-blue-950">Pengaturan Lengkap</p>
                            <p class="text-[10px] text-slate-400 font-medium">Impor data murid &amp; profil lengkap</p>
                        </div>
                    </a>

                    <!-- Link Informasi Sekolah -->
                    <a href="{{ route('settings.school') }}" 
                       class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-50 text-slate-800 hover:text-blue-900 transition text-xs font-bold group cursor-pointer text-left">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-900 flex items-center justify-center text-sm shrink-0">
                            🏫
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-slate-900 group-hover:text-blue-950">Informasi Sekolah</p>
                            <p class="text-[10px] text-slate-400 font-medium">Data profil lembaga sekolah</p>
                        </div>
                    </a>

                    <!-- Link Preferensi Notifikasi -->
                    <a href="{{ route('settings.notifications') }}" 
                       class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-50 text-slate-800 hover:text-blue-900 transition text-xs font-bold group cursor-pointer text-left">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-900 flex items-center justify-center text-sm shrink-0">
                            🔔
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-slate-900 group-hover:text-blue-950">Preferensi Notifikasi</p>
                            <p class="text-[10px] text-slate-400 font-medium">Saluran email &amp; browser</p>
                        </div>
                    </a>

                    <!-- Ganti Akun / Lupakan Perangkat -->
                    <a href="{{ route('login.forget-device') }}" 
                       class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-amber-50 text-amber-900 transition text-xs font-bold group cursor-pointer text-left">
                        <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 group-hover:bg-amber-100 flex items-center justify-center text-sm shrink-0">
                            🔄
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-amber-950">Ganti Akun</p>
                            <p class="text-[10px] text-amber-700 font-medium">Lupakan identitas di perangkat ini</p>
                        </div>
                    </a>

                    <div class="my-1 border-t border-slate-100"></div>

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
                                <p class="text-[10px] text-rose-400 font-medium">Akhiri sesi portal akun guru</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- MODAL EDIT PROFILE DIALOG (MUNCUL SAAT KLIK 'EDIT PROFIL CEPAT') -->
            <div x-show="modalEditOpen" 
                 class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div @click.outside="modalEditOpen = false" 
                     class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full max-h-[90vh] overflow-y-auto flex flex-col"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <!-- Header Modal -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-md z-10 rounded-t-3xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-lg font-bold">
                                👤
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900">Edit Profil Guru</h3>
                                <p class="text-[11px] text-slate-400 font-medium">Perbarui data profil &amp; kata sandi akun pendidik</p>
                            </div>
                        </div>
                        <button type="button" @click="modalEditOpen = false" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer text-lg font-bold">
                            &times;
                        </button>
                    </div>

                    <!-- Form Edit Profil -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')

                        <!-- Foto Profil Upload Section -->
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/70" x-data="{ previewPhoto: null }">
                            <div class="relative shrink-0">
                                <template x-if="previewPhoto">
                                    <img :src="previewPhoto" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-400 shadow-sm">
                                </template>
                                <template x-if="!previewPhoto">
                                    @if(auth()->user()->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->foto_profil))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->foto_profil) }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-100 shadow-sm">
                                    @else
                                        <div class="w-16 h-16 rounded-2xl bg-blue-900 text-white font-extrabold flex items-center justify-center text-xl shadow-md border-2 border-white">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                                        </div>
                                    @endif
                                </template>
                            </div>
                            <div class="space-y-1 min-w-0 flex-1">
                                <label class="block text-xs font-extrabold text-slate-700">Foto Profil Pendidik</label>
                                <input type="file" 
                                       name="foto_profil" 
                                       accept="image/*"
                                       @change="const file = $event.target.files[0]; if(file) { previewPhoto = URL.createObjectURL(file) }"
                                       class="text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-900 file:text-white hover:file:bg-blue-800 transition cursor-pointer">
                                <p class="text-[10px] text-slate-400">JPG, PNG atau WebP (Maksimal 2MB)</p>
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Nama Lengkap &amp; Gelar <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   required 
                                   class="w-full min-h-[44px] px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                        </div>

                        <!-- Baris Grid: NIP & Jabatan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700">NIP / NUPTK</label>
                                <input type="text" 
                                       name="nip" 
                                       value="{{ old('nip', auth()->user()->nip) }}" 
                                       placeholder="198xxxxxxxxxxxxxxx"
                                       class="w-full min-h-[44px] px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700">Jabatan</label>
                                <input type="text" 
                                       name="jabatan" 
                                       value="{{ old('jabatan', auth()->user()->jabatan) }}" 
                                       placeholder="GURU INFORMATIKA"
                                       class="w-full min-h-[44px] px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                            </div>
                        </div>

                        <!-- Baris Grid: Kelas Mengajar & Username -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700">Kelas Binaan / Mengajar</label>
                                <input type="text" 
                                       name="kelas_mengajar" 
                                       value="{{ old('kelas_mengajar', auth()->user()->kelas_mengajar) }}" 
                                       placeholder="Contoh: 9-A, 9-B"
                                       class="w-full min-h-[44px] px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700">Username</label>
                                <input type="text" 
                                       name="username" 
                                       value="{{ old('username', auth()->user()->username) }}" 
                                       class="w-full min-h-[44px] px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition">
                            </div>
                        </div>

                        <!-- Section Ubah Password (Opsional) -->
                        <div class="pt-3 border-t border-slate-100" x-data="{ changePass: false }">
                            <button type="button" 
                                    @click="changePass = !changePass" 
                                    class="text-xs font-bold text-blue-900 hover:text-blue-700 hover:underline flex items-center gap-1.5 cursor-pointer">
                                <span x-text="changePass ? '▼ Tutup opsi ganti kata sandi' : '▶ Ubah kata sandi akun'"></span>
                            </button>

                            <div x-show="changePass" x-cloak class="mt-3 space-y-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700">Kata Sandi Baru</label>
                                    <input type="password" 
                                           name="password" 
                                           placeholder="Minimal 6 karakter"
                                           class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700">Konfirmasi Sandi Baru</label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           placeholder="Ulangi kata sandi baru"
                                           class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-900 transition">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit Modal -->
                        <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                            <button type="button" 
                                    @click="modalEditOpen = false" 
                                    class="min-h-[44px] px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="min-h-[44px] px-6 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-black text-xs shadow-md shadow-blue-900/20 transition cursor-pointer">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</nav>
