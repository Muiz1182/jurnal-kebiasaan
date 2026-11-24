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
        if (!Schema::hasTable('makan_sehat_journals')) {
            Schema::create('makan_sehat_journals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->text('sarapan')->nullable();
                $table->text('makan_siang')->nullable();
                $table->text('makan_malam')->nullable();
                $table->integer('porsi_sayur')->default(0);
                $table->integer('porsi_buah')->default(0);
                $table->integer('air_putih')->default(0);
                $table->text('cemilan')->nullable();
                $table->string('status_gizi')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makan_sehat_journals');
    }
};
