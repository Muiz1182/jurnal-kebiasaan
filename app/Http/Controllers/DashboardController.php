<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Journal;
use App\Models\Attendance;
use App\Models\Student; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilan utama Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Lindungi jika role murid atau admin mengakses rute /dashboard langsung
        if ($user->role === 'murid') {
            return redirect()->route('student.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 1. Menentukan Sapaan Berdasarkan Jam
        $hour = now()->hour;
        if ($hour < 11) {
            $greeting = "Selamat Pagi";
        } elseif ($hour < 15) {
            $greeting = "Selamat Siang";
        } elseif ($hour < 19) {
            $greeting = "Selamat Sore";
        } else {
            $greeting = "Selamat Malam";
        }

        // 2. Statistik Jurnal (Bulan Ini)
        $totalJournals = Journal::where('teacher_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 3. Statistik Kehadiran
        $totalAttendanceRecords = Attendance::whereHas('student', function($q) use ($user) {
            $q->where('kelas', $user->kelas_mengajar);
        })->count();

        $presentCount = Attendance::whereHas('student', function($q) use ($user) {
            $q->where('kelas', $user->kelas_mengajar);
        })->where('status', 'H')->count();

        $avgAttendance = $totalAttendanceRecords > 0 
            ? round(($presentCount / $totalAttendanceRecords) * 100) 
            : 0;

        // 4. Progress Mengajar Hari Ini
        $totalSessions = Schedule::where('teacher_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->count();
            
        $completedSessions = Journal::where('teacher_id', $user->id)
            ->whereDate('date', Carbon::today()) // Menggunakan kolom date agar konsisten
            ->count();
            
        $progress = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) . '%' : '0%';

        // 5. Jadwal Mengajar
        $todaySchedules = Schedule::where('teacher_id', $user->id)
            ->whereDate('date', now()->toDateString()) 
            ->orderBy('start_time', 'asc') 
            ->get();

        if ($todaySchedules->isEmpty()) {
            $todaySchedules = Schedule::where('teacher_id', $user->id)
                ->orderBy('date', 'asc')
                ->limit(5)
                ->get();
        }

        $totalClasses = Student::where('teacher_id', $user->id)
            ->distinct('kelas')
            ->count();

        // REVISI: Mengambil jurnal hari ini
        $todayJournals = Journal::where('teacher_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->get();

        // 6. Data Notifikasi
        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('dashboard', compact(
            'user', 'greeting', 'totalJournals', 'todaySchedules', 
            'totalClasses', 'avgAttendance', 'progress', 'unreadNotificationsCount',
            'todayJournals'
        ));
    }

    /**
     * Menangani halaman notifikasi
     */
    public function notifications()
    {
        $user = Auth::user();
        
        $user->unreadNotifications->markAsRead();
        
        $notifications = $user->notifications()->paginate(10);
        $unreadNotificationsCount = 0; 
        
        return view('notifications', compact('user', 'notifications', 'unreadNotificationsCount'));
    }

    /**
     * Menangani fungsi pencarian
     */
    public function search(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            return redirect()->route('dashboard');
        }
        
        $results = Journal::where('teacher_id', Auth::id())
            ->where('subject', 'like', "%{$query}%")
            ->latest()
            ->paginate(10);
        
        return view('search_results', compact('query', 'results'));
    }
}