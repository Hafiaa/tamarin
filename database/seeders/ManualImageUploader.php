<?php

namespace Database\Seeders;

use App\Models\PackageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManualImageUploader extends Seeder
{
    public function run()
    {
        $packageId = 1; // Ganti dengan ID package yang ingin diupload gambarnya
        $package = \App\Models\PackageTemplate::find($packageId);

        if (!$package) {
            $this->command->error('Package tidak ditemukan!');
            return;
        }

        // Hapus semua media yang ada
        $package->clearMediaCollection('featured_image');
        $package->clearMediaCollection('gallery');

        // Upload featured image
        $this->uploadFeaturedImage($package);
        
        // Upload gallery images
        $this->uploadGalleryImages($package);
        
        $this->command->info('Upload gambar selesai!');
    }
    
    private function uploadFeaturedImage($package)
    {
        $imagePath = storage_path('app/public/package-featured/namafile.jpg'); // Ganti dengan nama file yang sesuai
        
        if (file_exists($imagePath)) {
            $package->addMedia($imagePath)
                  ->usingName(pathinfo($imagePath, PATHINFO_FILENAME))
                  ->usingFileName(Str::random(20) . '.' . pathinfo($imagePath, PATHINFO_EXTENSION))
                  ->toMediaCollection('featured_image', 'public');
                  
            $this->command->info('Featured image berhasil diupload!');
        } else {
            $this->command->warn('File featured image tidak ditemukan: ' . $imagePath);
        }
    }
    
    private function uploadGalleryImages($package)
    {
        $galleryPath = storage_path('app/public/package-gallery/');
        $images = glob($galleryPath . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        
        if (count($images) > 0) {
            foreach ($images as $image) {
                $package->addMedia($image)
                      ->usingName(pathinfo($image, PATHINFO_FILENAME))
                      ->usingFileName(Str::random(20) . '.' . pathinfo($image, PATHINFO_EXTENSION))
                      ->toMediaCollection('gallery', 'public');
            }
            $this->command->info(count($images) . ' gambar galeri berhasil diupload!');
        } else {
            $this->command->warn('Tidak ada file gambar di direktori galeri');
        }
    }
}
