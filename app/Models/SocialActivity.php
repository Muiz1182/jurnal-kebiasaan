<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialActivity extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * (Opsional jika mengikuti konvensi plural Laravel, namun didefinisikan secara eksplisit untuk keamanan).
     *
     * @var string
     */
    protected $table = 'social_activities';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jenis_kegiatan',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi ke model User (Setiap aktivitas sosial dimiliki oleh satu siswa/user).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}