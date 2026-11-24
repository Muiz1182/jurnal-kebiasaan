<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'PAI', 'code' => 'PAI'],
            ['name' => 'Informatika', 'code' => 'IF'],
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN'],
            ['name' => 'Bahasa Jawa', 'code' => 'BJ'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG'],
            ['name' => 'IPA', 'code' => 'IPA'],
            ['name' => 'IPS', 'code' => 'IPS'],
            ['name' => 'PPKn', 'code' => 'PPKN'],
            ['name' => 'PJOK', 'code' => 'PJOK'],
            ['name' => 'Seni Budaya', 'code' => 'SBK'],
            ['name' => 'Seni Tari', 'code' => 'STar'],
            ['name' => 'Prakarya', 'code' => 'PRK'],
            ['name' => 'Bimbingan Konseling', 'code' => 'BK'],
            ['name' => 'Ko-Kurikuler', 'code' => 'KOKU'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                ['name' => $subject['name']]
            );
        }
    }
}