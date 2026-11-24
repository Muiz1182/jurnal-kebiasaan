<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Menampilkan daftar siswa dan form presensi.
     */
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $selectedClass = $request->query('kelas');
        $availableClasses = Student::forTeacher()->distinct()->pluck('kelas')->sort();

        $studentsQuery = Student::forTeacher();
        if ($selectedClass) {
            $studentsQuery->where('kelas', $selectedClass);
        }
        $students = $studentsQuery->orderBy('name', 'asc')->get();

        $attendances = Attendance::where('date', $date)
            ->whereHas('student', function($q) {
                $q->where('teacher_id', Auth::id());
            })
            ->get()->keyBy('student_id');

        $stats = Attendance::where('date', $date)
            ->whereHas('student', function($q) use ($selectedClass) {
                $q->where('teacher_id', Auth::id());
                if ($selectedClass) {
                    $q->where('kelas', $selectedClass);
                }
            })
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = array_merge(['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0], $stats);

        return view('attendance.index', compact(
            'students', 
            'attendances', 
            'date', 
            'stats', 
            'selectedClass', 
            'availableClasses'
        ));
    }

    /**
     * Fitur Baru: Menandai semua siswa dalam satu kelas sebagai Hadir (H).
     */
    public function markAllPresent(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'kelas' => 'required'
        ]);

        $students = Student::where('teacher_id', Auth::id())
                           ->where('kelas', $request->kelas)
                           ->get();

        foreach ($students as $student) {
            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'date' => $request->date],
                ['status' => 'H', 'notes' => null]
            );
        }

        return back()->with('success', 'Semua siswa berhasil ditandai hadir!');
    }

    /**
     * Menampilkan halaman rekap absensi per guru dengan navigasi bulan atau keseluruhan.
     */
    public function recap(Request $request)
    {
        $monthYear = $request->query('month', date('Y-m'));
        $isAll = ($monthYear === 'all');
        
        $month = null;
        $year = null;

        if (!$isAll) {
            $dateParts = explode('-', $monthYear);
            $year = $dateParts[0];
            $month = $dateParts[1];
        }

        $firstClass = Student::forTeacher()->first()->kelas ?? null;
        $selectedClass = $request->query('kelas', $firstClass);
        $availableClasses = Student::forTeacher()->distinct()->pluck('kelas')->sort();

        $students = Student::forTeacher()
            ->when($selectedClass, function($query) use ($selectedClass) {
                return $query->where('kelas', $selectedClass);
            })
            ->with(['attendances' => function($q) use ($isAll, $month, $year) {
                if (!$isAll) {
                    $q->whereMonth('date', $month)->whereYear('date', $year);
                }
            }])
            ->orderBy('name', 'asc')
            ->get();

        $querySummary = Attendance::whereHas('student', function($q) use ($selectedClass) {
            $q->where('teacher_id', Auth::id());
            if ($selectedClass) {
                $q->where('kelas', $selectedClass);
            }
        });

        if (!$isAll) {
            $querySummary->whereMonth('date', $month)->whereYear('date', $year);
        }

        $summary = $querySummary->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $summary = array_merge(['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0], $summary);

        $dailyTrends = [];
        if (!$isAll) {
            $dailyTrends = Attendance::whereHas('student', function($q) use ($selectedClass) {
                $q->where('teacher_id', Auth::id());
                if ($selectedClass) {
                    $q->where('kelas', $selectedClass);
                }
            })
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'H')
            ->select('date', DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date')
            ->toArray();
        }

        return view('attendance.recap', compact('students', 'availableClasses', 'selectedClass', 'monthYear', 'summary', 'dailyTrends', 'isAll'));
    }

    /**
     * Mengekspor data rekapitulasi ke file CSV (Excel compatible).
     */
    public function exportExcel(Request $request)
    {
        $monthYear = $request->query('month', 'all');
        $selectedClass = $request->query('kelas');
        $isAll = ($monthYear === 'all');
        
        $month = null;
        $year = null;

        if (!$isAll) {
            $dateParts = explode('-', $monthYear);
            $year = $dateParts[0];
            $month = $dateParts[1];
        }

        $students = Student::forTeacher()
            ->when($selectedClass, fn($q) => $q->where('kelas', $selectedClass))
            ->with(['attendances' => function($q) use ($isAll, $month, $year) {
                if (!$isAll) $q->whereMonth('date', $month)->whereYear('date', $year);
            }])
            ->orderBy('name', 'asc')
            ->get();

        $fileName = 'Rekap_Absensi_' . ($selectedClass ?? 'Semua_Kelas') . '_' . $monthYear . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NIS', 'Nama Siswa', 'Hadir', 'Sakit', 'Izin', 'Alpha']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->nis,
                    $student->name,
                    $student->attendances->where('status', 'H')->count(),
                    $student->attendances->where('status', 'S')->count(),
                    $student->attendances->where('status', 'I')->count(),
                    $student->attendances->where('status', 'A')->count(),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Menyimpan atau memperbarui data presensi siswa secara massal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $date = $request->input('date');

        foreach ($request->attendance as $studentId => $data) {
            $student = Student::where('id', $studentId)
                              ->where('teacher_id', Auth::id())
                              ->first();

            if ($student && isset($data['status'])) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId, 
                        'date'      => $date
                    ],
                    [
                        'status' => $data['status'],
                        'notes'  => $data['notes'] ?? null
                    ]
                );
            }
        }

        return back()->with('success', 'Presensi berhasil disimpan!');
    }
}