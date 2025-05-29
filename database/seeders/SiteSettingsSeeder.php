<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Settings\SiteSettings;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Get default values
        $defaults = SiteSettings::getDefaults();
        
        // Convert old settings to new format if needed
        $siteSettings = [];
        foreach ($defaults as $name => $value) {
            $siteSettings[$name] = $value;
        }
        
        // Save settings using our new Setting model
        Setting::set('site', 'settings', $siteSettings);
        
        $this->command->info('Site settings have been saved successfully!');
    }
}
