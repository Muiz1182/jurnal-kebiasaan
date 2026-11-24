<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'schools';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'name',
        'address',
        'headmaster',
        'npsn',
        'phone',
        'email',
    ];

    /**
     * Menambahkan casting untuk kolom tertentu.
     * Menggunakan sintaks modern (method) untuk kompatibilitas Laravel terbaru.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relasi: Satu sekolah bisa memiliki banyak User (Guru/Staf).
     * Sesuaikan foreign key jika nama kolom di tabel users berbeda.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'school_id');
    }

    /**
     * Relasi: Satu sekolah bisa memiliki banyak Student.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'school_id');
    }
}