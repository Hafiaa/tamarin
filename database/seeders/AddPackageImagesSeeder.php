<?php

namespace Database\Seeders;

use App\Models\PackageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AddPackageImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the images directory exists
        $imagesPath = public_path('images');
        if (!file_exists($imagesPath)) {
            mkdir($imagesPath, 0755, true);
        }
        
        // Path for the placeholder image
        $defaultImagePath = public_path('images/package-placeholder.jpg');
        
        // Create a placeholder image if it doesn't exist
        if (!file_exists($defaultImagePath)) {
            // Create a simple placeholder image using GD
            $width = 800;
            $height = 600;
            $image = imagecreatetruecolor($width, $height);
            
            // Fill background
            $bgColor = imagecolorallocate($image, 240, 240, 240);
            imagefill($image, 0, 0, $bgColor);
            
            // Add text
            $textColor = imagecolorallocate($image, 150, 150, 150);
            $text = 'Package Image';
            $fontSize = 5; // 1-5 for built-in fonts
            $textWidth = imagefontwidth($fontSize) * strlen($text);
            $textX = ($width - $textWidth) / 2;
            $textY = $height / 2 - imagefontheight($fontSize) / 2;
            
            imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
            
            // Save the image
            imagejpeg($image, $defaultImagePath, 80);
            imagedestroy($image);
        }
        
        // Get all packages
        $packages = PackageTemplate::all();
        
        foreach ($packages as $package) {
            // Skip if package already has media
            if ($package->hasMedia('cover_image')) {
                continue;
            }
            
            // Add default cover image
            $package->addMedia($defaultImagePath)
                   ->preservingOriginal()
                   ->toMediaCollection('cover_image');
        }
        
        $this->command->info('Added default images to packages');
    }
}
