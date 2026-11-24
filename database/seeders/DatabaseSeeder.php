<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder mata pelajaran
        $this->call([
            SubjectSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Buat akun contoh Guru
        User::updateOrCreate(
            ['username' => 'guru'],
            [
                'name' => 'Guru Pengajar',
                'email' => 'guru@smpn9prob.sch.id',
                'password' => \Illuminate\Support\Facades\Hash::make('guru12345'),
                'role' => 'guru',
                'jabatan' => 'Matematika',
                'kelas_mengajar' => '7A,7B',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Buat akun contoh Murid
        User::updateOrCreate(
            ['username' => 'murid'],
            [
                'name' => 'Siswa SMPN 9',
                'email' => 'murid@smpn9prob.sch.id',
                'password' => \Illuminate\Support\Facades\Hash::make('murid12345'),
                'role' => 'murid',
                'kelas' => '7A',
                'nisn' => '0012345678',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}