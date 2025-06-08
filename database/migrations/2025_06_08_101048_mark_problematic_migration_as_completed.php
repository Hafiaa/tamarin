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
        // Mark the problematic migration as completed
        \DB::table('migrations')->insert([
            'migration' => '2025_06_02_011857_create_service_items_table',
            'batch' => 2
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the migration record if needed
        \DB::table('migrations')
            ->where('migration', '2025_06_02_011857_create_service_items_table')
            ->delete();
    }
};
