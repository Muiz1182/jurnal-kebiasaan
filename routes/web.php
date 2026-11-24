<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController; 
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\TaatBeribadahController; 
use App\Http\Controllers\Student\OlahragaController; // <-- TAMBAHAN: Import OlahragaController yang baru
use Illuminate\Support\Facades\Route;

// 1. Landing Page
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    if ($user->role === 'murid') {
        return redirect()->route('student.dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('dashboard');
});

// 2. Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

// 3. Route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/teacher/dashboard', [DashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
    Route::get('/search', [DashboardController::class, 'search'])->name('search');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/school', [SettingsController::class, 'schoolInfo'])->name('settings.school');
    Route::post('/settings/school/update', [SettingsController::class, 'updateSchool'])->name('settings.school.update');
    
    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::post('/settings/notifications/update', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    
    Route::patch('/settings/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/settings/password/update', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    Route::post('/settings/import-students', [SettingsController::class, 'import'])->name('students.import');
    Route::delete('/settings/reset-all-data', [SettingsController::class, 'resetAllData'])->name('settings.reset-all');

    // Rute Jurnal
    Route::get('/journal/create', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/journal/store', [JournalController::class, 'store'])->name('journal.store');
    Route::get('/journal/{id}', [JournalController::class, 'show'])->name('journal.show'); // Menambahkan route detail

    // Rute Absensi
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/mark-all', [AttendanceController::class, 'markAllPresent'])->name('attendance.mark-all');
    Route::get('/attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap'); 
    
    // Rute Ekspor Excel Absensi
    Route::get('/attendance/export', [AttendanceController::class, 'exportExcel'])->name('attendance.export');

    // Rute Input Nilai
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    
    // Rute Simpan Massal Nilai
    Route::post('/grades/store', [GradeController::class, 'store'])->name('grades.store');
    
    // Rute untuk Auto-Save AJAX
    Route::post('/grades/autosave', [GradeController::class, 'autoSave'])->name('grades.autosave');
    
    // Rute untuk menambah kolom dinamis
    Route::post('/grades/add-column', [GradeController::class, 'addColumn'])->name('grades.addColumn');
    
    // Rute Rekap Nilai
    Route::get('/grades/recap', [GradeController::class, 'recap'])->name('grades.recap');

    // TAMBAHAN: Rute Ekspor Excel Nilai
    Route::get('/grades/export', [GradeController::class, 'exportExcel'])->name('grades.export');

    // Mencegah error 405 (Method Not Allowed) untuk akses GET yang salah
    Route::get('/grades/store', function () {
        return redirect()->route('grades.index');
    });
    Route::get('/attendance/store', function () {
        return redirect()->route('attendance.index');
    });
    Route::get('/journal/store', function () {
        return redirect()->route('journal.create');
    });

    // 4. Rute Khusus Super Admin (Dashboard & Manajemen Guru)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Tambahan rute dashboard admin
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    });

    // 5. TAMBAHAN: Rute Khusus Dashboard & Jurnal Murid
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        // Rute untuk Halaman & Form Bangun Pagi
        Route::get('/bangun-pagi', [StudentDashboardController::class, 'bangunPagi'])->name('bangun-pagi');
        
        // Rute untuk Proses Simpan/Update Form Bangun Pagi
        Route::post('/bangun-pagi', [StudentDashboardController::class, 'updateBangunPagi'])->name('bangun-pagi.update');
        
        // DIREVISI: Rute Taat Beribadah dipisah ke TaatBeribadahController yang baru
        Route::get('/taat-beribadah', [TaatBeribadahController::class, 'index'])->name('taat-beribadah');
        Route::post('/taat-beribadah', [TaatBeribadahController::class, 'update'])->name('taat-beribadah.store');
        Route::post('/taat-beribadah/update', [TaatBeribadahController::class, 'update'])->name('taat-beribadah.update');

        // DIREVISI: Rute untuk Halaman & Proses Simpan Jurnal Olahraga (Rajin Olahraga) dialihkan ke OlahragaController
        Route::get('/olahraga', [OlahragaController::class, 'index'])->name('olahraga');
        Route::post('/olahraga', [OlahragaController::class, 'store'])->name('olahraga.store');
        Route::post('/olahraga/update', [OlahragaController::class, 'update'])->name('olahraga.update'); // TAMBAHAN: Mengatasi error RouteNotConfigured/RouteNotFound

        // TAMBAHAN: Rute untuk Halaman & Proses Simpan Jurnal Makan Sehat & Bergizi
        Route::get('/makan-sehat', [StudentDashboardController::class, 'makanSehat'])->name('makan-sehat');
        Route::post('/makan-sehat', [StudentDashboardController::class, 'storeMakanSehat'])->name('makan-sehat.store');

        // TAMBAHAN: Rute untuk Halaman & Proses Simpan Jurnal Gemar Belajar
        Route::get('/gemar-belajar', [StudentDashboardController::class, 'gemarBelajar'])->name('gemar-belajar');
        Route::post('/gemar-belajar', [StudentDashboardController::class, 'storeGemarBelajar'])->name('gemar-belajar.store');
        
        // TAMBAHAN: Rute untuk Mengosongkan Jurnal Gemar Belajar
        Route::delete('/gemar-belajar', [StudentDashboardController::class, 'destroyGemarBelajar'])->name('gemar-belajar.destroy');

        // TAMBAHAN: Rute untuk Halaman & Proses Simpan Aktif Masyarakat (Sosialisasi)
        Route::get('/aktif-masyarakat', [StudentDashboardController::class, 'aktifMasyarakat'])->name('aktif-masyarakat');
        Route::post('/aktif-masyarakat', [StudentDashboardController::class, 'storeAktifMasyarakat'])->name('aktif-masyarakat.store');
        Route::delete('/aktif-masyarakat/{id}', [StudentDashboardController::class, 'destroyAktifMasyarakat'])->name('aktif-masyarakat.destroy');

        // TAMBAHAN: Rute untuk Halaman & Proses Simpan Jurnal Tidur Cepat
        Route::get('/tidur-cepat', [StudentDashboardController::class, 'tidurCepat'])->name('tidur-cepat');
        Route::post('/tidur-cepat', [StudentDashboardController::class, 'storeTidurCepat'])->name('tidur-cepat.store');
        Route::delete('/tidur-cepat', [StudentDashboardController::class, 'destroyTidurCepat'])->name('tidur-cepat.destroy');

        // Rute untuk Proses Simpan/Update Jurnal Umum
        Route::post('/journal/update', [StudentDashboardController::class, 'update'])->name('journal.update');
    });
});