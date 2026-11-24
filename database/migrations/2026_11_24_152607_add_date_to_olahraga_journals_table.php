<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('olahraga_journals', function (Blueprint $table) {
            $table->date('date')->nullable()->after('user_id');
        });

        // Set date to DATE(created_at) for any existing rows
        DB::statement("UPDATE olahraga_journals SET date = DATE(created_at) WHERE date IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olahraga_journals', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
