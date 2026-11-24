<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'type', 'subject_id'];

    /**
     * Relasi ke Mata Pelajaran (Subject)
     * Setiap kategori nilai terikat pada satu mata pelajaran tertentu.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relasi ke item nilai (GradeItem)
     * Kategori ini memiliki banyak item nilai di dalamnya.
     */
    public function gradeItems(): HasMany
    {
        return $this->hasMany(GradeItem::class, 'grade_category_id');
    }
}