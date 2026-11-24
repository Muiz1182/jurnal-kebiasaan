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
        Schema::table('journals', function (Blueprint $table) {
            // Menambahkan detail yang belum ada
            $table->text('notes')->nullable()->after('content'); // Catatan kejadian
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');
            
            // Menambahkan kolom presensi
            $table->integer('total_students')->default(0)->after('end_time');
            $table->integer('present_count')->default(0);
            $table->integer('sick_count')->default(0);
            $table->integer('permit_count')->default(0);
            $table->integer('absent_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn([
                'notes', 
                'start_time', 
                'end_time', 
                'total_students', 
                'present_count', 
                'sick_count', 
                'permit_count', 
                'absent_count'
            ]);
        });
    }
};