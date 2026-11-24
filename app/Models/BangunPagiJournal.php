<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BangunPagiJournal extends Model
{
    use HasFactory;

    protected $table = 'bangun_pagi_journals';

    protected $fillable = [
        'user_id',
        'date',
        'waktu_bangun',
        'status_waktu',
        'merapikan_tempat_tidur',
        'minum_air_putih',
        'mandi_pagi',
        'persiapan_sekolah',
        'mood',
        'refleksi',
    ];

    protected $casts = [
        'merapikan_tempat_tidur' => 'boolean',
        'minum_air_putih'        => 'boolean',
        'mandi_pagi'              => 'boolean',
        'persiapan_sekolah'       => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
