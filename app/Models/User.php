<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password', 
        'nip', 
        'jabatan', 
        'nisn',      // Ditambahkan untuk mendukung pendaftaran akun murid
        'kelas',     // Ditambahkan untuk mendukung pendaftaran akun murid
        'role', // Ditambahkan untuk mendukung manajemen hak akses role akun
        'is_active',
        'foto_profil',
        'email_notifications',   
        'browser_notifications',
        'kelas_mengajar', 
        // 'mata_pelajaran' dihapus karena sekarang dikelola via relasi Many-to-Many
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_notifications' => 'boolean',
            'browser_notifications' => 'boolean',
        ];
    }

    // Relasi ke Journal
    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class, 'teacher_id');
    }

    /**
     * REVISI: Relasi ke Subject (Many-to-Many)
     * Menggunakan tabel pivot 'subject_user'
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }

    /**
     * Accessor untuk daftar kelas mengajar sebagai array
     */
    protected function kelasList(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($attributes['kelas_mengajar']) ? explode(',', $attributes['kelas_mengajar']) : []
        );
    }

    /**
     * Accessor untuk foto_url
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
                    return Storage::url($this->foto_profil);
                }
                return asset('images/default-avatar.png');
            },
        );
    }

    /**
     * Helper Check Role untuk Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}