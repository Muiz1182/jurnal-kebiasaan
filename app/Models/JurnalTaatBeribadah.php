<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalTaatBeribadah extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit (student_journals)
    protected $table = 'student_journals';

    // Kolom-kolom yang diizinkan untuk mass assignment (pencatatan data harian siswa)
    protected $fillable = [
        'user_id',
        'date',
        'bangun_pagi',
        'ibadah',
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
        'baca_alquran',
        'olahraga',
        'makan_sehat',
        'belajar',
        'sosialisasi',
        'tidur_cepat',
        'waktu_bangun',
        'status_waktu',
        'mood',
        'refleksi',
        'merapikan_tempat_tidur',
        'minum_air_putih',
        'mandi_pagi',
        'persiapan_sekolah',
        'jenis_olahraga',
        'durasi',
        'intensitas',
        'perasaan',
        'catatan',
        'menu_makanan',
        'sayur_buah',
        'air_putih',
        'materi_belajar',
        'durasi_belajar',
        'judul_buku',
        'halaman_terakhir',
        'kategori_keterampilan',
        'jam_tidur',
        'target_bangun',
        'kualitas_istirahat',
        'refleksi_malam',
        'rutinitas_malam',
        'status_tidur',
        'is_begadang'
    ];

    // Casting tipe data kolom agar otomatis dikenali sebagai boolean, array, atau date oleh Laravel
    protected $casts = [
        'date' => 'date',
        'bangun_pagi' => 'boolean',
        'ibadah' => 'boolean',
        'subuh' => 'boolean',
        'dzuhur' => 'boolean',
        'ashar' => 'boolean',
        'maghrib' => 'boolean',
        'isya' => 'boolean',
        'baca_alquran' => 'boolean',
        'olahraga' => 'boolean',
        'makan_sehat' => 'boolean',
        'belajar' => 'boolean',
        'sosialisasi' => 'boolean',
        'tidur_cepat' => 'boolean',
        'sayur_buah' => 'boolean',
        'is_begadang' => 'boolean',
        'rutinitas_malam' => 'array',
    ];

    /**
     * Relasi ke model User (Setiap jurnal dimiliki oleh satu siswa/user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}