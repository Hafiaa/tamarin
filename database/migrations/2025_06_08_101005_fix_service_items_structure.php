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
        // First, add is_active if it doesn't exist
        if (!Schema::hasColumn('service_items', 'is_active')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('description');
            });
        }
        
        // Then add other columns that depend on is_active
        if (!Schema::hasColumn('service_items', 'image')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->string('image')->nullable()->after('is_active');
            });
        }
        
        if (!Schema::hasColumn('service_items', 'options')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->json('options')->nullable()->after('image');
            });
        }
        
        if (!Schema::hasColumn('service_items', 'min_quantity')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->integer('min_quantity')->default(1)->after('options');
            });
        }
        
        if (!Schema::hasColumn('service_items', 'max_quantity')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->integer('max_quantity')->nullable()->after('min_quantity');
            });
        }
        
        if (!Schema::hasColumn('service_items', 'deleted_at')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
        
        // Handle renaming price to base_price if needed
        if (Schema::hasColumn('service_items', 'price') && !Schema::hasColumn('service_items', 'base_price')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->renameColumn('price', 'base_price');
            });
        }
        
        // Ensure type column exists
        if (!Schema::hasColumn('service_items', 'type')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->string('type')->default('service')->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration to fix the structure
        // No need to implement down() as we don't want to risk breaking things
    }
};
