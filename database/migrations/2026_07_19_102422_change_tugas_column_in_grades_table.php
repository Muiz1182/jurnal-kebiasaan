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
        Schema::table('grades', function (Blueprint $table) {
            // Mengubah kolom tugas menjadi TEXT agar bisa menyimpan format JSON
            $table->text('tugas')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Mengembalikan kolom tugas ke tipe integer
            // Catatan: Jika ada data JSON di kolom tugas, pastikan dibersihkan 
            // sebelum menjalankan rollback agar tidak error konversi tipe data.
            $table->integer('tugas')->change();
        });
    }
};