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
        Schema::table('bangun_pagi_journals', function (Blueprint $table) {
            $table->string('waktu_bangun', 100)->change();
            $table->string('status_waktu', 100)->change();
            $table->string('device_time', 50)->nullable()->after('waktu_bangun');
            $table->boolean('is_manipulated')->default(false)->after('status_waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bangun_pagi_journals', function (Blueprint $table) {
            $table->dropColumn(['device_time', 'is_manipulated']);
            $table->string('waktu_bangun', 10)->change();
            $table->string('status_waktu', 50)->change();
        });
    }
};
