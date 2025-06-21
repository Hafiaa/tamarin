<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ManualUploadController extends Controller
{
    public function showUploadForm($packageId)
    {
        $package = PackageTemplate::findOrFail($packageId);
        return view('admin.manual-upload', compact('package'));
    }

    public function uploadFeaturedImage(Request $request, $packageId)
    {
        $request->validate([
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $package = PackageTemplate::findOrFail($packageId);
        
        // Hapus gambar lama jika ada
        $package->clearMediaCollection('featured_image');
        
        // Tambahkan gambar baru
        $package->addMediaFromRequest('featured_image')
               ->usingFileName(Str::random(20) . '.' . $request->file('featured_image')->getClientOriginalExtension())
               ->toMediaCollection('featured_image', 'public');

        return back()->with('success', 'Foto utama berhasil diunggah!');
    }

    public function uploadGalleryImage(Request $request, $packageId)
    {
        $request->validate([
            'gallery_images' => 'required',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $package = PackageTemplate::findOrFail($packageId);
        
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $package->addMedia($image)
                       ->usingFileName(Str::random(20) . '.' . $image->getClientOriginalExtension())
                       ->toMediaCollection('gallery', 'public');
            }
        }

        return back()->with('success', 'Gambar galeri berhasil diunggah!');
    }

    public function deleteImage($mediaId)
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($mediaId);
        $packageId = $media->model_id;
        
        // Hapus file media
        $media->delete();
        
        return redirect()->route('admin.manual-upload', $packageId)
                         ->with('success', 'Gambar berhasil dihapus!');
    }
}
