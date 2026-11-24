<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TidurCepatJournal extends Model
{
    use HasFactory;

    protected $table = 'tidur_cepat_journals';

    protected $fillable = [
        'user_id',
        'date',
        'jam_tidur',
        'target_bangun',
        'durasi_tidur',
        'kualitas_istirahat',
        'status_tidur',
        'rutinitas_malam',
        'refleksi_malam',
        'is_begadang',
    ];

    protected $casts = [
        'date'            => 'date',
        'is_begadang'     => 'boolean',
        'rutinitas_malam' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
