<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\PackageTemplate;

class CheckMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check media files and their status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== Checking Media Files ===');
        
        // Check media count
        $mediaCount = Media::count();
        $this->line("Total Media: {$mediaCount}");
        
        // List all media files
        $mediaFiles = Media::all();
        
        $this->line("\n=== Media Files ===");
        foreach ($mediaFiles as $media) {
            $this->line("ID: {$media->id}");
            $this->line("Model: {$media->model_type} (ID: {$media->model_id})");
            $this->line("Collection: {$media->collection_name}");
            $this->line("File: {$media->file_name}");
            $this->line("Disk: {$media->disk}");
            
            $path = storage_path('app/public/' . $media->id . '/' . $media->file_name);
            $this->line("Path: {$path}");
            $this->line("File exists: " . (file_exists($path) ? 'Yes' : 'No'));
            $this->line("---");
        }
        
        // Check first package
        $package = PackageTemplate::first();
        if ($package) {
            $this->line("\n=== First Package ===");
            $this->line("ID: {$package->id}");
            $this->line("Name: {$package->name}");
            $this->line("Media count: " . $package->media()->count());
            
            // Check featured image
            if ($package->hasMedia('featured_image')) {
                $media = $package->getFirstMedia('featured_image');
                $this->line("\nFeatured Image:");
                $this->line("- Path: " . $media->getPath());
                $this->line("- URL: " . $media->getUrl());
                $this->line("- File exists: " . (file_exists($media->getPath()) ? 'Yes' : 'No'));
            } else {
                $this->line("\nNo featured image found.");
            }
            
            // Check gallery
            if ($package->hasMedia('gallery')) {
                $gallery = $package->getMedia('gallery');
                $this->line("\nGallery Images (" . $gallery->count() . "):");
                foreach ($gallery as $media) {
                    $this->line("- " . $media->getUrl());
                }
            } else {
                $this->line("\nNo gallery images found.");
            }
        } else {
            $this->warn("No packages found.");
        }
        
        return Command::SUCCESS;
    }
}
