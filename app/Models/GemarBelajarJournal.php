<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemarBelajarJournal extends Model
{
    use HasFactory;

    protected $table = 'gemar_belajar_journals';

    protected $fillable = [
        'user_id',
        'date',
        'materi_belajar',
        'judul_buku',
        'halaman_terakhir',
        'durasi_belajar',
        'kategori_keterampilan',
        'catatan',
        'level',
        'status_target',
    ];

    protected $casts = [
        'date'           => 'date',
        'durasi_belajar' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
