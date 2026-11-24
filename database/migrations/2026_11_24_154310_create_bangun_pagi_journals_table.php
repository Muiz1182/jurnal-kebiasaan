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
        Schema::create('bangun_pagi_journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->string('waktu_bangun', 10);
            $table->string('status_waktu', 50);
            $table->boolean('merapikan_tempat_tidur')->default(false);
            $table->boolean('minum_air_putih')->default(false);
            $table->boolean('mandi_pagi')->default(false);
            $table->boolean('persiapan_sekolah')->default(false);
            $table->string('mood', 50)->default('BERENERGI');
            $table->text('refleksi')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bangun_pagi_journals');
    }
};
