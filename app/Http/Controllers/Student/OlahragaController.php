<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OlahragaJournal;
use App\Services\GoogleSheetsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OlahragaController extends Controller
{
    protected $sheetsService;

    public function __construct(GoogleSheetsService $sheetsService)
    {
        $this->sheetsService = $sheetsService;
    }

    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today();

        // Ambil data jurnal untuk hari ini (jika siswa sudah mengisi hari ini)
        $journal = OlahragaJournal::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->first();

        // Hitung data Weekly Streak (Senin - Minggu di minggu berjalan)
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

        $weeklyJournals = OlahragaJournal::where('user_id', $userId)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();

        // Total hari aktif olahraga dalam seminggu
        $totalDaysActive = $weeklyJournals->count();

        // Total durasi olahraga dalam seminggu (dalam menit)
        $totalDuration = $weeklyJournals->sum('durasi');

        // Mapping data per hari (1 = Senin sampai 7 = Minggu) untuk indikator chart/lingkaran weekly streak
        $mappedWeeklyJournals = $weeklyJournals->keyBy(function($item) {
            return Carbon::parse($item->created_at)->format('N');
        });

        $weeklyChartData = [];
        for ($i = 1; $i <= 7; $i++) {
            $weeklyChartData[$i] = [
                'active' => isset($mappedWeeklyJournals[$i]),
                'data'   => $mappedWeeklyJournals[$i] ?? null
            ];
        }

        return view('student.olahraga', compact(
            'journal', 
            'weeklyJournals', 
            'totalDaysActive', 
            'totalDuration',
            'weeklyChartData'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_olahraga'   => 'required|array|min:1',
            'jenis_olahraga.*' => 'required|string|max:255',
            'durasi'           => 'required|integer|min:1',
            'intensitas'       => 'required|string',
            'perasaan'         => 'required|string',
            'catatan'          => 'nullable|string',
        ]);

        $userId = Auth::id();
        $today = Carbon::today();

        // Gabungkan array jenis olahraga menjadi string terpisah koma
        $jenisOlahragaString = implode(', ', $request->jenis_olahraga);

        // Cek apakah hari ini sudah pernah isi berdasarkan tanggalnya saja
        $journal = OlahragaJournal::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->first();

        if ($journal) {
            // Jika sudah ada hari ini, update datanya beserta status centang olahraga
            $journal->update([
                'jenis_olahraga' => $jenisOlahragaString,
                'durasi'         => $request->durasi,
                'intensitas'     => $request->intensitas,
                'perasaan'       => $request->perasaan,
                'catatan'        => $request->catatan,
                'olahraga'       => true, // <--- Ditambahkan agar status di dashboard otomatis tercentang
            ]);
        } else {
            // Jika belum ada, buat baru beserta status centang olahraga
            $journal = OlahragaJournal::create([
                'user_id'        => $userId,
                'jenis_olahraga' => $jenisOlahragaString,
                'durasi'         => $request->durasi,
                'intensitas'     => $request->intensitas,
                'perasaan'       => $request->perasaan,
                'catatan'        => $request->catatan,
                'olahraga'       => true, // <--- Ditambahkan agar status di dashboard otomatis tercentang
            ]);
        }

        // 3. PROSES KIRIM KE GOOGLE SPREADSHEET
        try {
            $user = Auth::user();
            $dataRow = [
                $user->name ?? '-',                     // 1. Nama Siswa
                $user->nis ?? '-',                        // 2. NIS (jika ada)
                $jenisOlahragaString,                     // 3. Jenis Olahraga
                $request->durasi . ' Menit',              // 4. Durasi
                $request->intensitas,                     // 5. Intensitas
                $request->perasaan,                       // 6. Perasaan
                $request->catatan ?? '-',                 // 7. Catatan
                Carbon::now()->format('Y-m-d H:i:s'),     // 8. Waktu Input (Ditaruh di Akhir)
            ];

            // Panggil service sheets ke tab "Rajin Olahraga"
            $this->sheetsService->appendRow('Rajin Olahraga', $dataRow); 

        } catch (\Exception $e) {
            // Tangkap error jika koneksi ke spreadsheet gagal agar tidak merusak response utama
            \Log::error('Gagal sync ke Google Sheets: ' . $e->getMessage());
        }

        // Diubah agar langsung kembali ke halaman dashboard atau tetap di halaman olahraga (bisa disesuaikan)
        return redirect()->route('student.dashboard')
            ->with('success', 'Jurnal olahraga hari ini berhasil disimpan dan dicatat di dashboard!');
    }
}