<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings
        Setting::truncate();
        
        // General Settings
        $generalSettings = [
            'site_name' => 'Tama Cafe',
            'site_description' => 'Tempat ngopi dan nongkrong asyik di kota Anda',
            'site_logo' => null,
            'favicon' => null,
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'd M Y',
            'time_format' => 'H:i',
        ];
        
        // Contact Information
        $contactSettings = [
            'email' => 'info@tamacafe.com',
            'phone' => '+62 812 3456 7890',
            'address' => 'Jl. Contoh No. 123, Kota Anda',
            'city' => 'Kota Anda',
            'province' => 'Jawa Barat',
            'postal_code' => '12345',
            'google_maps_embed' => null,
        ];
        
        // Social Media
        $socialMediaSettings = [
            'facebook' => 'https://facebook.com/tamacafe',
            'instagram' => 'https://instagram.com/tamacafe',
            'twitter' => 'https://twitter.com/tamacafe',
            'youtube' => 'https://youtube.com/tamacafe',
            'tiktok' => 'https://tiktok.com/@tamacafe',
        ];
        
        // Business Hours
        $businessHoursSettings = [
            'monday' => '09:00 - 22:00',
            'tuesday' => '09:00 - 22:00',
            'wednesday' => '09:00 - 22:00',
            'thursday' => '09:00 - 22:00',
            'friday' => '09:00 - 23:00',
            'saturday' => '10:00 - 23:00',
            'sunday' => '10:00 - 22:00',
        ];
        
        // Save settings
        Setting::set('general', 'site', $generalSettings);
        Setting::set('contact', 'info', $contactSettings);
        Setting::set('social', 'media', $socialMediaSettings);
        Setting::set('business', 'hours', $businessHoursSettings);
        
        $this->command->info('Settings table seeded successfully!');
    }
}
