<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordRequestController extends Controller
{
    /**
     * Tampilkan halaman formulir pengajuan reset kata sandi atau cek status.
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Ajukan permintaan reset kata sandi baru (menunggu verifikasi admin 1x24 jam).
     */
    public function submitRequest(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'reason' => 'nullable|string|max:255',
        ], [
            'identifier.required' => 'Masukkan Email, Username, atau NISN akun Anda.',
            'reason.max' => 'Alasan maksimal 255 karakter.',
        ]);

        $identifier = trim($request->identifier);

        // Cari user berdasarkan email, username, atau nisn
        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('nisn', $identifier)
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'identifier' => 'Akun dengan Email, Username, atau NISN tersebut tidak terdaftar.',
            ]);
        }

        // Cek apakah ada permohonan aktif yang belum kedaluwarsa (1x24 jam)
        $existing = PasswordResetRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existing) {
            if ($existing->isApproved()) {
                return redirect()->route('password.reset.form', ['token' => $existing->token])
                    ->with('info', 'Permintaan Anda telah disetujui admin. Silakan buat kata sandi baru Anda di bawah ini.');
            }

            return redirect()->route('password.reset.status', ['token' => $existing->token])
                ->with('info', 'Permintaan reset sandi akun ini sudah diajukan sebelumnya dan sedang menunggu verifikasi admin (berlaku 1x24 jam).');
        }

        // Buat token unik yang aman
        $token = Str::random(64);

        $resetRequest = PasswordResetRequest::create([
            'user_id' => $user->id,
            'identifier' => $identifier,
            'token' => $token,
            'status' => 'pending',
            'reason' => $request->reason,
            'expires_at' => now()->addHours(24),
        ]);

        return redirect()->route('password.reset.status', ['token' => $token])
            ->with('success', 'Permintaan reset kata sandi berhasil diajukan! Harap tunggu verifikasi dari Admin (maksimal 1x24 jam).');
    }

    /**
     * Cek status pengajuan berdasarkan email, username, atau nisn.
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ], [
            'identifier.required' => 'Masukkan Email, Username, atau NISN untuk memeriksa status.',
        ]);

        $identifier = trim($request->identifier);

        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('nisn', $identifier)
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'identifier' => 'Akun tidak ditemukan. Pastikan Email, Username, atau NISN sudah tepat.',
            ]);
        }

        $latestRequest = PasswordResetRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$latestRequest) {
            return back()->withInput()->withErrors([
                'identifier' => 'Belum ada riwayat permohonan reset sandi untuk akun ini. Silakan ajukan di formulir atas.',
            ]);
        }

        return redirect()->route('password.reset.status', ['token' => $latestRequest->token]);
    }

    /**
     * Halaman pemantauan status permohonan reset sandi (Pending, Approved, Rejected, Expired).
     */
    public function showStatus($token)
    {
        $resetRequest = PasswordResetRequest::with('user', 'approver')
            ->where('token', $token)
            ->firstOrFail();

        return view('auth.reset-password-status', compact('resetRequest'));
    }

    /**
     * Tampilkan formulir penetapan kata sandi baru jika sudah disetujui admin.
     */
    public function showResetForm($token)
    {
        $resetRequest = PasswordResetRequest::with('user')
            ->where('token', $token)
            ->firstOrFail();

        if ($resetRequest->isCompleted()) {
            return redirect()->route('login')
                ->with('info', 'Kata sandi untuk permohonan ini sudah pernah diperbarui. Silakan login dengan kata sandi baru.');
        }

        if ($resetRequest->isRejected()) {
            return redirect()->route('password.reset.status', ['token' => $token])
                ->withErrors(['status' => 'Permintaan reset sandi ini ditolak oleh Admin.']);
        }

        if ($resetRequest->isExpired()) {
            return redirect()->route('password.reset.status', ['token' => $token])
                ->withErrors(['status' => 'Masa berlaku verifikasi telah kedaluwarsa (lebih dari 1x24 jam). Silakan ajukan permohonan baru.']);
        }

        if (!$resetRequest->isApproved()) {
            return redirect()->route('password.reset.status', ['token' => $token])
                ->with('warning', 'Permintaan Anda masih menunggu verifikasi admin. Silakan pantau halaman ini hingga admin menyetujui.');
        }

        return view('auth.reset-password', compact('resetRequest', 'token'));
    }

    /**
     * Proses update password baru setelah verifikasi admin disetujui.
     */
    public function updatePassword(Request $request, $token)
    {
        $resetRequest = PasswordResetRequest::with('user')
            ->where('token', $token)
            ->firstOrFail();

        if (!$resetRequest->canResetPassword()) {
            return redirect()->route('password.reset.status', ['token' => $token])
                ->withErrors(['status' => 'Permintaan ini tidak dapat diproses lagi karena belum disetujui, sudah kadaluwarsa, atau sudah selesai.']);
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Update password pengguna
        $user = $resetRequest->user;
        $user->password = Hash::make($request->password);
        $user->save();

        // Tandai permohonan sudah selesai digunakan
        $resetRequest->status = 'completed';
        $resetRequest->save();

        return redirect()->route('login')
            ->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }
}
