<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $site_name;
    public ?string $site_description;
    public ?string $site_logo;
    public ?string $site_favicon;
    public ?string $phone;
    public ?string $email;
    public ?string $address;
    public ?string $facebook_url;
    public ?string $twitter_url;
    public ?string $instagram_url;
    public ?string $youtube_url;
    public ?string $working_hours;
    public ?string $google_maps_embed;
    
    public static function group(): string
    {
        return 'site';
    }
    
    public static function getDefaults(): array
    {
        return [
            'site_name' => 'Tama Cafe',
            'site_description' => 'Tempat nongkrong dan kumpul keluarga yang nyaman dengan berbagai pilihan menu lezat',
            'phone' => '(021) 12345678',
            'email' => 'info@tamacafe.com',
            'address' => 'Jl. Contoh No. 123, Jakarta Selatan',
            'facebook_url' => 'https://facebook.com/tamacafe',
            'instagram_url' => 'https://instagram.com/tamacafe',
            'youtube_url' => 'https://youtube.com/tamacafe',
            'working_hours' => 'Senin - Minggu, 08:00 - 22:00 WIB',
            'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6666666666665!2d106.81666666666666!3d-6.175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTAnMzAiUyAxMDbCsDQ5JzAwIlc!5e0!3m2!1sen!2sid!4v1234567890',
        ];
    }
}
