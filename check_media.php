<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware_Contracts_Console_Kernel::class);

$app->boot();

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\PackageTemplate;

// Check media count
echo "Total Media: " . Media::count() . "\n\n";

// List media files
$mediaFiles = Media::all();

foreach ($mediaFiles as $media) {
    echo "ID: " . $media->id . "\n";
    echo "Model: " . $media->model_type . " (ID: " . $media->model_id . ")\n";
    echo "Collection: " . $media->collection_name . "\n";
    echo "File: " . $media->file_name . "\n";
    echo "Disk: " . $media->disk . "\n";
    $path = storage_path('app/public/' . $media->id . '/' . $media->file_name);
    echo "Path: " . $path . "\n";
    echo "File exists: " . (file_exists($path) ? 'Yes' : 'No') . "\n\n";
}

// Check first package
$package = PackageTemplate::first();
if ($package) {
    echo "\n=== First Package ===\n";
    echo "ID: " . $package->id . "\n";
    echo "Name: " . $package->name . "\n";
    echo "Media count: " . $package->media()->count() . "\n";
    
    // Check featured image
    if ($package->hasMedia('featured_image')) {
        $media = $package->getFirstMedia('featured_image');
        echo "\nFeatured Image:\n";
        echo "- Path: " . $media->getPath() . "\n";
        echo "- URL: " . $media->getUrl() . "\n";
        echo "- File exists: " . (file_exists($media->getPath()) ? 'Yes' : 'No') . "\n";
    } else {
        echo "\nNo featured image found.\n";
    }
    
    // Check gallery
    if ($package->hasMedia('gallery')) {
        echo "\nGallery Images (" . $package->getMedia('gallery')->count() . "):\n";
        foreach ($package->getMedia('gallery') as $media) {
            echo "- " . $media->getUrl() . "\n";
        }
    } else {
        echo "\nNo gallery images found.\n";
    }
} else {
    echo "\nNo packages found.\n";
}
