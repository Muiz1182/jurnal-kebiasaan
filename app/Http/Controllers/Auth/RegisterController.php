<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'kelas' => ['required', 'string', 'max:255'], // Disesuaikan untuk murid
            'nisn' => ['nullable', 'string', 'max:50', 'unique:users,nisn'], // Disesuaikan untuk murid
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Maks 2MB
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'role' => 'murid', // Otomatis set sebagai murid
            'is_active' => true,
            'password' => Hash::make($request->password),
        ];

        // Menangani upload foto jika ada
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $userData['foto_profil'] = $path;
        }

        $user = User::create($userData);

        Auth::login($user);

        // DIREVISI: Set is_active menjadi 1 saat login setelah registrasi
        if ($user) {
            $user->is_active = 1;
            $user->save();

            // Pengecekan role yang konsisten agar redirection akurat
            $role = strtolower(trim($user->role));

            if ($role === 'murid') {
                return redirect()->route('student.dashboard')->with('success', 'Akun berhasil dibuat dan berhasil masuk!');
            } elseif ($role === 'guru') {
                return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat dan berhasil masuk!');
            }
        }

        // Diperbarui agar diarahkan langsung ke dashboard khusus murid / default
        return redirect()->route('student.dashboard')->with('success', 'Akun berhasil dibuat dan berhasil masuk!');
    }
}