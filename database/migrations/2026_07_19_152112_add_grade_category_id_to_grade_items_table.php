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
        Schema::table('grade_items', function (Blueprint $table) {
            // Menambahkan kolom foreign key grade_category_id
            // nullable() digunakan agar data yang lama tidak error
            // constrained() otomatis merujuk ke tabel grade_categories (kolom id)
            $table->foreignId('grade_category_id')->nullable()->constrained('grade_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_items', function (Blueprint $table) {
            // Menghapus foreign key terlebih dahulu sebelum menghapus kolomnya
            $table->dropForeign(['grade_category_id']);
            $table->dropColumn('grade_category_id');
        });
    }
};