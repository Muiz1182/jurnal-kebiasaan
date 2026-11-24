<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Ditambahkan untuk kelengkapan
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'grades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'subject_id',
        'tugas',
        'praktik',
        'ulangan_harian',
        'uts',
        'uas',
        'rata_rata',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tugas' => 'float',
        'praktik' => 'float',
        'ulangan_harian' => 'float',
        'uts' => 'float',
        'uas' => 'float',
        'rata_rata' => 'float',
    ];

    /**
     * Get the student that owns the grade.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Get the subject associated with the grade.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    /**
     * REVISI: Relasi ke tabel grade_items untuk kolom dinamis.
     * 
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(GradeItem::class, 'grade_id', 'id');
    }
}