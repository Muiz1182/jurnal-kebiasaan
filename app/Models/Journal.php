<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'schedule_id',
        'subject',        // Tambahkan ini untuk menyimpan subjek
        'lesson_hours',   // Tambahkan ini untuk menyimpan jam ke
        'class_name',     // Tambahkan ini untuk menyimpan nama kelas
        'content',        // Materi Pembelajaran
        'notes',          // Catatan Kejadian / Kendala
        'status',         // approved, pending, revision
        'date',
        // 'start_time',  <-- Dihapus sesuai permintaan
        // 'end_time',    <-- Dihapus sesuai permintaan
        // Kolom Presensi
        'total_students',
        'present_count',
        'sick_count',
        'permit_count',   // Izin
        'absent_count',   // Alpha
    ];

    /**
     * Relasi ke model User (Guru)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relasi ke model Schedule
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}