<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * Tentukan atribut yang dapat diisi secara massal.
     * 
     * REVISI: Menambahkan 'keterangan' ke dalam fillable untuk 
     * menyimpan catatan presensi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'date',
        'status', // Contoh: 'H', 'S', 'I', 'A'
        'notes',
        'keterangan', // Ditambahkan
    ];

    /**
     * Menambahkan casting untuk memastikan 'date' dianggap sebagai tipe data tanggal (Carbon).
     * Ini membantu saat melakukan query filter tanggal atau range.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Mendapatkan siswa yang memiliki absensi ini.
     * Relasi ini sangat penting untuk fitur filter berdasarkan kelas di Controller.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}