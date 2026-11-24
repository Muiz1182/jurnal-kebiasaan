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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); // Nama Lengkap
            $table->string('nis')->unique(); // NIS (Unique agar tidak duplikat)
            $table->string('kelas')->nullable(); // Menambahkan kolom kelas
            $table->string('foto')->nullable(); // Menambahkan kolom untuk path foto profil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};