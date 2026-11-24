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
        if (!Schema::hasTable('gemar_belajar_journals')) {
            Schema::create('gemar_belajar_journals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->text('materi_belajar')->nullable();
                $table->string('judul_buku')->nullable();
                $table->string('halaman_terakhir', 50)->nullable();
                $table->integer('durasi_belajar')->default(30);
                $table->string('kategori_keterampilan', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->string('level', 50)->default('Pemula');
                $table->string('status_target', 100)->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gemar_belajar_journals');
    }
};
