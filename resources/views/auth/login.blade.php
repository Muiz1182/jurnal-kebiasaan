<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Portal | SMPN 9 Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-white border-b border-gray-200 py-4 px-8 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-900">SMPN 9 Probolinggo</h1>
        <div class="space-x-6 text-sm font-medium text-gray-600">
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 w-full max-w-md">
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gray-100 mx-auto mb-4 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/LOGO SMP 9 TRANS.png') }}" alt="Logo" class="w-10">
                </div>
                <h2 class="text-2xl font-semibold text-gray-800">Masuk ke Portal</h2>
                <p class="text-gray-500 text-sm">Sistem Administrasi Akademik Terpadu</p>
            </div>

            {{-- Pesan Error Global / Sesi Kedaluwarsa --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-4 p-3 bg-amber-50 text-amber-700 text-sm rounded-lg border border-amber-200">
                    {{ session('warning') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email atau Username</label>
                    <input type="text" name="email" value="{{ old('email') }}" required autofocus 
                           class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-500 @enderror">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <input type="password" name="password" required
                           class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-2 h-4 w-4 text-blue-800 rounded"> 
                        <span class="text-gray-600">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-blue-700 font-semibold hover:underline">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" class="w-full bg-blue-800 text-white py-3 rounded-lg font-medium hover:bg-blue-900 transition">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-500 mb-2">Belum memiliki akun?</p>
                <a href="{{ route('register') }}" class="block w-full text-center py-3 border border-green-600 text-green-700 font-semibold rounded-lg hover:bg-green-50 transition">
                    Daftar Akun Baru
                </a>
            </div>
        </div>
    </main>

    <footer class="p-8 text-sm text-gray-500 flex justify-between border-t border-gray-200">
        <p>&copy; 2026 SMPN 9 Probolinggo. Sistem Administrasi Akademik Terpadu.</p>
        <div class="space-x-4">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Syarat & Ketentuan</a>
            <a href="#">Kontak IT Support</a>
        </div>
    </footer>

    <script>
        // Mencegah error 419 CSRF Token Mismatch akibat restore halaman dari Back-Forward Cache (bfcache)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>