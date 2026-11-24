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
        Schema::create('grade_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kolom, misal: "Tugas 1"
            $table->string('type'); // Jenis, misal: "Tugas", "Praktik"
            
            // Ditambahkan onDelete('cascade') agar lebih aman secara relasi
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_categories');
    }
};