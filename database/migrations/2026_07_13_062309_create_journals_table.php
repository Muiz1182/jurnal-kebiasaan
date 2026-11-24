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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Guru) dan Schedule (Jadwal)
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            
            // Konten jurnal
            $table->text('content');
            
            // Status jurnal untuk kebutuhan "Recent Activity"
            // Default 'pending', bisa diubah ke 'approved' atau 'revision'
            $table->enum('status', ['pending', 'approved', 'revision'])->default('pending');
            
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};