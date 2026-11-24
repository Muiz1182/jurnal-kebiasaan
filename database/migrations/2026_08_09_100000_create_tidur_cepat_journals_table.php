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
        if (!Schema::hasTable('tidur_cepat_journals')) {
            Schema::create('tidur_cepat_journals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->string('jam_tidur', 10);
                $table->string('target_bangun', 10);
                $table->string('durasi_tidur', 50)->nullable();
                $table->string('kualitas_istirahat', 50)->default('Nyenyak');
                $table->string('status_tidur', 50)->default('Tepat Waktu');
                $table->text('rutinitas_malam')->nullable();
                $table->text('refleksi_malam')->nullable();
                $table->boolean('is_begadang')->default(false);
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
        Schema::dropIfExists('tidur_cepat_journals');
    }
};
