<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * Tentukan tabel jika nama tabel bukan bentuk jamak dari nama model
     * (Opsional: hapus baris ini jika nama tabel Anda 'schedules')
     */
    // protected $table = 'schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'subject',
        'class_name',
        'start_time',
        'end_time',
        'date',
    ];

    /**
     * Get the teacher that owns the schedule.
     * Relasi ke model User (Guru)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}