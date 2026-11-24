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
        // Pastikan tabel students sudah ada sebelum membuat foreign key
        if (!Schema::hasTable('students')) {
            throw new \Exception("Tabel 'students' belum dibuat. Jalankan migrasi tabel students terlebih dahulu.");
        }

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke students
            // constrained('students') merujuk ke nama tabel
            $table->foreignId('student_id')
                  ->constrained('students') 
                  ->onDelete('cascade');
            
            $table->date('date');
            $table->string('status', 10); // H, S, I, A
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};