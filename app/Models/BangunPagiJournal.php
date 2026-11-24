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
        'device_time',
        'status_waktu',
        'is_manipulated',
        'merapikan_tempat_tidur',
        'minum_air_putih',
        'mandi_pagi',
        'persiapan_sekolah',
        'mood',
        'refleksi',
    ];

    protected $casts = [
        'is_manipulated'         => 'boolean',
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
