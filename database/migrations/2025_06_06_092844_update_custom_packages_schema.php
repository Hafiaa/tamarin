php <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's update the custom_packages table
        if (Schema::hasTable('custom_packages')) {
            // Drop foreign key constraints first
            Schema::table('custom_packages', function (Blueprint $table) {
                // Get all foreign key constraints
                $foreignKeys = DB::select(
                    "SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                    WHERE TABLE_SCHEMA = ? 
                    AND TABLE_NAME = 'custom_packages' 
                    AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                    [config('database.connections.mysql.database')]
                );

                // Drop each foreign key constraint
                foreach ($foreignKeys as $key) {
                    $constraintName = $key->CONSTRAINT_NAME;
                    // Remove database name prefix if it exists
                    $constraintName = preg_replace('/^[^.]+\./', '', $constraintName);
                    $table->dropForeign($constraintName);
                }

                // Add new columns if they don't exist
                if (!Schema::hasColumn('custom_packages', 'user_id')) {
                    $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('custom_packages', 'event_type_id')) {
                    $table->foreignId('event_type_id')->after('user_id')->constrained()->onDelete('cascade');
                }
                
                // Drop old columns if they exist
                $columnsToDrop = [
                    'reservation_id',
                    'price',
                    'moodboard'
                ];
                
                foreach ($columnsToDrop as $column) {
                    if (Schema::hasColumn('custom_packages', $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                // Add new columns with proper types
                if (!Schema::hasColumn('custom_packages', 'total_price')) {
                    $table->decimal('total_price', 12, 2)->after('description');
                }
                
                if (!Schema::hasColumn('custom_packages', 'guest_count')) {
                    $table->integer('guest_count')->default(1)->after('total_price');
                }
                
                if (!Schema::hasColumn('custom_packages', 'event_date')) {
                    $table->date('event_date')->after('guest_count');
                }
                
                if (!Schema::hasColumn('custom_packages', 'event_time')) {
                    $table->time('event_time')->after('event_date');
                }
                
                if (!Schema::hasColumn('custom_packages', 'bride_name')) {
                    $table->string('bride_name')->nullable()->after('event_time');
                }
                
                if (!Schema::hasColumn('custom_packages', 'groom_name')) {
                    $table->string('groom_name')->nullable()->after('bride_name');
                }
                
                if (!Schema::hasColumn('custom_packages', 'special_requests')) {
                    $table->text('special_requests')->nullable()->after('groom_name');
                }
                
                if (!Schema::hasColumn('custom_packages', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('status');
                }
                
                // Update status column if it exists
                if (Schema::hasColumn('custom_packages', 'status')) {
                    $table->string('status')->default('draft')->change();
                }
                
                // Add soft deletes if not exists
                if (!Schema::hasColumn('custom_packages', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // Now, let's update the custom_package_items table
        if (Schema::hasTable('custom_package_items')) {
            Schema::table('custom_package_items', function (Blueprint $table) {
                // Get all foreign key constraints
                $foreignKeys = DB::select(
                    "SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                    WHERE TABLE_SCHEMA = ? 
                    AND TABLE_NAME = 'custom_package_items' 
                    AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                    [config('database.connections.mysql.database')]
                );

                // Drop each foreign key constraint
                foreach ($foreignKeys as $key) {
                    $constraintName = $key->CONSTRAINT_NAME;
                    // Remove database name prefix if it exists
                    $constraintName = preg_replace('/^[^.]+\./', '', $constraintName);
                    $table->dropForeign($constraintName);
                }
                
                // Rename reservation_id to custom_package_id if it exists
                if (Schema::hasColumn('custom_package_items', 'reservation_id')) {
                    $table->renameColumn('reservation_id', 'custom_package_id');
                }
                
                // Add foreign key to custom_packages
                if (Schema::hasColumn('custom_package_items', 'custom_package_id')) {
                    $table->foreign('custom_package_id')
                        ->references('id')
                        ->on('custom_packages')
                        ->onDelete('cascade');
                }
                
                // Ensure service_item_id exists
                if (!Schema::hasColumn('custom_package_items', 'service_item_id')) {
                    $table->unsignedBigInteger('service_item_id')
                        ->nullable()
                        ->after('custom_package_id');
                }
                
                // Add foreign key constraint only if service_items table exists
                if (Schema::hasTable('service_items')) {
                    // First, drop any existing foreign key constraints
                    $foreignKeys = DB::select(
                        "SELECT CONSTRAINT_NAME 
                        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_NAME = 'custom_package_items' 
                        AND CONSTRAINT_TYPE = 'FOREIGN_KEY' 
                        AND CONSTRAINT_NAME LIKE '%service_item_id%'",
                        [config('database.connections.mysql.database')]
                    );
                    
                    foreach ($foreignKeys as $key) {
                        $constraintName = $key->CONSTRAINT_NAME;
                        $constraintName = preg_replace('/^[^.]+\./', '', $constraintName);
                        $table->dropForeign($constraintName);
                    }
                    
                    // Add the foreign key constraint
                    $table->foreign('service_item_id')
                        ->references('id')
                        ->on('service_items')
                        ->onDelete('set null');
                }
                
                // Add name column if it doesn't exist
                if (!Schema::hasColumn('custom_package_items', 'name')) {
                    $table->string('name')->after('service_item_id');
                }
                
                // Add description column if it doesn't exist
                if (!Schema::hasColumn('custom_package_items', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                
                // Rename unit_price to price if it exists, otherwise add price
                if (Schema::hasColumn('custom_package_items', 'unit_price')) {
                    $table->renameColumn('unit_price', 'price');
                } elseif (!Schema::hasColumn('custom_package_items', 'price')) {
                    $table->decimal('price', 12, 2)->after('description');
                }
                
                // Ensure quantity exists
                if (!Schema::hasColumn('custom_package_items', 'quantity')) {
                    $table->integer('quantity')->default(1)->after('price');
                }
                
                // Add category column if it doesn't exist
                if (!Schema::hasColumn('custom_package_items', 'category')) {
                    $table->string('category')->after('quantity');
                }
                
                // Handle options column
                if (Schema::hasColumn('custom_package_items', 'options')) {
                    // Convert existing options to JSON if it's not already
                    $table->json('options')->nullable()->change();
                } else {
                    $table->json('options')->nullable()->after('category');
                }
                
                // Drop total_price as it can be calculated
                if (Schema::hasColumn('custom_package_items', 'total_price')) {
                    $table->dropColumn('total_price');
                }
                
                // Drop notes column as it's not needed
                if (Schema::hasColumn('custom_package_items', 'notes')) {
                    $table->dropColumn('notes');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Note: This is a one-way migration. The down method is intentionally left empty
     * as reversing these changes would be complex and potentially destructive.
     * If you need to revert these changes, you should create a new migration.
     */
    public function down(): void
    {
        // Intentionally left empty
    }
};
