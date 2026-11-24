<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login dengan mendeteksi sesi aktif atau identitas tersimpan di perangkat.
     */
    public function showLogin(Request $request)
    {
        // Jika sudah login melalui sesi aktif atau remember_token, langsung arahkan ke dashboard yang sesuai
        if (Auth::check()) {
            $user = Auth::user();
            $role = strtolower(trim($user->role));
            if ($role === 'murid') {
                return redirect()->route('student.dashboard');
            } elseif ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        $rememberedIdentifier = $request->cookie('remembered_login_identifier');
        $rememberChecked = $request->cookie('remembered_login_remember') === '1' || !empty($rememberedIdentifier);

        // Ambil data ringkas akun jika ada identitas yang pernah disimpan di perangkat ini
        $rememberedUser = null;
        if (!empty($rememberedIdentifier)) {
            $rememberedUser = User::where('email', $rememberedIdentifier)
                ->orWhere('username', $rememberedIdentifier)
                ->orWhere('nisn', $rememberedIdentifier)
                ->first(['name', 'username', 'email', 'kelas', 'role', 'foto_profil']);
        }

        return view('auth.login', compact('rememberedIdentifier', 'rememberChecked', 'rememberedUser'));
    }

    /**
     * Proses autentikasi login dengan dukungan "Ingat Sesi di Perangkat Ini".
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'], // Bisa menerima email atau username
            'password' => ['required'],
        ]);

        // Cari user berdasarkan email atau username atau nisn
        $user = User::where('email', $request->email)
                    ->orWhere('username', $request->email)
                    ->orWhere('nisn', $request->email)
                    ->first();

        if (!$user) {
            Log::info('Login Gagal: User tidak ditemukan: ' . $request->email);
            return back()->withErrors(['email' => 'Email atau username tidak terdaftar.'])->withInput();
        }

        // Cek kecocokan password
        if (!Hash::check($request->password, $user->password)) {
            Log::info('Login Gagal: Password tidak cocok untuk: ' . $request->email);
            return back()->withErrors(['email' => 'Password yang Anda masukkan salah.'])->withInput();
        }

        // Mengambil status 'remember' (boolean)
        $remember = $request->boolean('remember');

        // Login dengan remember token bawaan Laravel
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Tandai status user aktif
        $user->is_active = 1;
        $user->save();

        // Kelola cookie perangkat untuk mengingat identitas akun (30 hari)
        if ($remember) {
            Cookie::queue('remembered_login_identifier', $request->email, 60 * 24 * 30);
            Cookie::queue('remembered_login_remember', '1', 60 * 24 * 30);
        } else {
            Cookie::queue(Cookie::forget('remembered_login_identifier'));
            Cookie::queue(Cookie::forget('remembered_login_remember'));
        }

        // Redirect sesuai role
        $role = strtolower(trim($user->role));
        if ($role === 'murid') {
            return redirect()->route('student.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Hapus cookie identitas yang tersimpan di perangkat ini (Ganti Akun).
     */
    public function forgetDevice()
    {
        Cookie::queue(Cookie::forget('remembered_login_identifier'));
        Cookie::queue(Cookie::forget('remembered_login_remember'));
        return redirect()->route('login')->with('info', 'Identitas akun yang tersimpan di perangkat ini berhasil dihapus.');
    }

    /**
     * Proses keluar dari sesi portal.
     */
    public function logout(Request $request)
    {
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
