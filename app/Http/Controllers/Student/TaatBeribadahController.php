<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalTaatBeribadah;
use App\Models\StudentIbadahDetail;
use App\Services\GoogleSheetsService; // <-- Menggunakan service yang sudah ada
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaatBeribadahController extends Controller
{
    /**
     * Jadwal Waktu Sholat Default (Wilayah Probolinggo / WIB)
     * Format 24 Jam (HH:mm)
     */
    public static $schedules = [
        'subuh' => [
            'name' => 'Sholat Subuh',
            'time' => '04:15',
            'icon' => '🌅',
        ],
        'dzuhur' => [
            'name' => 'Sholat Dzuhur',
            'time' => '11:45',
            'icon' => '☀️',
        ],
        'ashar' => [
            'name' => 'Sholat Ashar',
            'time' => '15:15',
            'icon' => '🌤️',
        ],
        'maghrib' => [
            'name' => 'Sholat Maghrib',
            'time' => '17:45',
            'icon' => '🌇',
        ],
        'isya' => [
            'name' => 'Sholat Isya',
            'time' => '19:00',
            'icon' => '🌙',
        ],
        'dhuha' => [
            'name' => 'Sholat Dhuha',
            'time' => '06:30',
            'end_time' => '11:00',
            'icon' => '⭐',
        ],
    ];

    /**
     * Menghitung status setiap waktu sholat:
     * - Buka: 5 menit sebelum waktu sholat
     * - Tutup: 1 jam setelah waktu sholat (60 menit)
     */
    public static function getPrayerStatus(Carbon $now, ?StudentIbadahDetail $ibadahDetail = null): array
    {
        $todayStr = $now->toDateString();
        $status = [];

        foreach (self::$schedules as $key => $sched) {
            $isAlreadyChecked = $ibadahDetail && ((int)$ibadahDetail->$key === 1);

            if ($key === 'dhuha') {
                $openTime = Carbon::createFromFormat('Y-m-d H:i', "$todayStr {$sched['time']}", $now->timezone)->subMinutes(5);
                $closeTime = Carbon::createFromFormat('Y-m-d H:i', "$todayStr {$sched['end_time']}", $now->timezone);
            } else {
                $prayerTime = Carbon::createFromFormat('Y-m-d H:i', "$todayStr {$sched['time']}", $now->timezone);
                $openTime = $prayerTime->copy()->subMinutes(5);
                $closeTime = $prayerTime->copy()->addMinutes(60);
            }

            $isOpen = $now->between($openTime, $closeTime);
            $isPast = $now->gt($closeTime);
            $isUpcoming = $now->lt($openTime);

            // Centang hanya aktif jika window sedang dibuka dan belum pernah dicentang sebelumnya
            $canCheck = $isOpen && !$isAlreadyChecked;
            $isDisabled = !$isOpen || $isAlreadyChecked;

            $status[$key] = [
                'name'         => $sched['name'],
                'time'         => $sched['time'],
                'end_time'     => $sched['end_time'] ?? null,
                'icon'         => $sched['icon'],
                'open_at'      => $openTime->format('H:i'),
                'close_at'     => $closeTime->format('H:i'),
                'is_open'      => $isOpen,
                'is_past'      => $isPast,
                'is_upcoming'  => $isUpcoming,
                'is_checked'   => $isAlreadyChecked,
                'can_check'    => $canCheck,
                'is_disabled'  => $isDisabled,
            ];
        }

        return $status;
    }

    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentMonth = $now;

        // Ambil data rekap utama jurnal hari ini
        $journal = JurnalTaatBeribadah::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Ambil data rincian sholat HARI INI dari tabel terpisah
        $ibadahDetail = StudentIbadahDetail::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$ibadahDetail) {
            $ibadahDetail = new StudentIbadahDetail([
                'user_id' => $user->id,
                'date'    => $today,
                'subuh'   => 0,
                'dzuhur'  => 0,
                'ashar'   => 0,
                'maghrib' => 0,
                'isya'    => 0,
                'dhuha'   => 0,
            ]);
        }

        $monthlyJournals = JurnalTaatBeribadah::where('user_id', $user->id)
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->toDateString();
            });

        // --- STATISTIK ---
        $fields = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya', 'dhuha'];
        $averageDaily = 0;
        foreach ($fields as $field) {
            if ($ibadahDetail->$field == 1) {
                $averageDaily++;
            }
        }
        
        $allDetails = StudentIbadahDetail::where('user_id', $user->id)->get();
        $totalDays = $allDetails->count();

        $onTimePercentage = 0;
        if ($totalDays > 0) {
            $totalCheckedForPercentage = $allDetails->sum(function($detail) {
                return ($detail->subuh ?? 0) + 
                       ($detail->dzuhur ?? 0) + 
                       ($detail->ashar ?? 0) + 
                       ($detail->maghrib ?? 0) + 
                       ($detail->isya ?? 0) + 
                       ($detail->dhuha ?? 0);
            });

            $maxPossibleScore = $totalDays * 6;
            $onTimePercentage = min(round(($totalCheckedForPercentage / $maxPossibleScore) * 100), 100);
        }

        $allJournals = JurnalTaatBeribadah::where('user_id', $user->id)->get();
        $totalJournalDays = $allJournals->count();
        $mostConsistent = $totalJournalDays > 0 ? 'Ibadah Harian' : '-';

        $streakDays = 0;
        $checkDate = Carbon::today();
        
        if (!JurnalTaatBeribadah::where('user_id', $user->id)->where('date', $checkDate->toDateString())->exists()) {
            $checkDate->subDay();
        }

        while (true) {
            $exists = JurnalTaatBeribadah::where('user_id', $user->id)
                ->where('date', $checkDate->toDateString())
                ->exists();

            if ($exists) {
                $streakDays++;
                $checkDate->subDay();
            } else {
                break;
            }
        }

        // Hitung status pembukaan dan penguncian waktu sholat
        $prayerStatus = self::getPrayerStatus($now, $ibadahDetail);
        
        $lockStatus = [];
        $notYetOpenStatus = [];
        foreach ($prayerStatus as $key => $item) {
            $lockStatus[$key] = $item['is_past'];
            $notYetOpenStatus[$key] = $item['is_upcoming'];
        }

        $schedules = self::$schedules;

        return view('student.taat-beribadah', compact(
            'journal',
            'ibadahDetail',
            'monthlyJournals',
            'streakDays',
            'averageDaily',
            'onTimePercentage',
            'mostConsistent',
            'lockStatus',
            'notYetOpenStatus',
            'prayerStatus',
            'schedules'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $now = Carbon::now();
        $today = $now->toDateString();

        $existingDetail = StudentIbadahDetail::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Validasi waktu sholat di sisi server
        $prayerStatus = self::getPrayerStatus($now, $existingDetail);

        $savedFields = [];
        $newChecksCount = 0;

        foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya', 'dhuha'] as $field) {
            $isAlreadyChecked = $existingDetail && ((int)$existingDetail->$field === 1);
            $isOpen = $prayerStatus[$field]['is_open'] ?? false;

            if ($isAlreadyChecked) {
                // Pertahankan jika sudah dicentang dan tersimpan sebelumnya
                $savedFields[$field] = 1;
            } elseif ($isOpen && $request->has($field)) {
                // Hanya izinkan mencentang jika rentang waktu sholat saat ini sedang terbuka
                $savedFields[$field] = 1;
                $newChecksCount++;
            } else {
                $savedFields[$field] = 0;
            }
        }

        $isIbadahFilled = (
            $savedFields['subuh'] || 
            $savedFields['dzuhur'] || 
            $savedFields['ashar'] || 
            $savedFields['maghrib'] || 
            $savedFields['isya'] || 
            $savedFields['dhuha']
        ) ? 1 : 0;

        // 1. Simpan rekap utama ke tabel student_journals
        JurnalTaatBeribadah::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => $today,
            ],
            [
                'ibadah'       => $isIbadahFilled,
                'total_points' => $isIbadahFilled ? 10 : 0,
            ]
        );

        // 2. Simpan rincian detail sholat ke tabel terpisah (student_ibadah_details)
        StudentIbadahDetail::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => $today,
            ],
            [
                'subuh'   => $savedFields['subuh'],
                'dzuhur'  => $savedFields['dzuhur'],
                'ashar'   => $savedFields['ashar'],
                'maghrib' => $savedFields['maghrib'],
                'isya'    => $savedFields['isya'],
                'dhuha'   => $savedFields['dhuha'],
            ]
        );

        // 3. Sinkronisasi langsung ke Google Sheets (update row hari ini atau buat row baru)
        try {
            $sheetService = new GoogleSheetsService();
            
            $rowValues = [
                $user->name ?? 'Siswa',
                $today,
                $savedFields['subuh'] ? 'Ya' : 'Tidak',
                $savedFields['dzuhur'] ? 'Ya' : 'Tidak',
                $savedFields['ashar'] ? 'Ya' : 'Tidak',
                $savedFields['maghrib'] ? 'Ya' : 'Tidak',
                $savedFields['isya'] ? 'Ya' : 'Tidak',
                $savedFields['dhuha'] ? 'Ya' : 'Tidak',
                $now->toDateTimeString(),
            ];

            $sheetService->updateOrAppendRow('Taat Beribadah', $rowValues);
        } catch (\Exception $e) {
            Log::error('Gagal sinkronisasi Google Sheet: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Jurnal ibadah berhasil disimpan dan langsung ter-rekap ke Google Sheets!');
    }
}