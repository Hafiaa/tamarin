<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Mark the problematic migrations as already run
        \DB::table('migrations')->insert([
            ['migration' => '2025_06_02_011857_create_service_items_table', 'batch' => 1],
            ['migration' => '2025_06_02_011903_create_custom_package_items_table', 'batch' => 1],
            ['migration' => '2025_06_02_012054_add_reference_files_to_reservations_table', 'batch' => 1],
            ['migration' => '2025_06_02_013957_add_cancellation_fields_to_reservations_table', 'batch' => 1],
            ['migration' => '2025_06_02_015118_update_reservation_revisions_table', 'batch' => 1],
            ['migration' => '2025_06_02_020450_mark_pending_migrations_as_completed', 'batch' => 1],
            ['migration' => '2025_06_02_024112_update_service_items_table', 'batch' => 1],
            ['migration' => '2025_06_03_111004_add_cancellation_columns_to_reservations_table', 'batch' => 1],
            ['migration' => '2025_06_04_112005_add_end_time_to_reservations_table', 'batch' => 1],
            ['migration' => '2025_06_06_092844_update_custom_packages_schema', 'batch' => 1],
            ['migration' => '2025_06_08_100943_add_is_active_to_service_items', 'batch' => 1],
            ['migration' => '2025_06_08_101005_fix_service_items_structure', 'batch' => 1],
            ['migration' => '2025_06_08_101048_mark_problematic_migration_as_completed', 'batch' => 1],
            ['migration' => '2025_06_08_104304_add_blocked_until_to_blocked_dates_table', 'batch' => 1],
            ['migration' => '2025_06_08_105000_fix_blocked_dates_table', 'batch' => 1],
            ['migration' => '2025_06_11_000000_add_payment_status_flow', 'batch' => 1],
            ['migration' => '2025_06_11_000000_update_payments_table', 'batch' => 1],
            ['migration' => '2025_06_11_100000_update_payments_table_for_status_flow', 'batch' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // No need to do anything on down
    }
};
