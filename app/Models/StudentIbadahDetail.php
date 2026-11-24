<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIbadahDetail extends Model
{
    use HasFactory;

    protected $table = 'student_ibadah_details';

    protected $fillable = [
        'user_id',
        'date',
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
        'dhuha',
        'baca_alquran'
    ];

    protected $casts = [
        'date' => 'date',
        'subuh' => 'boolean',
        'dzuhur' => 'boolean',
        'ashar' => 'boolean',
        'maghrib' => 'boolean',
        'isya' => 'boolean',
        'dhuha' => 'boolean',
        'baca_alquran' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}