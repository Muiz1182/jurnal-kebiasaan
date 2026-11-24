<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- PENTING: Tambahkan ini untuk keamanan AJAX/Fetch -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') | SMPN 9 Probolinggo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- REVISI: Menambahkan Choices.js untuk fitur Multiple Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom styling agar Choices.js senada dengan desain Anda */
        .choices__inner {
            border-radius: 1rem !important; /* rounded-2xl */
            border: 1px solid #f3f4f6 !important; /* border-gray-100 */
            background-color: #f9fafb !important; /* bg-gray-50 */
            padding: 5px 12px !important;
        }
        .choices__input { background-color: transparent !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-900">
    <div class="flex min-h-screen">
        
        @include('layouts.sidebar')

        <main class="flex-1 flex flex-col min-w-0">
            
            @include('layouts.navbar')
            
            <div class="p-4 md:p-8 flex-1 overflow-x-hidden">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>