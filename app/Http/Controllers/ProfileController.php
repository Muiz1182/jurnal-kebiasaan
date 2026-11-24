<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validasi input
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip'            => ['nullable', 'string', 'max:50'],
            'jabatan'        => ['nullable', 'string', 'max:100'],
            'kelas_mengajar' => ['nullable', 'string', 'max:100'], 
            // REVISI: Validasi array subject_ids untuk mendukung multiple selection
            'subject_ids'    => ['nullable', 'array'], 
            'subject_ids.*'  => ['exists:subjects,id'],
            'foto_profil'    => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'password'       => ['nullable', 'confirmed', 'min:8'],
        ]);

        // 2. Update foto profil jika ada file baru
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profile-photos', 'public');
            $user->foto_profil = $path;
        }

        // 3. Update data profil
        $user->name            = $validated['name'];
        
        if ($request->filled('email')) {
            $user->email = $validated['email'];
        }
        
        // Memastikan field tambahan diperbarui (pastikan kolom ini ada di migrasi tabel users jika ingin tersimpan)
        $user->nip            = $request->input('nip');
        $user->jabatan        = $request->input('jabatan');
        $user->kelas_mengajar = $request->input('kelas_mengajar');
        
        // REVISI: Sinkronisasi relasi Many-to-Many dengan tabel pivot
        if (method_exists($user, 'subjects')) {
            if ($request->has('subject_ids')) {
                $user->subjects()->sync($request->input('subject_ids'));
            } else {
                // Jika tidak ada yang dipilih, hapus semua relasi mata pelajaran
                $user->subjects()->detach();
            }
        }

        // 4. Jika password diisi, update password
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // 5. Simpan perubahan
        $user->save();

        return back()->with('success', 'Profil dan daftar mata pelajaran berhasil diperbarui!');
    }
}