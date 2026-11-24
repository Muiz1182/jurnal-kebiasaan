<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required'], // Bisa menerima email atau username
            'password' => ['required'],
        ]);

        // Coba cari user berdasarkan email ATAU username
        $user = User::where('email', $request->email)
                    ->orWhere('username', $request->email)
                    ->first();

        if (!$user) {
            Log::info('Login Gagal: User tidak ditemukan: ' . $request->email);
            return back()->withErrors(['email' => 'Email atau username tidak terdaftar.']);
        }

        // Cek password secara manual
        if (!Hash::check($request->password, $user->password)) {
            Log::info('Login Gagal: Password tidak cocok untuk: ' . $request->email);
            return back()->withErrors(['email' => 'Password yang Anda masukkan salah.']);
        }

        // Jika user ditemukan dan password cocok, lakukan login manual
        // Menambahkan parameter '$request->has('remember')' agar fitur "remember me" berfungsi
        $remember = $request->has('remember');
        Auth::login($user, $remember);
        
        $request->session()->regenerate();

        // DIREVISI: Ubah status user menjadi aktif (1) saat berhasil login
        if ($user) {
            $user->is_active = 1;
            $user->save();
        }

        // REVISI: Redirect langsung ke dashboard role yang sesuai agar tidak terjebak di session intended yang expired
        $role = strtolower(trim($user->role));

        if ($role === 'murid') {
            return redirect()->route('student.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request) {
        // DIREVISI: Ubah status user menjadi tidak aktif (0) sebelum sesi di-destroy
        $user = Auth::user();
        if ($user) {
            $user->is_active = 0;
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}