<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeItem;
use App\Models\GradeCategory;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $subjects = Subject::all(); 
        $classes = Student::forTeacher()->distinct()->pluck('kelas')->sort();

        $selectedSubject = $request->query('subject') ?? $user->subject_id ?? ($subjects->first()->id ?? null);
        $selectedClass = $request->query('class', $classes->first() ?? null);

        $students = collect();
        $grades = collect();
        $categories = collect();

        if ($selectedSubject && $selectedClass) {
            $students = Student::forTeacher()->where('kelas', $selectedClass)->get();
            $categories = GradeCategory::where('subject_id', $selectedSubject)->get();
            
            $grades = Grade::with(['items.category']) 
                ->where('subject_id', $selectedSubject)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        $totalSiswa = $students->count();
        $rataRataKelas = $grades->count() > 0 ? $grades->avg('rata_rata') : 0;
        $siswaTuntas = $grades->where('status', 'Tuntas')->count();
        $siswaRemedial = $grades->where('status', 'Remedial')->count();
        $progress = $totalSiswa > 0 ? ($grades->count() / $totalSiswa) * 100 : 0;
        
        return view('grades.index', compact(
            'students', 'subjects', 'classes', 'selectedSubject', 
            'selectedClass', 'grades', 'rataRataKelas', 
            'siswaTuntas', 'siswaRemedial', 'progress', 'user', 'categories'
        ));
    }

    public function addColumn(Request $request)
    {
        $request->validate([
            'column_name' => 'required|string', 
            'subject_id' => 'required', 
            'class' => 'required'
        ]);

        $category = GradeCategory::updateOrCreate(
            ['name' => $request->column_name, 'subject_id' => $request->subject_id],
            ['type' => 'custom']
        );

        $students = Student::where('kelas', $request->class)->get();

        foreach ($students as $student) {
            $grade = Grade::firstOrCreate(
                ['student_id' => $student->id, 'subject_id' => $request->subject_id]
            );

            GradeItem::updateOrCreate(
                ['grade_id' => $grade->id, 'column_name' => $request->column_name],
                ['grade_category_id' => $category->id, 'value' => 0]
            );
        }

        return response()->json(['success' => true, 'message' => 'Kolom berhasil ditambahkan']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
        ]);

        $subjectId = $request->subject_id;

        foreach ($request->grades as $studentId => $data) {
            $isStudentOwned = Student::where('id', $studentId)->where('teacher_id', Auth::id())->exists();
            
            if ($isStudentOwned) {
                $tugas = (float)($data['tugas'] ?? 0);
                $praktik = (float)($data['praktik'] ?? 0);
                $ulangan_harian = (float)($data['ulangan_harian'] ?? 0);
                $uts = (float)($data['uts'] ?? 0);
                $uas = (float)($data['uas'] ?? 0);
                
                $sum = $tugas + $praktik + $ulangan_harian + $uts + $uas;
                $count = 5;

                $grade = Grade::updateOrCreate(
                    ['student_id' => $studentId, 'subject_id' => $subjectId],
                    [
                        'tugas' => $tugas, 'praktik' => $praktik,
                        'ulangan_harian' => $ulangan_harian, 'uts' => $uts, 'uas' => $uas,
                    ]
                );

                if (isset($data['items'])) {
                    foreach ($data['items'] as $categoryId => $value) {
                        $cat = GradeCategory::find($categoryId);
                        $val = (float)($value ?? 0);
                        
                        $sum += $val;
                        $count++;

                        GradeItem::updateOrCreate(
                            [
                                'grade_id' => $grade->id, 
                                'column_name' => $cat ? $cat->name : 'Item'
                            ],
                            [
                                'grade_category_id' => $categoryId,
                                'value' => $val
                            ]
                        );
                    }
                }

                $rataRata = $count > 0 ? ($sum / $count) : 0;
                $status = $rataRata >= 75 ? 'Tuntas' : 'Remedial';
                $grade->update(['rata_rata' => $rataRata, 'status' => $status]);
            }
        }

        return redirect()->back()->with('success', 'Semua nilai berhasil disimpan!');
    }

    public function autoSave(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function recap(Request $request)
    {
        $classes = Student::forTeacher()->distinct()->pluck('kelas')->sort();
        $subjects = auth()->user()->subjects;

        $selectedClass = $request->query('class', $classes->first());
        $selectedSubject = $request->query('subject', $subjects->first()->id ?? null);
        
        $categories = GradeCategory::where('subject_id', $selectedSubject)->get();

        $students = Student::forTeacher()
            ->where('kelas', $selectedClass)
            ->with(['grades' => function($q) use ($selectedSubject) {
                $q->where('subject_id', $selectedSubject)
                ->with('items');
            }])
            ->get();
        
        $distribusi = ['tinggi' => 0, 'sedang' => 0, 'rendah' => 0];
        foreach ($students as $student) {
            $grade = $student->grades->first(); 
            if ($grade) {
                $nilaiRata = $grade->rata_rata;
                if ($nilaiRata >= 85) { $distribusi['tinggi']++; } 
                elseif ($nilaiRata >= 75) { $distribusi['sedang']++; } 
                else { $distribusi['rendah']++; }
            }
        }
        
        return view('grades.recap', compact('students', 'classes', 'selectedClass', 'distribusi', 'subjects', 'selectedSubject', 'categories'));
    }

    /**
     * Mengekspor data rekap nilai ke file CSV.
     */
    public function exportExcel(Request $request)
    {
        $selectedClass = $request->query('class');
        $selectedSubject = $request->query('subject');

        $students = Student::forTeacher()
            ->where('kelas', $selectedClass)
            ->with(['grades' => function($q) use ($selectedSubject) {
                $q->where('subject_id', $selectedSubject)->with('items');
            }])
            ->get();

        $categories = GradeCategory::where('subject_id', $selectedSubject)->get();

        $fileName = "Rekap_Nilai_{$selectedClass}_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function() use ($students, $categories) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            $header = ['NIS', 'Nama Siswa', 'Tugas', 'Praktik', 'UH', 'UTS', 'UAS'];
            foreach ($categories as $cat) {
                $header[] = $cat->name;
            }
            $header[] = 'Nilai Akhir';
            $header[] = 'Status';
            fputcsv($file, $header);

            // Data
            foreach ($students as $student) {
                $grade = $student->grades->first();
                $row = [
                    $student->nis,
                    $student->name,
                    $grade->tugas ?? 0,
                    $grade->praktik ?? 0,
                    $grade->ulangan_harian ?? 0,
                    $grade->uts ?? 0,
                    $grade->uas ?? 0,
                ];

                foreach ($categories as $cat) {
                    $item = $grade ? $grade->items->where('grade_category_id', $cat->id)->first() : null;
                    $row[] = $item ? $item->value : 0;
                }

                $row[] = number_format($grade->rata_rata ?? 0, 2);
                $row[] = $grade->status ?? '-';
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}