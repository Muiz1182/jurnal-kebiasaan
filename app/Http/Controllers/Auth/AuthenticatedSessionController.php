<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan ini
use Illuminate\Support\Facades\Artisan; // Tambahan untuk pembersih cache

class AuthenticatedSessionController extends Controller
{
    // Menampilkan form login
    public function create() {
        return view('auth.login');
    }

    // Memproses login
    public function store(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // LOGGING untuk debugging:
        // Cek apakah user ada di database
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            Log::warning('Login gagal: Email tidak ditemukan - ' . $request->email);
        } else {
            // Cek apakah password cocok secara manual
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                Log::warning('Login gagal: Password tidak cocok untuk email - ' . $request->email);
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // TAMBAHAN: Bersihkan cache otomatis agar perubahan route/role terbaca instan
            try {
                Artisan::call('cache:clear');
                Artisan::call('route:clear');
            } catch (\Exception $e) {
                // Abaikan jika gagal
            }

            // DIREVISI: Ubah status user menjadi aktif (1) saat berhasil login
            $loggedInUser = Auth::user();
            if ($loggedInUser) {
                $loggedInUser->is_active = 1;
                $loggedInUser->save();

                // TAMBAHAN: Pengecekan role yang aman dari spasi tersembunyi & perbedaan kapital
                $role = strtolower(trim($loggedInUser->role));

                if ($role === 'murid') {
                    return redirect()->route('student.dashboard');
                } elseif ($role === 'guru') {
                    return redirect()->route('dashboard');
                }
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    // Logout
    public function destroy(Request $request) {
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