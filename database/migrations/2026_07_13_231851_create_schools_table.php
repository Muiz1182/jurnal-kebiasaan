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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Sekolah
            $table->text('address')->nullable(); // Alamat
            $table->string('headmaster')->nullable(); // Nama Kepala Sekolah
            $table->string('npsn', 20)->nullable()->unique(); // NPSN (unik)
            $table->string('phone', 20)->nullable(); // Nomor Telepon
            $table->string('email')->nullable(); // Email Sekolah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};