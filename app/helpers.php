<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        static $settings = [];
        
        // If we've already loaded this setting, return it
        if (array_key_exists($key, $settings)) {
            return $settings[$key] ?? $default;
        }
        
        // Otherwise, load the setting from the database
        $setting = DB::table('settings')
            ->where('group', 'site')
            ->where('name', $key)
            ->first();
            
        if ($setting) {
            $settings[$key] = json_decode($setting->payload, true);
            return $settings[$key];
        }
        
        return $default;
    }
}
