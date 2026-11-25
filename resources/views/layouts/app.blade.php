<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Guru') | SMPN 9 Probolinggo</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Choices.js untuk Multiple Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom styling agar Choices.js rapi dan serasi dengan desain */
        .choices__inner {
            border-radius: 1rem !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            padding: 6px 14px !important;
            min-height: 48px !important;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .choices__input { 
            background-color: transparent !important; 
            font-size: 0.875rem !important;
        }
        .choices__list--multiple .choices__item {
            background-color: #1e3a8a !important;
            border: 1px solid #1e3a8a !important;
            border-radius: 0.75rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            padding: 3px 10px !important;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 min-h-screen lg:h-screen lg:overflow-hidden antialiased selection:bg-blue-600 selection:text-white"
      x-data="{ 
          sidebarOpen: false,
          toasts: []
      }">

    <!-- FLOATING TOAST NOTIFICATION SYSTEM -->
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0">
        @if (session('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="pointer-events-auto p-4 rounded-2xl bg-white border border-emerald-200 shadow-xl shadow-emerald-950/10 flex items-start gap-3 text-slate-900">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-base shrink-0 border border-emerald-100">
                    ✓
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <p class="font-extrabold text-xs text-emerald-950">Berhasil</p>
                    <p class="text-xs text-slate-600 mt-0.5">{{ session('success') }}</p>
                </div>
                <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
                    ✕
                </button>
            </div>
        @endif

        @if (session('warning'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 6000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="pointer-events-auto p-4 rounded-2xl bg-white border border-amber-200 shadow-xl shadow-amber-950/10 flex items-start gap-3 text-slate-900">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-base shrink-0 border border-amber-100">
                    !
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <p class="font-extrabold text-xs text-amber-950">Perhatian</p>
                    <p class="text-xs text-slate-600 mt-0.5">{{ session('warning') }}</p>
                </div>
                <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
                    ✕
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 7000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 class="pointer-events-auto p-4 rounded-2xl bg-white border border-rose-200 shadow-xl shadow-rose-950/10 flex items-start gap-3 text-slate-900">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-base shrink-0 border border-rose-100">
                    ✕
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <p class="font-extrabold text-xs text-rose-950">Terjadi Kesalahan</p>
                    <p class="text-xs text-slate-600 mt-0.5">{{ $errors->first() }}</p>
                </div>
                <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
                    ✕
                </button>
            </div>
        @endif
    </div>

    <!-- MAIN APP WRAPPER -->
    <div class="flex min-h-screen lg:h-screen relative">
        
        <!-- SIDEBAR GURU (DESKTOP STATIC & MOBILE DRAWER) -->
        @include('layouts.sidebar')

        <!-- KONTEN UTAMA KANAN (SCROLL MANDIRI DI DESKTOP) -->
        <main class="flex-1 flex flex-col min-w-0 min-h-screen lg:h-screen lg:overflow-y-auto">
            
            <!-- NAVBAR ATAS RESPONSIVE -->
            @include('layouts.navbar')
            
            <!-- CONTENT INJECTION -->
            <div class="p-4 sm:p-6 lg:p-8 flex-1 overflow-x-hidden">
                @yield('content')
            </div>

            <!-- FOOTER PORTAL -->
            <footer class="border-t border-slate-200/70 bg-white px-6 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>&copy; {{ date('Y') }} SMPN 9 Probolinggo. Portal Akademik &amp; Guru.</span>
                <span class="text-slate-400 font-semibold">Sistem Jurnal dan Akademik v2.0</span>
            </footer>
        </main>
    </div>

</body>
</html>
