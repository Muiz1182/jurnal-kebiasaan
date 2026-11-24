<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'subjects';

    /**
     * Tentukan field yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Relasi ke model Grade: Satu mata pelajaran memiliki banyak data nilai.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id', 'id');
    }
}