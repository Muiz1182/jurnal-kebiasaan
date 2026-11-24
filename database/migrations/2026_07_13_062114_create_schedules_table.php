<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // Menghubungkan jadwal dengan guru (tabel users)
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            $table->string('subject');       // Nama mata pelajaran
            $table->string('class_name');    // Contoh: Kelas 9A
            $table->time('start_time');      // Jam mulai (07:30)
            $table->time('end_time');        // Jam selesai (09:00)
            $table->date('date');            // Tanggal jadwal
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};