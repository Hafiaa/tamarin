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
        Schema::table('service_items', function (Blueprint $table) {
            // Rename price to base_price if it exists
            if (Schema::hasColumn('service_items', 'price')) {
                $table->renameColumn('price', 'base_price');
            }
            
            // Rename is_available to is_active if it exists
            if (Schema::hasColumn('service_items', 'is_available')) {
                $table->renameColumn('is_available', 'is_active');
            }
            
            // Add new columns
            if (!Schema::hasColumn('service_items', 'type')) {
                $table->string('type')->default('service')->after('description');
            }
            
            if (!Schema::hasColumn('service_items', 'image')) {
                $table->string('image')->nullable()->after('is_active');
            }
            
            if (!Schema::hasColumn('service_items', 'options')) {
                $table->json('options')->nullable()->after('image');
            }
            
            if (!Schema::hasColumn('service_items', 'min_quantity')) {
                $table->integer('min_quantity')->default(1)->after('options');
            }
            
            if (!Schema::hasColumn('service_items', 'max_quantity')) {
                $table->integer('max_quantity')->nullable()->after('min_quantity');
            }
            
            if (!Schema::hasColumn('service_items', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_items', function (Blueprint $table) {
            // Revert column renames
            if (Schema::hasColumn('service_items', 'base_price')) {
                $table->renameColumn('base_price', 'price');
            }
            
            if (Schema::hasColumn('service_items', 'is_active')) {
                $table->renameColumn('is_active', 'is_available');
            }
            
            // Remove added columns
            $columnsToDrop = ['type', 'image', 'options', 'min_quantity', 'max_quantity', 'deleted_at'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('service_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
