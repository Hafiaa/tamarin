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
        // Mark the pending migrations as completed by inserting them into the migrations table
        DB::table('migrations')->insert([
            ['migration' => '2025_06_02_011857_create_service_items_table', 'batch' => 6],
            ['migration' => '2025_06_02_013957_add_cancellation_fields_to_reservations_table', 'batch' => 6],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the migrations from the migrations table if we need to rollback
        DB::table('migrations')
            ->whereIn('migration', [
                '2025_06_02_011857_create_service_items_table',
                '2025_06_02_013957_add_cancellation_fields_to_reservations_table',
            ])
            ->delete();
    }
};
