<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakanSehatJournal extends Model
{
    use HasFactory;

    protected $table = 'makan_sehat_journals';

    protected $fillable = [
        'user_id',
        'date',
        'sarapan',
        'makan_siang',
        'makan_malam',
        'porsi_sayur',
        'porsi_buah',
        'air_putih',
        'cemilan',
        'status_gizi',
    ];

    protected $casts = [
        'date'        => 'date',
        'porsi_sayur' => 'integer',
        'porsi_buah'  => 'integer',
        'air_putih'   => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
