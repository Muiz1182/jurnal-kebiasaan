<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OlahragaJournal extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * (Sesuaikan jika nama tabel di database Anda berbeda, misal: 'olahraga_journals')
     */
    protected $table = 'olahraga_journals';

    /**
     * Atribut yang diizinkan untuk mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'jenis_olahraga',
        'durasi',
        'intensitas',
        'perasaan',
        'catatan',
    ];

    /**
     * Relasi ke model User (jika siswa terhubung ke tabel users).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}