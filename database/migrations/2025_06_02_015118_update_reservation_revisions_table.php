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
        Schema::table('reservation_revisions', function (Blueprint $table) {
            // Add user_id foreign key
            $table->foreignId('user_id')->after('reservation_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
                
            // Rename revision_details to description for consistency
            $table->renameColumn('revision_details', 'description');
            
            // Add title column
            $table->string('title')->after('user_id')->nullable();
            
            // Update the changes column to be nullable if not already
            $table->json('changes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_revisions', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['user_id']);
            
            // Drop the user_id column
            $table->dropColumn('user_id');
            
            // Revert the column name back to revision_details
            $table->renameColumn('description', 'revision_details');
            
            // Drop the title column
            $table->dropColumn('title');
        });
    }
};
