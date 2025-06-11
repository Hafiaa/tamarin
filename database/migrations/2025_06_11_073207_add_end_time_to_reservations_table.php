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
        if (!Schema::hasColumn('reservations', 'end_time')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->time('end_time')->nullable()->after('event_time')->comment('End time of the event');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'end_time')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropColumn('end_time');
            });
        }
    }
};
