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
        if (!Schema::hasTable('social_activities')) {
            Schema::create('social_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->string('jenis_kegiatan');
                $table->text('deskripsi');
                $table->string('lokasi_kegiatan')->nullable();
                $table->string('foto_kegiatan')->nullable();
                $table->string('status', 50)->default('approved');
                $table->integer('points')->default(30);
                $table->timestamps();

                $table->index(['user_id', 'date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_activities');
    }
};
