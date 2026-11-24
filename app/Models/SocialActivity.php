<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialActivity extends Model
{
    use HasFactory;

    protected $table = 'social_activities';

    protected $fillable = [
        'user_id',
        'date',
        'jenis_kegiatan',
        'deskripsi',
        'lokasi_kegiatan',
        'foto_kegiatan',
        'status',
        'points',
    ];

    protected $casts = [
        'date'   => 'date',
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
