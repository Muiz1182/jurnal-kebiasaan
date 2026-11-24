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
        // Tabel Utama Grades
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            
            // Kolom statis yang Anda inginkan tetap ada
            $table->integer('tugas')->default(0);
            $table->integer('praktik')->default(0);
            $table->integer('ulangan_harian')->default(0);
            $table->integer('uts')->default(0);
            $table->integer('uas')->default(0);
            $table->decimal('rata_rata', 5, 2)->default(0);
            $table->string('status')->default('Remedial');
            $table->timestamps();
        });

        // Tabel GradeItems: Ini yang memungkinkan fitur "+ Tambah Kolom" bersifat dinamis & permanen
        Schema::create('grade_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->string('column_name'); // Nama kolom dinamis, misal: "Tugas 2", "Proyek"
            $table->integer('value')->default(0); // Nilai untuk kolom tersebut
            
            // Menambahkan unique agar tidak terjadi duplikasi nama kolom untuk grade_id yang sama
            $table->unique(['grade_id', 'column_name']); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tabel item terlebih dahulu sebelum tabel utama
        Schema::dropIfExists('grade_items');
        Schema::dropIfExists('grades');
    }
};