<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    // Menggunakan NIS sesuai dengan data Excel Anda
    protected $fillable = ['name', 'nis', 'kelas', 'foto', 'teacher_id'];

    /**
     * Relasi ke User (Guru)
     * Setiap siswa dimiliki oleh satu guru/pengampu.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relasi ke tabel attendance
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi ke tabel grades
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    /**
     * Scope untuk memfilter siswa berdasarkan kelas.
     */
    public function scopeByKelas($query, $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Scope untuk memfilter siswa berdasarkan guru yang sedang login.
     */
    public function scopeForTeacher($query)
    {
        return $query->where('teacher_id', auth()->id());
    }
}