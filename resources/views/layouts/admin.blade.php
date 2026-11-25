<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - SMPN 9 Probolinggo</title>
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
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- FontAwesome untuk ikon pendukung -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900 flex h-screen overflow-hidden">

    <!-- SIDEBAR KIRI -->
    <aside class="w-72 bg-[#0A1128] text-white flex flex-col justify-between border-r border-gray-800 shrink-0">
        <div>
            <!-- Logo & Brand Header -->
            <div class="p-6 flex items-center gap-3 border-b border-gray-800/65">
                <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center shadow-md">
                    <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo" class="w-6 h-6 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <span class="text-blue-900 font-black text-xs hidden">SMP9</span>
                </div>
                <div>
                    <h1 class="font-extrabold text-sm tracking-wide">Admin Portal</h1>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Super Administrator</p>
                </div>
            </div>

            <!-- Menu Navigasi Sesuai UI/UX -->
            <nav class="p-4 space-y-1.5 text-sm font-medium">
                <!-- REVISI: Mengarahkan menu dashboard admin ke route admin.dashboard yang benar -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/20 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }} transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.teachers.*') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/20 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }} transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Teacher Management
                </a>

                @php
                    $pendingResetCount = \App\Models\PasswordResetRequest::where('status', 'pending')->where('expires_at', '>', now())->count();
                @endphp
                <a href="{{ route('admin.password-resets.index') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl {{ request()->routeIs('admin.password-resets.*') ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/20 font-bold' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }} transition">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        <span>Reset Sandi Murid</span>
                    </div>
                    @if ($pendingResetCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-slate-900 animate-pulse">
                            {{ $pendingResetCount }}
                        </span>
                    @endif
                </a>

                <!-- <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Journal Summaries
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Class Schedules
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    School Reports
                </a> -->
            </nav>
        </div>

        <!-- Sidebar Footer (Tombol Tambah Guru Cepat & Logout) -->
        <div class="p-4 space-y-3 border-t border-gray-800/60">
            <a href="{{ route('admin.teachers.create') }}" class="w-full bg-blue-900 text-white py-3 px-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-800 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Teacher
            </a>

            <div class="pt-2 space-y-1">
                <!-- <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800/50 hover:text-white text-xs font-semibold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a> -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 text-xs font-semibold transition text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA KANAN -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- HEADER ATAS -->
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center gap-6 w-1/3">
                <span class="font-black text-gray-800 text-sm tracking-tight">SMPN 9 Probolinggo Admin</span>
            </div>

            <!-- Global Search & Notifikasi -->
            <div class="flex items-center gap-4">
                <div class="relative w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Search teachers..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-950 transition">
                </div>

                <a href="{{ route('notifications') }}" class="p-2.5 text-gray-500 hover:bg-gray-100 rounded-full transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </a>

                <button class="p-2.5 text-gray-500 hover:bg-gray-100 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>

                <!-- Profile Info -->
                <div class="flex items-center gap-3 pl-3 border-l border-gray-200">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-900">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Profile Settings</p>
                    </div>
                    <div class="w-9 h-9 rounded-2xl bg-blue-900 text-white font-bold flex items-center justify-center text-xs overflow-hidden shadow-md">
                        {{ strtoupper(substr(auth()->user()->name ?? 'SA', 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- KONTEN DINAMIS -->
        <main class="flex-1 overflow-y-auto bg-gray-50/50">
            @yield('content')
        </main>

        <!-- FOOTER ADMIN -->
        <footer class="bg-white border-t border-gray-100 px-8 py-4 flex items-center justify-between text-xs text-gray-400 font-medium shrink-0">
            <p>&copy; {{ date('Y') }} SMPN 9 Probolinggo Admin Portal. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-6">
                <!-- <a href="#" class="hover:text-gray-600 transition">Panduan Pengguna</a>
                <a href="#" class="hover:text-gray-600 transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-gray-600 transition">Bantuan</a> -->
            </div>
        </footer>

    </div>

    <!-- PWA Install Prompt Banner & Script -->
    @include('partials.pwa-install-banner')
</body>
</html>