<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentJournal extends Model
{
    use HasFactory;

    protected $table = 'student_journals';

    protected $fillable = [
        'user_id',
        'date',
        'bangun_pagi',
        'ibadah',
        'olahraga',
        'makan_sehat',
        'belajar',
        'sosialisasi',
        'tidur_cepat',
        'total_points',
    ];

    /**
     * Relasi ke model User (Setiap jurnal milik satu siswa/user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat atau mengupdate status kebiasaan hari ini secara otomatis.
     * 
     * @param int $userId
     * @param string $column (contoh: 'olahraga', 'ibadah', dll)
     * @param mixed $value
     * @return self
     */
    public static function logToday($userId, $column, $value = 1)
    {
        $today = Carbon::today()->toDateString();

        // Cari atau buat data jurnal untuk user pada hari ini
        $journal = self::firstOrCreate(
            [
                'user_id' => $userId,
                'date'    => $today,
            ],
            [
                'bangun_pagi'  => 0,
                'ibadah'       => 0,
                'olahraga'     => 0,
                'makan_sehat'  => 0,
                'belajar'      => 0,
                'sosialisasi'  => 0,
                'tidur_cepat'  => 0,
                'total_points' => 0,
            ]
        );

        // Update kolom yang diminta (misal: 'olahraga')
        $journal->{$column} = $value;
        $journal->save();

        return $journal;
    }
}