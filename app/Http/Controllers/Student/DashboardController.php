<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentJournal;
use App\Models\SocialActivity;
use App\Models\JurnalTaatBeribadah;
use App\Models\MakanSehatJournal;
use App\Models\GemarBelajarJournal;
use App\Models\TidurCepatJournal;
use App\Services\GoogleSheetsService; // TAMBAHAN: Mengimport GoogleSheetsService
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        
        $journal = null;
        $completedCount = 0;
        $totalPoints = 0;
        $progressPercentage = 0;
        $streakDays = 0; // Inisialisasi variabel streak hari beruntun
        $journals = collect(); // Inisialisasi variabel riwayat jurnal untuk tabel rekapan

        // Pengecekan aman jika tabel student_journals sudah ada atau belum
        if (Schema::hasTable('student_journals')) {
            // Mengambil jurnal terbaru hari ini untuk ditampilkan di dashboard
            $journal = StudentJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->latest()
                ->first();

            // Ambil seluruh riwayat jurnal siswa untuk ditampilkan pada tabel rekapan di bawah dashboard
            $journals = StudentJournal::where('user_id', $user->id)->orderBy('date', 'desc')->get();

            // Hitung berapa kebiasaan yang sudah selesai hari ini (0-7) jika jurnalnya ada
            if ($journal) {
                $completedCount = collect([
                    $journal->bangun_pagi,
                    $journal->ibadah,
                    $journal->olahraga,
                    $journal->makan_sehat,
                    $journal->belajar,
                    $journal->sosialisasi,
                    $journal->tidur_cepat
                ])->filter()->count();
            }

            // Hitung total poin keseluruhan dari semua jurnal siswa (Contoh: 1 kebiasaan bernilai 10 poin)
            $allJournals = StudentJournal::where('user_id', $user->id)->get();
            $totalChecked = 0;
            foreach ($allJournals as $j) {
                $totalChecked += collect([
                    $j->bangun_pagi, $j->ibadah, $j->olahraga, 
                    $j->makan_sehat, $j->belajar, $j->sosialisasi, $j->tidur_cepat
                ])->filter()->count();
            }
            
            $totalPoints = $totalChecked * 10; // 1 centang = 10 poin
            
            // Asumsi target 1000 poin untuk level maksimal, batasi max 100%
            $progressPercentage = min(100, round(($totalPoints / 1000) * 100));

            // Logika menghitung hari beruntun (streak) secara dinamis dari database
            $checkDate = Carbon::today();
            while (true) {
                // REVISI: Pastikan streak dihitung hanya jika ibadah/jurnal benar-benar terisi (tidak kosong melompong)
                $exists = StudentJournal::where('user_id', $user->id)
                    ->whereDate('date', $checkDate)
                    ->where(function($query) {
                        $query->where('ibadah', 1)
                            ->orWhere('bangun_pagi', 1)
                            ->orWhere('olahraga', 1)
                            ->orWhere('makan_sehat', 1)
                            ->orWhere('belajar', 1)
                            ->orWhere('sosialisasi', 1)
                            ->orWhere('tidur_cepat', 1);
                    })
                    ->exists();

                if ($exists) {
                    $streakDays++;
                    $checkDate->subDay(); // Mundur ke hari sebelumnya
                } else {
                    // Jika hari ini belum isi, berikan toleransi mengecek kemarin agar streak tidak langsung 0 jika baru terlewat hari ini
                    if ($streakDays == 0 && $checkDate->isToday()) {
                        $checkDate->subDay();
                        continue;
                    }
                    break;
                }
            }
        }

        return view('student.dashboard', compact('user', 'journal', 'journals', 'completedCount', 'totalPoints', 'progressPercentage', 'streakDays'));
    }

    /**
     * TAMBAHAN: Menampilkan halaman khusus menu Bangun Pagi
     */
    public function bangunPagi()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $journal = null;

        if (Schema::hasTable('student_journals')) {
            $journal = StudentJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->latest()
                ->first();
        }

        return view('student.bangun-pagi', compact('user', 'journal'));
    }

    /**
     * TAMBAHAN: Menampilkan halaman khusus menu Taat Beribadah (Dilengkapi data kalender & streak dinamis)
     * Catatan: Method index Taat Beribadah dipindahkan ke controller tersendiri (TaatBeribadahController) 
     * tetapi method ini tetap dipertahankan untuk kompatibilitas dashboard jika dibutuhkan.
     */
    public function taatBeribadah()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $now = Carbon::now();
        $journal = null;
        $monthlyJournals = collect();
        $streakDays = 0;
        
        $averageDaily = 0;
        $onTimePercentage = 0;
        $mostConsistent = '-';

        $prayerSchedule = [
            'subuh'        => ['start' => '03:50', 'end' => '05:20'],
            'dzuhur'       => ['start' => '11:04', 'end' => '12:34'],
            'ashar'        => ['start' => '14:25', 'end' => '15:55'],
            'maghrib'      => ['start' => '16:56', 'end' => '18:26'],
            'isya'         => ['start' => '18:09', 'end' => '19:39'],
            'baca_alquran' => ['start' => '03:50', 'end' => '22:00'],
        ];

        $lockStatus = [];
        $notYetOpenStatus = [];

        foreach ($prayerSchedule as $key => $times) {
            $startTime = Carbon::createFromFormat('Y-m-d H:i', $today->toDateString() . ' ' . $times['start']);
            $endTime = Carbon::createFromFormat('Y-m-d H:i', $today->toDateString() . ' ' . $times['end']);
            
            $notYetOpenStatus[$key] = $now->lessThan($startTime);
            $lockStatus[$key] = $now->greaterThan($endTime);
        }

        if (Schema::hasTable('student_journals')) {
            $journal = StudentJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->latest()
                ->first();

            $monthlyJournals = StudentJournal::where('user_id', $user->id)
                ->whereMonth('date', $today->month)
                ->whereYear('date', $today->year)
                ->where(function($query) {
                    $query->where('ibadah', 1)
                          ->orWhere('bangun_pagi', 1)
                          ->orWhere('olahraga', 1)
                          ->orWhere('makan_sehat', 1)
                          ->orWhere('belajar', 1)
                          ->orWhere('sosialisasi', 1)
                          ->orWhere('tidur_cepat', 1);
                })
                ->get()
                ->keyBy(function($item) {
                    return Carbon::parse($item->date)->toDateString();
                });

            $allStudentJournals = StudentJournal::where('user_id', $user->id)->get();
            if ($allStudentJournals->count() > 0) {
                $validJournals = $allStudentJournals->filter(function($j) {
                    return $j->ibadah || $j->subuh || $j->dzuhur || $j->ashar || $j->maghrib || $j->isya || $j->baca_alquran;
                });

                $totalDays = $validJournals->count();

                if ($totalDays > 0) {
                    $totalCompletedIbadah = 0;
                    $subuhCount = 0; $dzuhurCount = 0; $asharCount = 0; $maghribCount = 0; $isyaCount = 0; $quranCount = 0;

                    foreach ($validJournals as $j) {
                        $ibadahPerRow = collect([
                            $j->subuh ?? false, $j->dzuhur ?? false, $j->ashar ?? false, 
                            $j->maghrib ?? false, $j->isya ?? false, $j->baca_alquran ?? false
                        ])->filter()->count();

                        $totalCompletedIbadah += $ibadahPerRow;

                        if ($j->subuh) $subuhCount++;
                        if ($j->dzuhur) $dzuhurCount++;
                        if ($j->ashar) $asharCount++;
                        if ($j->maghrib) $maghribCount++;
                        if ($j->isya) $isyaCount++;
                        if ($j->baca_alquran) $quranCount++;
                    }

                    $averageDaily = round($totalCompletedIbadah / $totalDays, 1);
                    $maxPossibleIbadah = $totalDays * 6;
                    $onTimePercentage = $maxPossibleIbadah > 0 ? round(($totalCompletedIbadah / $maxPossibleIbadah) * 100) : 0;

                    $ibadahCounts = [
                        'Subuh' => $subuhCount, 'Dzuhur' => $dzuhurCount, 'Ashar' => $asharCount,
                        'Maghrib' => $maghribCount, 'Isya' => $isyaCount, 'Baca Al-Quran' => $quranCount,
                    ];
                    
                    $maxConsist = max($ibadahCounts);
                    if ($maxConsist > 0) {
                        $mostConsistent = array_search($maxConsist, $ibadahCounts);
                    }
                }
            }

            $checkDate = $today->copy();
            while (true) {
                $dateStr = $checkDate->toDateString();
                $exists = StudentJournal::where('user_id', $user->id)
                            ->whereDate('date', $dateStr)
                            ->where(function($query) {
                                $query->where('ibadah', 1)
                                    ->orWhere('bangun_pagi', 1)
                                    ->orWhere('olahraga', 1)
                                    ->orWhere('makan_sehat', 1)
                                    ->orWhere('belajar', 1)
                                    ->orWhere('sosialisasi', 1)
                                    ->orWhere('tidur_cepat', 1);
                            })
                            ->exists();
                
                if ($exists) {
                    $streakDays++;
                    $checkDate->subDay();
                } else {
                    if ($checkDate->isToday()) {
                        $checkDate->subDay();
                        continue;
                    }
                    break;
                }
            }
        }

        return view('student.taat-beribadah', compact(
            'user', 'journal', 'monthlyJournals', 'streakDays', 'averageDaily', 
            'onTimePercentage', 'mostConsistent', 'lockStatus', 'notYetOpenStatus'
        ));
    }

    /**
     * TAMBAHAN: Menampilkan halaman Jurnal Olahraga
     */
    public function olahraga()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $journal = null;

        if (Schema::hasTable('student_journals')) {
            $journal = StudentJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->latest()
                ->first();
        }

        $journals = StudentJournal::where('user_id', $user->id)->latest()->get();
        
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

        $weeklyOlahraga = StudentJournal::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where('olahraga', true)
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->toDateString();
            });

        $weeklyStreakData = [];
        $currentDay = $startOfWeek->copy();
        
        for ($i = 0; $i < 7; $i++) {
            $dateStr = $currentDay->toDateString();
            $weeklyStreakData[$currentDay->format('l')] = isset($weeklyOlahraga[$dateStr]);
            $currentDay->addDay();
        }

        return view('student.olahraga', compact('user', 'journal', 'journals', 'weeklyStreakData'));
    }

    /**
     * TAMBAHAN: Menyimpan data Jurnal Olahraga
     */
    public function storeOlahraga(Request $request, GoogleSheetsService $sheetsService)
    {
        $request->validate([
            'jenis_olahraga'         => 'required|array|min:1',
            'jenis_olahraga.*'       => 'string',
            'jenis_olahraga_lainnya' => 'nullable|string|max:255',
            'durasi'                 => 'required|integer|min:1',
            'intensitas'             => 'required|string',
            'perasaan'               => 'required|string',
            'catatan'                => 'nullable|string',
        ]);

        $user = auth()->user();
        $today = Carbon::today();
        $clickTime = now();

        $pilihanOlahraga = $request->jenis_olahraga;

        if (($key = array_search('Lainnya', $pilihanOlahraga)) !== false) {
            unset($pilihanOlahraga[$key]);
        }

        if ($request->filled('jenis_olahraga_lainnya')) {
            $pilihanOlahraga[] = $request->jenis_olahraga_lainnya;
        }

        $jenisOlahragaString = implode(', ', $pilihanOlahraga);

        StudentJournal::create([
            'user_id'        => $user->id,
            'date'           => $today,
            'olahraga'       => true,
            'jenis_olahraga' => $jenisOlahragaString,
            'durasi'         => $request->durasi,
            'intensitas'     => $request->intensitas,
            'perasaan'       => $request->perasaan,
            'catatan'        => $request->catatan,
        ]);

        try {
            $rowData = [
                $user->id,
                $user->name ?? '-',
                $today->toDateString(),
                $jenisOlahragaString,
                $request->durasi . ' Menit',
                $request->intensitas,
                $request->perasaan,
                $request->catatan ?? '-',
                $clickTime->toDateTimeString()
            ];
            
            if (method_exists($sheetsService, 'updateOrAppendRow')) {
                $sheetsService->updateOrAppendRow('Olahraga', $rowData, 0, $user->id, $today->toDateString());
            } else {
                $sheetsService->appendRow('Olahraga', $rowData);
            }
        } catch (\Exception $e) {
            \Log::error("Gagal sync ke Google Sheets (Olahraga): " . $e->getMessage());
        }

        return redirect()->route('student.olahraga')->with('success', 'Jurnal olahraga berhasil disimpan!');
    }

    /**
     * TAMBAHAN: Menampilkan halaman Jurnal Makan Sehat & Bergizi
     */
    public function makanSehat()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $journal = null;

        if (Schema::hasTable('makan_sehat_journals')) {
            $journal = MakanSehatJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->first();
        }

        // Kalkulasi persentase nutrisi dinamis berdasarkan data input
        $porsiSayur = (int) ($journal->porsi_sayur ?? 0);
        $porsiBuah  = (int) ($journal->porsi_buah ?? 0);
        $airPutih   = (int) ($journal->air_putih ?? 0);

        // Serat & Vitamin: target minimal 5 porsi sayur & buah per hari
        $seratVitaminPct = min(round((($porsiSayur + $porsiBuah) / 5) * 100), 100);
        // Hidrasi: target minimal 8 gelas air putih per hari
        $hidrasiPct = min(round(($airPutih / 8) * 100), 100);
        // Protein: indikasi asupan makanan utama yang tercatat
        $hasMeals = (!empty($journal->sarapan) || !empty($journal->makan_siang) || !empty($journal->makan_malam));
        $proteinPct = $hasMeals ? 85 : 0;

        // Evaluasi status gizi makanan (apakah makanan sehat atau belum seimbang)
        $evaluasiGizi = $this->evaluateNutrition(
            $porsiSayur,
            $porsiBuah,
            $airPutih,
            $journal->sarapan ?? '',
            $journal->makan_siang ?? '',
            $journal->makan_malam ?? ''
        );

        $journals = MakanSehatJournal::where('user_id', $user->id)->orderBy('date', 'desc')->get();

        return view('student.makan-sehat', compact(
            'user', 
            'journal', 
            'journals',
            'seratVitaminPct',
            'hidrasiPct',
            'proteinPct',
            'evaluasiGizi'
        ));
    }

    /**
     * Helper evaluasi gizi seimbang
     */
    protected function evaluateNutrition($porsiSayur, $porsiBuah, $airPutih, $sarapan = '', $makanSiang = '', $makanMalam = '')
    {
        $hasBreakfast = !empty(trim($sarapan ?? ''));
        $hasLunch = !empty(trim($makanSiang ?? ''));
        $hasDinner = !empty(trim($makanMalam ?? ''));
        $mainMealsCount = ($hasBreakfast ? 1 : 0) + ($hasLunch ? 1 : 0) + ($hasDinner ? 1 : 0);
        $totalSayurBuah = (int)$porsiSayur + (int)$porsiBuah;
        $air = (int)$airPutih;

        if ($mainMealsCount === 0 && $totalSayurBuah === 0 && $air === 0) {
            return [
                'status'     => 'Belum Ada Catatan',
                'badge'      => 'bg-gray-100 text-gray-700 border-gray-200',
                'color'      => 'gray',
                'icon'       => '⚪',
                'is_healthy' => null,
                'summary'    => 'Belum Ada Data Asupan Hari Ini',
                'advice'     => 'Yuk catat menu sarapan, makan siang, atau makan malammu beserta porsi sayur, buah, dan air putih.'
            ];
        }

        // Kriteria Sangat Sehat & Bergizi Seimbang:
        // Sayur + buah >= 5 porsi, Air putih >= 8 gelas, dan minimal 2x makan utama
        if ($totalSayurBuah >= 5 && $air >= 8 && $mainMealsCount >= 2) {
            return [
                'status'     => 'Sangat Sehat & Bergizi Seimbang',
                'badge'      => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'color'      => 'emerald',
                'icon'       => '🟢',
                'is_healthy' => true,
                'summary'    => 'Makanan Sehat & Sangat Bergizi Seimbang',
                'advice'     => 'Luar biasa! Asupan serat, vitamin, mineral, lauk bergizi, dan hidrasi harianmu telah memenuhi standar gizi seimbang.'
            ];
        }

        // Kriteria Cukup Sehat (Perlu Peningkatan):
        // Sayur + buah 2 - 4 porsi, Air putih 5 - 7 gelas, dan minimal 1x makan utama
        if ($totalSayurBuah >= 2 && $air >= 5 && $mainMealsCount >= 1) {
            return [
                'status'     => 'Cukup Sehat (Perlu Peningkatan)',
                'badge'      => 'bg-amber-50 text-amber-800 border-amber-200',
                'color'      => 'amber',
                'icon'       => '🟡',
                'is_healthy' => true,
                'summary'    => 'Makanan Cukup Sehat, Perlu Penambahan Porsi',
                'advice'     => 'Pola makan sudah cukup baik! Tingkatkan porsi sayur & buah hingga minimal 5 porsi dan minum air putih hingga 8 gelas agar optimal.'
            ];
        }

        // Kriteria Kurang Sehat / Belum Seimbang:
        return [
            'status'     => 'Kurang Sehat / Belum Seimbang',
            'badge'      => 'bg-rose-50 text-rose-800 border-rose-200',
            'color'      => 'rose',
            'icon'       => '🔴',
            'is_healthy' => false,
            'summary'    => 'Makanan Belum Memenuhi Pedoman Gizi Seimbang',
            'advice'     => 'Porsi serat, vitamin, atau hidrasi masih kurang. Lengkapi menu makanan utama dengan sayuran, buah-buahan, dan cukupi air putih.'
        ];
    }

    /**
     * TAMBAHAN: Menyimpan data Jurnal Makan Sehat & Bergizi
     */
    public function storeMakanSehat(Request $request, GoogleSheetsService $sheetsService)
    {
        $request->validate([
            'sarapan'     => 'nullable|string|max:1000',
            'makan_siang' => 'nullable|string|max:1000',
            'makan_malam' => 'nullable|string|max:1000',
            'porsi_sayur' => 'nullable|integer|min:0|max:50',
            'porsi_buah'  => 'nullable|integer|min:0|max:50',
            'air_putih'   => 'nullable|integer|min:0|max:50',
            'cemilan'     => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $sarapan    = $request->sarapan;
        $makanSiang = $request->makan_siang;
        $makanMalam = $request->makan_malam;
        $porsiSayur = (int) $request->input('porsi_sayur', 0);
        $porsiBuah  = (int) $request->input('porsi_buah', 0);
        $airPutih   = (int) $request->input('air_putih', 0);
        $cemilan    = $request->cemilan;

        // Evaluasi status gizi
        $evaluasiGizi = $this->evaluateNutrition($porsiSayur, $porsiBuah, $airPutih, $sarapan, $makanSiang, $makanMalam);

        // 1. Simpan/Update detail ke tabel makan_sehat_journals
        MakanSehatJournal::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => $today,
            ],
            [
                'sarapan'     => $sarapan,
                'makan_siang' => $makanSiang,
                'makan_malam' => $makanMalam,
                'porsi_sayur' => $porsiSayur,
                'porsi_buah'  => $porsiBuah,
                'air_putih'   => $airPutih,
                'cemilan'     => $cemilan,
                'status_gizi' => $evaluasiGizi['status'],
            ]
        );

        // 2. Tandai habit makan_sehat = true di student_journals utama
        $hasFilledAny = (!empty($sarapan) || !empty($makanSiang) || !empty($makanMalam) || $porsiSayur > 0 || $porsiBuah > 0 || $airPutih > 0 || !empty($cemilan));

        if (Schema::hasTable('student_journals')) {
            StudentJournal::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date'    => $today,
                ],
                [
                    'makan_sehat'  => $hasFilledAny ? 1 : 0,
                    'total_points' => $hasFilledAny ? 10 : 0,
                ]
            );
        }

        // 3. Rekap langsung ke Google Spreadsheet tab 'Makan Sehat'
        try {
            $rowData = [
                $user->name ?? 'Siswa',
                $today,
                !empty($sarapan) ? $sarapan : '-',
                !empty($makanSiang) ? $makanSiang : '-',
                !empty($makanMalam) ? $makanMalam : '-',
                $porsiSayur . ' Porsi',
                $porsiBuah . ' Porsi',
                $airPutih . ' Gelas',
                !empty($cemilan) ? $cemilan : '-',
                $evaluasiGizi['status'],
                $now->toDateTimeString(),
            ];

            // updateOrAppendRow: kolom 0 = Nama Siswa, kolom 1 = Tanggal
            $sheetsService->updateOrAppendRow('Makan Sehat', $rowData, 0, 1);
        } catch (\Exception $e) {
            \Log::error("Gagal sync ke Google Sheets (Makan Sehat): " . $e->getMessage());
        }

        return redirect()->route('student.makan-sehat')->with('success', 'Jurnal makan sehat berhasil disimpan dan langsung ter-rekap ke Google Spreadsheet!');
    }

    /**
     * TAMBAHAN: Menampilkan halaman Jurnal Gemar Belajar
     */
    public function gemarBelajar()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $todayStr = $today->toDateString();
        $journal = null;
        
        $totalSesiBelajar = 0;
        $totalMenitBelajar = 0;
        $streakBelajar = 0;
        $totalPoinBelajar = 0;
        $levelBuku = 'Pemula';
        $nextLevelTarget = 5;
        $levelProgress = 0;
        $weeklyChartData = [];

        if (Schema::hasTable('gemar_belajar_journals')) {
            $journal = GemarBelajarJournal::where('user_id', $user->id)
                ->where('date', $todayStr)
                ->first();

            $allJournals = GemarBelajarJournal::where('user_id', $user->id)->get();
            $totalSesiBelajar = $allJournals->count();
            $totalMenitBelajar = $allJournals->sum('durasi_belajar');
            $totalPoinBelajar = $totalSesiBelajar * 35;

            if ($totalSesiBelajar >= 20) {
                $levelBuku = 'Master Literasi';
                $nextLevelTarget = 20;
                $levelProgress = 100;
            } elseif ($totalSesiBelajar >= 10) {
                $levelBuku = 'Kutu Buku';
                $nextLevelTarget = 20;
                $levelProgress = min(round((($totalSesiBelajar - 10) / 10) * 100), 100);
            } elseif ($totalSesiBelajar >= 5) {
                $levelBuku = 'Rajin Membaca';
                $nextLevelTarget = 10;
                $levelProgress = min(round((($totalSesiBelajar - 5) / 5) * 100), 100);
            } else {
                $levelBuku = 'Pemula';
                $nextLevelTarget = 5;
                $levelProgress = min(round(($totalSesiBelajar / 5) * 100), 100);
            }

            // Hitung streak berturut-turut
            $checkDate = $today->copy();
            while (true) {
                $exists = GemarBelajarJournal::where('user_id', $user->id)
                    ->whereDate('date', $checkDate)
                    ->exists();
                
                if ($exists) {
                    $streakBelajar++;
                    $checkDate->subDay();
                } else {
                    if ($checkDate->isToday()) {
                        $checkDate->subDay();
                        continue;
                    }
                    break;
                }
            }

            // Aktivitas mingguan (Senin - Minggu)
            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

            $weeklyJournals = GemarBelajarJournal::where('user_id', $user->id)
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->get()
                ->keyBy(function($item) {
                    return Carbon::parse($item->date)->format('N');
                });

            for ($i = 1; $i <= 7; $i++) {
                $durasi = isset($weeklyJournals[$i]) ? (int) $weeklyJournals[$i]->durasi_belajar : 0;
                
                $heightClass = 'h-2';
                if ($durasi >= 90) {
                    $heightClass = 'h-28';
                } elseif ($durasi >= 60) {
                    $heightClass = 'h-24';
                } elseif ($durasi >= 45) {
                    $heightClass = 'h-18';
                } elseif ($durasi >= 30) {
                    $heightClass = 'h-14';
                } elseif ($durasi > 0) {
                    $heightClass = 'h-8';
                }
                
                $weeklyChartData[$i] = [
                    'active' => isset($weeklyJournals[$i]),
                    'height' => $heightClass,
                    'durasi' => $durasi,
                ];
            }
        }

        $journals = GemarBelajarJournal::where('user_id', $user->id)->orderBy('date', 'desc')->get();

        return view('student.gemar-belajar', compact(
            'user', 'journal', 'journals', 'totalSesiBelajar', 'totalMenitBelajar',
            'streakBelajar', 'totalPoinBelajar', 'levelBuku', 'nextLevelTarget', 'levelProgress', 'weeklyChartData'
        ));
    }

    /**
     * TAMBAHAN: Menyimpan data Jurnal Gemar Belajar
     */
    public function storeGemarBelajar(Request $request, GoogleSheetsService $sheetsService)
    {
        $request->validate([
            'materi_belajar'        => 'nullable|string|max:1000',
            'durasi_belajar'        => 'nullable|integer|min:1|max:600',
            'judul_buku'            => 'nullable|string|max:255',
            'halaman_terakhir'      => 'nullable|string|max:50',
            'kategori_keterampilan' => 'nullable|string|max:100',
            'catatan'               => 'nullable|string|max:2000',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $clickTime = Carbon::now();

        $durasi = (int) $request->input('durasi_belajar', 30);
        $materi = $request->materi_belajar;
        $buku   = $request->judul_buku;
        $hal    = $request->halaman_terakhir;
        $skill  = $request->kategori_keterampilan;
        $catatan = $request->catatan;

        $statusTarget = ($durasi >= 30) ? 'Target Terpenuhi (>=30 Menit)' : 'Perlu Peningkatan (<30 Menit)';

        // 1. Simpan/Update detail ke tabel gemar_belajar_journals
        GemarBelajarJournal::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => $today,
            ],
            [
                'materi_belajar'        => $materi,
                'durasi_belajar'        => $durasi,
                'judul_buku'            => $buku,
                'halaman_terakhir'      => $hal,
                'kategori_keterampilan' => $skill,
                'catatan'               => $catatan,
                'status_target'         => $statusTarget,
            ]
        );

        // 2. Tandai habit belajar = true di student_journals utama
        if (Schema::hasTable('student_journals')) {
            StudentJournal::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date'    => $today,
                ],
                [
                    'belajar'      => 1,
                    'total_points' => 35,
                ]
            );
        }

        // 3. Rekap langsung ke Google Spreadsheet tab 'Gemar Belajar'
        try {
            $rowData = [
                $user->name ?? 'Siswa',
                $today,
                !empty($materi) ? $materi : '-',
                $durasi . ' Menit',
                !empty($buku) ? $buku : '-',
                !empty($hal) ? ('Hal. ' . $hal) : '-',
                !empty($skill) ? ucfirst($skill) : '-',
                !empty($catatan) ? $catatan : '-',
                $statusTarget,
                $clickTime->toDateTimeString(),
            ];

            // updateOrAppendRow: kolom 0 = Nama Siswa, kolom 1 = Tanggal
            $sheetsService->updateOrAppendRow('Gemar Belajar', $rowData, 0, 1);
        } catch (\Exception $e) {
            \Log::error("Gagal sync ke Google Sheets (Gemar Belajar): " . $e->getMessage());
        }

        return redirect()->route('student.gemar-belajar')->with('success', 'Jurnal gemar belajar berhasil disimpan dan langsung ter-rekap ke Google Spreadsheet!');
    }

    /**
     * TAMBAHAN: Mengosongkan data Jurnal Gemar Belajar hari ini
     */
    public function destroyGemarBelajar()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        if (Schema::hasTable('gemar_belajar_journals')) {
            GemarBelajarJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->delete();
        }

        if (Schema::hasTable('student_journals')) {
            $journal = StudentJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->latest()
                ->first();

            if ($journal) {
                $journal->update([
                    'belajar' => 0,
                ]);
            }
        }

        return redirect()->route('student.gemar-belajar')->with('success', 'Data jurnal gemar belajar hari ini berhasil dikosongkan!');
    }

    /**
     * Helper menghitung durasi tidur
     */
    protected function calculateSleepDuration($jamTidur, $targetBangun)
    {
        try {
            $tidur = Carbon::createFromFormat('H:i', $jamTidur);
            $bangun = Carbon::createFromFormat('H:i', $targetBangun);

            if ($bangun->lessThanOrEqualTo($tidur)) {
                $bangun->addDay();
            }

            $diffMinutes = $tidur->diffInMinutes($bangun);
            $hours = floor($diffMinutes / 60);
            $minutes = $diffMinutes % 60;

            if ($minutes > 0) {
                return "{$hours} Jam {$minutes} Menit";
            }
            return "{$hours} Jam";
        } catch (\Exception $e) {
            return "-";
        }
    }

    /**
     * TAMBAHAN: Menampilkan halaman Jurnal Tidur Cepat
     */
    public function tidurCepat()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $journal = null;
        $routines = [];
        
        $summary = [
            'rerata_jam_tidur' => '-',
            'total_durasi'     => '-',
            'konsistensi'      => '0%',
            'total_sesi'       => 0,
        ];

        if (Schema::hasTable('tidur_cepat_journals')) {
            $journal = TidurCepatJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            if ($journal && isset($journal->rutinitas_malam)) {
                $routines = is_array($journal->rutinitas_malam) 
                    ? $journal->rutinitas_malam 
                    : json_decode($journal->rutinitas_malam, true) ?? [];
            }

            // Hitung statistik pekan ini (Senin - Minggu)
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            $weeklyLogs = TidurCepatJournal::where('user_id', $user->id)
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->get();

            if ($weeklyLogs->isNotEmpty()) {
                $totalMinutes = 0;
                $tepatWaktuCount = 0;
                $jamTidurMinutes = 0;

                foreach ($weeklyLogs as $log) {
                    if (!$log->is_begadang) {
                        $tepatWaktuCount++;
                    }
                    try {
                        $t = Carbon::createFromFormat('H:i', $log->jam_tidur);
                        $b = Carbon::createFromFormat('H:i', $log->target_bangun);
                        if ($b->lessThanOrEqualTo($t)) {
                            $b->addDay();
                        }
                        $totalMinutes += $t->diffInMinutes($b);

                        // Rerata waktu tidur
                        $jamTidurMinutes += ($t->hour * 60) + $t->minute;
                    } catch (\Exception $e) {
                    }
                }

                $logCount = $weeklyLogs->count();
                $avgDurationMinutes = round($totalMinutes / $logCount);
                $avgDurHours = floor($avgDurationMinutes / 60);
                $avgDurMins = $avgDurationMinutes % 60;

                $avgBedtimeMinutes = round($jamTidurMinutes / $logCount);
                $avgBedHours = str_pad(floor($avgBedtimeMinutes / 60), 2, '0', STR_PAD_LEFT);
                $avgBedMins = str_pad($avgBedtimeMinutes % 60, 2, '0', STR_PAD_LEFT);

                $summary['rerata_jam_tidur'] = "{$avgBedHours}:{$avgBedMins}";
                $summary['total_durasi'] = $avgDurMins > 0 ? "{$avgDurHours}j {$avgDurMins}m" : "{$avgDurHours} Jam";
                $summary['konsistensi'] = round(($tepatWaktuCount / $logCount) * 100) . '%';
                $summary['total_sesi'] = $logCount;
            }
        }

        $recentJournals = TidurCepatJournal::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return view('student.tidur-cepat', compact('user', 'journal', 'routines', 'summary', 'recentJournals'));
    }

    /**
     * TAMBAHAN: Menyimpan data Jurnal Tidur Cepat
     */
    public function storeTidurCepat(Request $request, GoogleSheetsService $sheetsService)
    {
        $request->validate([
            'jam_tidur'          => 'required|string',
            'target_bangun'      => 'required|string',
            'kualitas_istirahat' => 'required|string|in:Buruk,Cukup,Nyenyak',
            'refleksi_malam'     => 'nullable|string|max:1000',
            'rutinitas'          => 'nullable|array',
            'rutinitas.*'        => 'string',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $clickTime = Carbon::now();

        $jamTidurInput = $request->jam_tidur;
        $targetBangun = $request->target_bangun;
        $isBegadang = false;

        if (!empty($jamTidurInput)) {
            // Standar batas tidur tepat waktu untuk siswa: maksimal pukul 22:00
            if ($jamTidurInput >= '22:00' || $jamTidurInput <= '03:00') {
                $isBegadang = true;
            }
        }

        $statusTidur = $isBegadang ? 'Begadang' : 'Tepat Waktu';
        $durasiTidur = $this->calculateSleepDuration($jamTidurInput, $targetBangun);
        $rutinitasArray = $request->input('rutinitas', []);

        // Mapping label rutinitas yang ramah dibaca
        $rutinitasLabels = [
            'ganti_baju'    => 'Ganti Baju Tidur',
            'sikat_gigi'    => 'Sikat Gigi & Cuci Muka',
            'berdoa'        => 'Berdoa Sebelum Tidur',
            'matikan_lampu' => 'Matikan Lampu Kamar',
            'jauhkan_hp'    => 'Jauhkan Ponsel/Gadget',
        ];
        $readableRoutines = [];
        foreach ($rutinitasArray as $r) {
            $readableRoutines[] = $rutinitasLabels[$r] ?? ucfirst(str_replace('_', ' ', $r));
        }
        $rutinitasString = !empty($readableRoutines) ? implode(', ', $readableRoutines) : '-';

        // 1. Simpan/Update detail ke tabel tidur_cepat_journals
        TidurCepatJournal::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => $today,
            ],
            [
                'jam_tidur'          => $jamTidurInput,
                'target_bangun'      => $targetBangun,
                'durasi_tidur'       => $durasiTidur,
                'kualitas_istirahat' => $request->kualitas_istirahat,
                'status_tidur'       => $statusTidur,
                'rutinitas_malam'    => $rutinitasArray,
                'refleksi_malam'     => $request->refleksi_malam,
                'is_begadang'        => $isBegadang,
            ]
        );

        // 2. Tandai habit tidur_cepat di student_journals utama
        if (Schema::hasTable('student_journals')) {
            StudentJournal::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date'    => $today,
                ],
                [
                    'tidur_cepat'  => 1,
                    'total_points' => 35,
                ]
            );
        }

        // 3. Rekap langsung ke Google Spreadsheet tab 'Tidur Cepat'
        try {
            $rowData = [
                $user->name ?? 'Siswa',
                $today,
                $jamTidurInput,
                $targetBangun,
                $durasiTidur,
                $request->kualitas_istirahat,
                $statusTidur,
                $rutinitasString,
                !empty($request->refleksi_malam) ? $request->refleksi_malam : '-',
                $clickTime->toDateTimeString(),
            ];

            // updateOrAppendRow: kolom 0 = Nama Siswa, kolom 1 = Tanggal
            $sheetsService->updateOrAppendRow('Tidur Cepat', $rowData, 0, 1);
        } catch (\Exception $e) {
            \Log::error("Gagal sync ke Google Sheets (Tidur Cepat): " . $e->getMessage());
        }

        $pesan = $isBegadang 
            ? 'Jurnal tidur berhasil disimpan. Catatan: Kamu tidur lewat dari jam 22:00 (Begadang), usahakan istirahat lebih awal esok hari!' 
            : 'Jurnal tidur berhasil disimpan dan ter-rekap ke Google Spreadsheet. Kerja bagus, istirahat tepat waktu!';

        return redirect()->route('student.tidur-cepat')->with('success', $pesan);
    }

    /**
     * TAMBAHAN: Mengosongkan data Jurnal Tidur Cepat hari ini
     */
    public function destroyTidurCepat()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        if (Schema::hasTable('tidur_cepat_journals')) {
            TidurCepatJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->delete();
        }

        if (Schema::hasTable('student_journals')) {
            $journal = StudentJournal::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            if ($journal) {
                $journal->update([
                    'tidur_cepat' => 0,
                ]);
            }
        }

        return redirect()->route('student.tidur-cepat')->with('success', 'Data jurnal tidur cepat hari ini berhasil dikosongkan!');
    }

    /**
     * TAMBAHAN: Menampilkan halaman Aktif Masyarakat (Sosialisasi)
     */
    public function aktifMasyarakat()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        
        $activities = collect();
        $totalSocialPoints = 0;
        $contributorLevel = 'Relawan Pemula';
        $todayActivity = null;

        if (Schema::hasTable('social_activities')) {
            $activities = SocialActivity::where('user_id', $user->id)->latest()->get();
            $todayActivity = SocialActivity::where('user_id', $user->id)->where('date', $today)->latest()->first();

            $totalCount = $activities->count();
            $totalSocialPoints = $totalCount * 30;

            if ($totalCount >= 11) {
                $contributorLevel = 'Duta Sosial Sekolah';
            } elseif ($totalCount >= 6) {
                $contributorLevel = 'Penggerak Kebaikan';
            } elseif ($totalCount >= 3) {
                $contributorLevel = 'Sahabat Warga';
            } else {
                $contributorLevel = 'Relawan Pemula';
            }
        }

        // Dampak kolektif sekolah bulan ini
        $now = Carbon::now();
        $collectiveMonthlyCount = 0;
        if (Schema::hasTable('social_activities')) {
            $collectiveMonthlyCount = SocialActivity::whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->count();
        }
        $collectiveTarget = 100;
        $collectiveProgress = min(round(($collectiveMonthlyCount / $collectiveTarget) * 100), 100);

        // Inspirator pekan ini (Top siswa yang paling aktif melakukan aksi sosial minggu ini)
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);
        $inspirators = collect();

        if (Schema::hasTable('social_activities')) {
            $inspirators = SocialActivity::with('user')
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->selectRaw('user_id, count(*) as total_actions')
                ->groupBy('user_id')
                ->orderByDesc('total_actions')
                ->limit(3)
                ->get()
                ->map(function ($item) {
                    $name = $item->user->name ?? 'Siswa';
                    $words = explode(' ', trim($name));
                    $initials = '';
                    foreach (array_slice($words, 0, 2) as $w) {
                        $initials .= strtoupper(substr($w, 0, 1));
                    }
                    return (object) [
                        'name'          => $name,
                        'initials'      => !empty($initials) ? $initials : 'SW',
                        'total_actions' => $item->total_actions,
                    ];
                });
        }

        return view('student.aktif-masyarakat', compact(
            'user', 'activities', 'todayActivity', 'totalSocialPoints', 
            'contributorLevel', 'collectiveMonthlyCount', 'collectiveTarget', 
            'collectiveProgress', 'inspirators'
        ));
    }

    /**
     * TAMBAHAN: Menyimpan data Aktif Masyarakat
     */
    public function storeAktifMasyarakat(Request $request, GoogleSheetsService $sheetsService)
    {
        $request->validate([
            'jenis_kegiatan'         => 'required|string|max:255',
            'jenis_kegiatan_lainnya' => 'nullable|string|max:255',
            'deskripsi'              => 'required|string|max:1000',
            'lokasi_kegiatan'        => 'nullable|string|max:255',
            'foto_kegiatan'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $clickTime = Carbon::now();

        $jenisKegiatan = $request->jenis_kegiatan;
        if ($jenisKegiatan === 'Lainnya' && filled($request->jenis_kegiatan_lainnya)) {
            $jenisKegiatan = $request->jenis_kegiatan_lainnya;
        }

        $photoPath = null;
        if ($request->hasFile('foto_kegiatan')) {
            $photoPath = $request->file('foto_kegiatan')->store('social_activities', 'public');
        }

        // 1. Simpan ke tabel social_activities
        SocialActivity::create([
            'user_id'         => $user->id,
            'date'            => $today,
            'jenis_kegiatan'  => $jenisKegiatan,
            'deskripsi'       => $request->deskripsi,
            'lokasi_kegiatan' => $request->lokasi_kegiatan,
            'foto_kegiatan'   => $photoPath,
            'status'          => 'approved',
            'points'          => 30,
        ]);

        // 2. Tandai checklist sosialisasi di student_journals utama
        if (Schema::hasTable('student_journals')) {
            StudentJournal::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date'    => $today,
                ],
                [
                    'sosialisasi'  => 1,
                    'total_points' => 30,
                ]
            );
        }

        // 3. Rekap langsung ke Google Spreadsheet tab 'Aktif Masyarakat'
        try {
            $photoInfo = !empty($photoPath) ? asset('storage/' . $photoPath) : 'Tanpa Foto';
            $rowData = [
                $user->name ?? 'Siswa',
                $today,
                $jenisKegiatan,
                $request->deskripsi,
                !empty($request->lokasi_kegiatan) ? $request->lokasi_kegiatan : '-',
                $photoInfo,
                'Divalidasi',
                $clickTime->toDateTimeString(),
            ];

            // updateOrAppendRow: kolom 0 = Nama Siswa, kolom 1 = Tanggal
            $sheetsService->updateOrAppendRow('Aktif Masyarakat', $rowData, 0, 1);
        } catch (\Exception $e) {
            \Log::error("Gagal sync ke Google Sheets (Aktif Masyarakat): " . $e->getMessage());
        }

        return redirect()->route('student.aktif-masyarakat')->with('success', 'Catatan aksi sosial berhasil disimpan dan langsung ter-rekap ke Google Spreadsheet!');
    }

    /**
     * TAMBAHAN: Menghapus data aksi sosial tertentu
     */
    public function destroyAktifMasyarakat($id)
    {
        $user = auth()->user();
        $activity = SocialActivity::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $activityDate = $activity->date;
        $activity->delete();

        // Cek apakah masih ada aktivitas di tanggal tersebut
        $remainingToday = SocialActivity::where('user_id', $user->id)->where('date', $activityDate)->count();
        if ($remainingToday === 0 && Schema::hasTable('student_journals')) {
            $journal = StudentJournal::where('user_id', $user->id)->where('date', $activityDate)->first();
            if ($journal) {
                $journal->update(['sosialisasi' => 0]);
            }
        }

        return redirect()->route('student.aktif-masyarakat')->with('success', 'Catatan kegiatan sosial berhasil dihapus.');
    }

    /**
     * Menyimpan/memperbarui status checklist jurnal harian siswa (Bangun Pagi)
     */
    public function updateBangunPagi(Request $request, GoogleSheetsService $sheetsService)
    {
        $response = $this->processUpdate($request, 'bangun_pagi', 'Form bangun pagi berhasil diperbarui!', route('student.bangun-pagi'), null);

        if ($sheetsService) {
            try {
                $user = auth()->user();
                $today = Carbon::today();
                $clickTime = now();

                $rowData = [
                    $user->id,
                    $user->name ?? '-',
                    $today->toDateString(),
                    $request->input('waktu_bangun', '-'),
                    $request->input('status_waktu', '-'),
                    $request->has('merapikan_tempat_tidur') ? 'Ya' : 'Tidak',
                    $request->has('minum_air_putih') ? 'Ya' : 'Tidak',
                    $request->has('mandi_pagi') ? 'Ya' : 'Tidak',
                    $request->has('persiapan_sekolah') ? 'Ya' : 'Tidak',
                    $request->input('mood', '-'),
                    $request->input('refleksi', '-'),
                    $clickTime->toDateTimeString()
                ];
                
                if (method_exists($sheetsService, 'updateOrAppendRow')) {
                    $sheetsService->updateOrAppendRow('Bangun Pagi', $rowData, 0, $user->id, $today->toDateString());
                } else {
                    $sheetsService->appendRow('Bangun Pagi', $rowData);
                }
            } catch (\Exception $e) {
                \Log::error("Gagal sync ke Google Sheets (Bangun Pagi): " . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Menyimpan/memperbarui status checklist jurnal harian siswa (Taat Beribadah)
     * Menggunakan TaatBeribadahController yang terpisah, namun method ini di-cover 
     * agar mengarah ke TaatBeribadahController jika rute mengarah ke sini.
     */
    public function updateTaatBeribadah(Request $request, GoogleSheetsService $sheetsService)
    {
        $taatController = new TaatBeribadahController();
        return $taatController->update($request, $sheetsService);
    }

    /**
     * Menyimpan/memperbarui status checklist jurnal harian siswa secara umum (Dashboard)
     */
    public function update(Request $request, GoogleSheetsService $sheetsService)
    {
        $response = $this->processUpdate($request, null, 'Jurnal harian berhasil diperbarui!', route('student.taat-beribadah'), $sheetsService);

        if ($sheetsService) {
            try {
                $user = auth()->user();
                $today = Carbon::today();
                $clickTime = now();

                $journal = StudentJournal::where('user_id', $user->id)->where('date', $today)->latest()->first();

                $rowData = [
                    $user->id,
                    $user->name ?? '-',
                    $today->toDateString(),
                    optional($journal)->bangun_pagi ? 'Ya' : 'Tidak',
                    optional($journal)->ibadah ? 'Ya' : 'Tidak',
                    optional($journal)->olahraga ? 'Ya' : 'Tidak',
                    optional($journal)->makan_sehat ? 'Ya' : 'Tidak',
                    optional($journal)->belajar ? 'Ya' : 'Tidak',
                    optional($journal)->sosialisasi ? 'Ya' : 'Tidak',
                    optional($journal)->tidur_cepat ? 'Ya' : 'Tidak',
                    $clickTime->toDateTimeString()
                ];
                
                if (method_exists($sheetsService, 'updateOrAppendRow')) {
                    $sheetsService->updateOrAppendRow('Rekap Jurnal', $rowData, 0, $user->id, $today->toDateString());
                } else {
                    $sheetsService->appendRow('Rekap Jurnal', $rowData);
                }
            } catch (\Exception $e) {
                \Log::error("Gagal sync ke Google Sheets (Rekap Jurnal Dashboard): " . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Private helper method untuk memproses penyimpanan data jurnal
     */
    private function processUpdate(Request $request, $primaryField = null, $successMessage = 'Jurnal harian berhasil diperbarui!', $redirectRoute = null, ?GoogleSheetsService $sheetsService = null)
    {
        $user = auth()->user();
        $today = Carbon::today();
        $now = Carbon::now();
        $clickTime = now();

        $existingJournal = StudentJournal::where('user_id', $user->id)->where('date', $today)->latest()->first();

        $prayerSchedule = [
            'subuh'        => ['start' => '03:50', 'end' => '05:20'],
            'dzuhur'       => ['start' => '11:04', 'end' => '12:34'],
            'ashar'        => ['start' => '14:25', 'end' => '15:55'],
            'maghrib'      => ['start' => '16:56', 'end' => '18:26'],
            'isya'         => ['start' => '18:09', 'end' => '19:39'],
            'baca_alquran' => ['start' => '03:50', 'end' => '22:00'],
        ];

        $resolveStatus = function($field) use ($request, $existingJournal) {
            if ($request->has($field)) {
                return true;
            }

            if ($request->has('ibadah') || $request->has('subuh') || $request->has('dzuhur') || $request->has('ashar') || $request->has('maghrib') || $request->has('isya') || $request->has('baca_alquran')) {
                return $request->has($field) ? true : false;
            }

            return $existingJournal ? $existingJournal->$field : false;
        };

        $dataToSave = [
            'user_id'      => $user->id,
            'date'         => $today,
            'bangun_pagi'  => $request->has('bangun_pagi') ? true : ($existingJournal ? $existingJournal->bangun_pagi : false),
            'subuh'        => $resolveStatus('subuh'),
            'dzuhur'       => $resolveStatus('dzuhur'),
            'ashar'        => $resolveStatus('ashar'),
            'maghrib'      => $resolveStatus('maghrib'),
            'isya'         => $resolveStatus('isya'),
            'baca_alquran' => $resolveStatus('baca_alquran'),
            'olahraga'     => $request->has('olahraga') ? true : ($existingJournal ? $existingJournal->olahraga : false),
            'makan_sehat'  => $request->has('makan_sehat') ? true : ($existingJournal ? $existingJournal->makan_sehat : false),
            'belajar'      => $request->has('belajar') ? true : ($existingJournal ? $existingJournal->belajar : false),
            'sosialisasi'  => $request->has('sosialisasi') ? true : ($existingJournal ? $existingJournal->sosialisasi : false),
            'tidur_cepat'  => $request->has('tidur_cepat') ? true : ($existingJournal ? $existingJournal->tidur_cepat : false),
        ];

        $dataToSave['ibadah'] = $dataToSave['subuh'] || $dataToSave['dzuhur'] || $dataToSave['ashar'] || $dataToSave['maghrib'] || $dataToSave['isya'] || $dataToSave['baca_alquran'] || $request->has('ibadah');

        if ($request->has('waktu_bangun') || $request->has('status_waktu')) {
            $dataToSave['waktu_bangun'] = $request->input('waktu_bangun');
            $dataToSave['status_waktu'] = $request->input('status_waktu');
            $dataToSave['mood'] = $request->input('mood');
            $dataToSave['refleksi'] = $request->input('refleksi');
            
            $dataToSave['merapikan_tempat_tidur'] = $request->has('merapikan_tempat_tidur') ? 1 : 0;
            $dataToSave['minum_air_putih'] = $request->has('minum_air_putih') ? 1 : 0;
            $dataToSave['mandi_pagi'] = $request->has('mandi_pagi') ? 1 : 0;
            $dataToSave['persiapan_sekolah'] = $request->has('persiapan_sekolah') ? 1 : 0;
            
            $dataToSave['bangun_pagi'] = true;
        }

        if ($request->has('refleksi') && !$request->has('waktu_bangun')) {
            $dataToSave['refleksi'] = $request->input('refleksi');
        }

        if ($primaryField && $primaryField !== 'ibadah') {
            $dataToSave[$primaryField] = true;
        }

        StudentJournal::create($dataToSave);

        if ($sheetsService && !$request->has('waktu_bangun') && !$request->has('subuh') && !$request->has('dzuhur')) {
            try {
                $sheetTab = 'Rekap Jurnal';

                $rowData = [
                    $user->id,
                    $user->name ?? '-',
                    $today->toDateString(),
                    $dataToSave['bangun_pagi'] ? 'Ya' : 'Tidak',
                    $dataToSave['subuh'] ? 'Ya' : 'Tidak',
                    $dataToSave['dzuhur'] ? 'Ya' : 'Tidak',
                    $dataToSave['ashar'] ? 'Ya' : 'Tidak',
                    $dataToSave['maghrib'] ? 'Ya' : 'Tidak',
                    $dataToSave['isya'] ? 'Ya' : 'Tidak',
                    $dataToSave['baca_alquran'] ? 'Ya' : 'Tidak',
                    $dataToSave['refleksi'] ?? '-',
                    $clickTime->toDateTimeString()
                ];
                
                if (method_exists($sheetsService, 'updateOrAppendRow')) {
                    $sheetsService->updateOrAppendRow($sheetTab, $rowData, 0, $user->id, $today->toDateString());
                } else {
                    $sheetsService->appendRow($sheetTab, $rowData);
                }
            } catch (\Exception $e) {
                \Log::error("Gagal sync ke Google Sheets (ProcessUpdate): " . $e->getMessage());
            }
        }

        return redirect()->to($redirectRoute ?? route('student.taat-beribadah'))->with('success', $successMessage);
    }
}