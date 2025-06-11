<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]);
        }

        // Create test customer if not exists
        if (!User::where('email', 'customer@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test Customer',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]);
        }
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate menu-related tables
        \App\Models\MenuItem::truncate();
        \App\Models\MenuCategory::truncate();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Seed complete menu items and package templates
        $this->call([
            CompleteMenuItemsSeeder::class,
            PackageTemplatesSeeder::class,
            AddPackageImagesSeeder::class
        ]);
        
        // Skip other seeders as they're not needed or replaced
        // $this->call([
        //     NewMenuItemsSeeder::class,
        //     AdditionalMenuItemsSeeder::class,
        //     BlockedDatesAndTestimonialsSeeder::class,
        //     UpdatedMenuItemsSeeder::class,
        // ]);
    }
}
