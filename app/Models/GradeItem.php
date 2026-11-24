<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeItem extends Model
{
    use HasFactory;

    // Menentukan kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'grade_id',
        'grade_category_id', // Penting untuk menyimpan relasi kategori
        'column_name',
        'value'
    ];

    /**
     * Relasi: GradeItem milik satu Grade
     * 
     * @return BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    /**
     * Relasi: GradeItem milik satu GradeCategory
     * 
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        // Menambahkan relasi ini agar error "undefined relationship" hilang
        return $this->belongsTo(GradeCategory::class, 'grade_category_id', 'id');
    }
}