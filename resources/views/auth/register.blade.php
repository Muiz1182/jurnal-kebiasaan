<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun Murid | SMPN 9 Probolinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen py-10">

    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-800">Pendaftaran Akun Murid</h2>
            <p class="text-gray-500 text-sm">Silakan lengkapi data untuk mengakses portal akademik</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required 
                       class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('username') border-red-500 @enderror">
                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- DIREVISI: Mengganti NIP menjadi NISN untuk Murid -->
            <div>
                <label for="nisn" class="block text-sm font-medium text-gray-700">NISN (Opsional)</label>
                <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional" 
                       class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('nisn') border-red-500 @enderror">
                @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- DIREVISI: Mengganti Jabatan menjadi Kelas untuk Murid -->
            <div>
                <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: 7A, 8B, 9C" 
                       class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('kelas') border-red-500 @enderror">
                @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Instansi (Opsional)</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="foto_profil" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                <input type="file" id="foto_profil" name="foto_profil" accept="image/*" 
                       class="mt-1 w-full p-3 border rounded-lg text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100 cursor-pointer">
                @error('foto_profil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <input type="password" id="password" name="password" required 
                           class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('password') border-red-500 @enderror">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required 
                           class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

            <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-medium hover:bg-blue-950 transition cursor-pointer">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <p class="text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-700 font-semibold">Masuk</a></p>
        </div>
    </div>

</body>
</html>