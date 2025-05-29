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
        
        // Seed blocked dates and testimonials
        $this->call([
            BlockedDatesAndTestimonialsSeeder::class,
        ]);

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables first to avoid duplicates
        \App\Models\MenuItem::truncate();
        \App\Models\MenuCategory::truncate();
        \App\Models\ServiceItem::truncate();
        \App\Models\PackageTemplate::truncate();
        \App\Models\EventType::truncate();
        
        // Re-enable foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed menu categories and items
        $this->call([
            UpdatedMenuItemsSeeder::class,
            PackageTemplatesSeeder::class,
        ]);
    }
}
