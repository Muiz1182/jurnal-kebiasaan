<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Menambahkan nullable agar data lama tidak error saat migrasi
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Menghapus foreign key terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};