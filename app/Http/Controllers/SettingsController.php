<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Models\School;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use Exception;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class SettingsController extends Controller
{
    /**
     * Menampilkan halaman pengaturan (Profil & Keamanan)
     */
    public function index()
    {
        return view('settings.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Memproses update profil user (termasuk subject_ids dan relasi many-to-many)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'nip'            => 'nullable|string|max:50',
            'jabatan'        => 'nullable|string|max:100',
            'kelas_mengajar' => 'nullable|string|max:20',
            'subject_ids'    => 'nullable|array', // Validasi array untuk multi-select mata pelajaran
            'subject_ids.*'  => 'exists:subjects,id', // Memastikan ID valid di tabel subjects
            'foto_profil'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $user = Auth::user();
            $user->name            = $request->name;
            $user->nip             = $request->nip;
            $user->jabatan         = $request->jabatan;
            $user->kelas_mengajar  = $request->kelas_mengajar;

            // Logika upload foto
            if ($request->hasFile('foto_profil')) {
                $path = $request->file('foto_profil')->store('profiles', 'public');
                $user->foto_url = '/storage/' . $path;
            }

            $user->save();

            // REVISI: Sinkronisasi relasi mata pelajaran (Many-to-Many) jika method subjects tersedia di Model User
            if (method_exists($user, 'subjects')) {
                if ($request->has('subject_ids')) {
                    $user->subjects()->sync($request->input('subject_ids'));
                } else {
                    $user->subjects()->detach();
                }
            }

            return redirect()->route('settings.index')->with('success', 'Profil berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()]);
        }
    }

    /**
     * Memproses update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], 
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('settings.index')->with('success', 'Password berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui password.']);
        }
    }

    /**
     * Menampilkan halaman Informasi Sekolah
     */
    public function schoolInfo()
    {
        $school = School::first(); 

        return view('settings.school', [
            'user'   => Auth::user(),
            'school' => $school
        ]);
    }

    /**
     * Memproses update informasi sekolah
     */
    public function updateSchool(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'address'    => 'nullable|string',
            'headmaster' => 'nullable|string|max:255',
        ]);

        try {
            $school = School::firstOrNew(['id' => 1]);
            
            $school->name = $request->name;
            $school->address = $request->address;
            $school->headmaster = $request->headmaster;
            $school->save();

            return back()->with('success', 'Informasi sekolah berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan informasi sekolah.']);
        }
    }

    /**
     * Menampilkan halaman Pengaturan Notifikasi
     */
    public function notifications()
    {
        return view('settings.notifications', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Memproses update preferensi notifikasi
     */
    public function updateNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            
            $user->update([
                'email_notifications'   => $request->has('email_notifications'),
                'browser_notifications' => $request->has('browser_notifications'),
            ]);

            return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui pengaturan notifikasi.']);
        }
    }

    /**
     * Memproses import data murid dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new StudentsImport(Auth::id()), $request->file('file'));
            
            return back()->with('success', 'Data murid berhasil diimpor ke akun Anda!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengimpor data: ' . $e->getMessage()]);
        }
    }

    /**
     * Reset semua data (Nilai, Absensi, Murid) milik guru yang login
     */
    public function resetAllData()
    {
        try {
            $teacherId = Auth::id();

            Grade::whereHas('student', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })->delete();

            Attendance::whereHas('student', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })->delete();

            Student::forTeacher()->delete();

            if (Student::count() === 0) {
                DB::statement('ALTER TABLE students AUTO_INCREMENT = 1');
            }

            return back()->with('success', 'Semua data nilai, absensi, dan murid Anda berhasil dibersihkan.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mereset data: ' . $e->getMessage()]);
        }
    }
}