<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OlahragaJournal;
use App\Models\StudentJournal;
use App\Services\GoogleSheetsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OlahragaController extends Controller
{
    protected $sheetsService;

    public function __construct(GoogleSheetsService $sheetsService)
    {
        $this->sheetsService = $sheetsService;
    }

    /**
     * Menampilkan halaman formulir dan rekap jurnal olahraga siswa
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $today = Carbon::today();

        // Ambil data jurnal olahraga hari ini
        $journal = OlahragaJournal::where('user_id', $userId)
            ->where(function ($q) use ($today) {
                $q->whereDate('date', $today)
                  ->orWhere(function ($sub) use ($today) {
                      $sub->whereNull('date')
                          ->whereDate('created_at', $today);
                  });
            })
            ->latest()
            ->first();

        // Hitung data Weekly Streak (Senin - Minggu di minggu berjalan)
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

        $weeklyJournals = OlahragaJournal::where('user_id', $userId)
            ->where(function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('date', [$startOfWeek, $endOfWeek])
                  ->orWhere(function ($sub) use ($startOfWeek, $endOfWeek) {
                      $sub->whereNull('date')
                          ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                  });
            })
            ->get();

        // Statistik olahraga minggu ini
        $totalDaysActive = $weeklyJournals->count();
        $totalDuration = $weeklyJournals->sum('durasi');
        $averageDuration = $totalDaysActive > 0 ? round($totalDuration / $totalDaysActive) : 0;

        // Mapping hari 1 (Senin) s/d 7 (Minggu)
        $mappedWeeklyJournals = $weeklyJournals->keyBy(function ($item) {
            $date = $item->date ? Carbon::parse($item->date) : Carbon::parse($item->created_at);
            return $date->format('N');
        });

        $weeklyChartData = [];
        for ($i = 1; $i <= 7; $i++) {
            $weeklyChartData[$i] = [
                'active' => isset($mappedWeeklyJournals[$i]),
                'data'   => $mappedWeeklyJournals[$i] ?? null,
            ];
        }

        // Status pencapaian target harian (minimal 30 menit)
        $hasReachedTarget = $journal && $journal->durasi >= 30;

        return view('student.olahraga', compact(
            'user',
            'journal',
            'weeklyJournals',
            'totalDaysActive',
            'totalDuration',
            'averageDuration',
            'weeklyChartData',
            'hasReachedTarget'
        ));
    }

    /**
     * Menyimpan atau memperbarui jurnal olahraga hari ini
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_olahraga'   => 'required|array|min:1',
            'jenis_olahraga.*' => 'required|string|max:255',
            'durasi'           => 'required|integer|min:1|max:600',
            'intensitas'       => 'required|string|max:50',
            'perasaan'         => 'required|string|max:50',
            'catatan'          => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $userId = $user->id;
        $today = Carbon::today();
        $todayStr = $today->toDateString();

        // Gabungkan array jenis olahraga (bersihkan duplikat/kosong)
        $cleanSports = array_values(array_unique(array_filter(array_map('trim', $request->jenis_olahraga))));
        $jenisOlahragaString = implode(', ', $cleanSports);

        // 1. Simpan atau perbarui di tabel olahraga_journals
        $journal = OlahragaJournal::where('user_id', $userId)
            ->where(function ($q) use ($today) {
                $q->whereDate('date', $today)
                  ->orWhere(function ($sub) use ($today) {
                      $sub->whereNull('date')
                          ->whereDate('created_at', $today);
                  });
            })
            ->first();

        if ($journal) {
            $journal->update([
                'date'           => $todayStr,
                'jenis_olahraga' => $jenisOlahragaString,
                'durasi'         => $request->durasi,
                'intensitas'     => $request->intensitas,
                'perasaan'       => $request->perasaan,
                'catatan'        => $request->catatan,
            ]);
        } else {
            $journal = OlahragaJournal::create([
                'user_id'        => $userId,
                'date'           => $todayStr,
                'jenis_olahraga' => $jenisOlahragaString,
                'durasi'         => $request->durasi,
                'intensitas'     => $request->intensitas,
                'perasaan'       => $request->perasaan,
                'catatan'        => $request->catatan,
            ]);
        }

        // 2. Tandai habit olahraga = true di student_journals utama
        if (Schema::hasTable('student_journals')) {
            StudentJournal::updateOrCreate(
                [
                    'user_id' => $userId,
                    'date'    => $todayStr,
                ],
                [
                    'olahraga'     => 1,
                    'total_points' => 15,
                ]
            );
        }

        // 3. Rekap langsung ke Google Spreadsheet tab 'Rajin Olahraga'
        try {
            $statusTarget = ($request->durasi >= 30)
                ? 'Target Terpenuhi (>=30 Menit)'
                : 'Perlu Peningkatan (<30 Menit)';

            $rowData = [
                $user->name ?? 'Siswa',
                $todayStr,
                $jenisOlahragaString,
                $request->durasi . ' Menit',
                $request->intensitas,
                $request->perasaan,
                !empty($request->catatan) ? $request->catatan : '-',
                $statusTarget,
                Carbon::now()->format('Y-m-d H:i:s'),
            ];

            // updateOrAppendRow: kolom 0 = Nama Siswa, kolom 1 = Tanggal
            $this->sheetsService->updateOrAppendRow('Rajin Olahraga', $rowData, 0, 1);
        } catch (\Exception $e) {
            Log::error('Gagal sync ke Google Sheets (Rajin Olahraga): ' . $e->getMessage());
        }

        return redirect()->route('student.olahraga')
            ->with('success', 'Jurnal olahraga hari ini berhasil disimpan dan langsung ter-rekap ke Google Spreadsheet!');
    }

    /**
     * Alias method untuk update
     */
    public function update(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Mengosongkan data jurnal olahraga hari ini
     */
    public function destroy()
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $todayStr = $today->toDateString();

        // Hapus detail jurnal olahraga hari ini
        OlahragaJournal::where('user_id', $userId)
            ->where(function ($q) use ($today) {
                $q->whereDate('date', $today)
                  ->orWhere(function ($sub) use ($today) {
                      $sub->whereNull('date')
                          ->whereDate('created_at', $today);
                  });
            })
            ->delete();

        // Nonaktifkan centang habit olahraga di student_journals
        if (Schema::hasTable('student_journals')) {
            $studentJournal = StudentJournal::where('user_id', $userId)
                ->where('date', $todayStr)
                ->first();

            if ($studentJournal) {
                $studentJournal->olahraga = 0;
                $studentJournal->save();
            }
        }

        return redirect()->route('student.olahraga')
            ->with('success', 'Jurnal olahraga hari ini berhasil dikosongkan.');
    }
}
