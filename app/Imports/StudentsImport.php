<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    protected $teacherId;

    /**
     * Menerima teacher_id dari SettingsController
     */
    public function __construct($teacherId)
    {
        $this->teacherId = $teacherId;
    }

    public function model(array $row)
    {
        // Baris ini akan melewatkan baris pertama (yang berisi judul kolom)
        // Kita cek apakah kolom pertama (NIS) adalah teks "nis"
        if (strtolower($row[0]) === 'nis') {
            return null;
        }

        return new Student([
            'nis'        => $row[0], // Kolom A
            'name'       => $row[1], // Kolom B
            'kelas'      => $row[2], // Kolom C
            'teacher_id' => $this->teacherId, // Menyisipkan ID guru yang sedang login
        ]);
    }
}