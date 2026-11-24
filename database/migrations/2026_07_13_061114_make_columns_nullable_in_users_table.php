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
        Schema::table('users', function (Blueprint $table) {
            // Mengubah kolom email menjadi nullable
            $table->string('email')->nullable()->change();
            
            // Mengubah kolom nip menjadi nullable
            $table->string('nip')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan kolom menjadi not nullable (jika ingin dibatalkan)
            // Catatan: Pastikan tidak ada data NULL sebelum menjalankan ini, 
            // jika ada, Anda harus menghapus/mengisi data tersebut terlebih dahulu.
            $table->string('email')->nullable(false)->change();
            $table->string('nip')->nullable(false)->change();
        });
    }
};