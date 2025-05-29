<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        // Add is_admin column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('id');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->boolean('is_active')->default(true)->after('is_admin');
        });

        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@tamacafe.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'last_login_at', 'is_active']);
        });

        // Delete admin user
        DB::table('users')->where('email', 'admin@tamacafe.com')->delete();
    }
};
