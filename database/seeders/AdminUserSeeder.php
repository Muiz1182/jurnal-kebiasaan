<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smpn9prob.sch.id'],
            [
                'name' => 'Super Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin1182'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}