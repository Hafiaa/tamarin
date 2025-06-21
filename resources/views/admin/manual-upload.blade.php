@extends('layouts.app')

@section('title', 'Upload Gambar Manual')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Upload Gambar Manual - {{ $package->name }}</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Featured Image Upload -->
                    <div class="mb-5">
                        <h5>Foto Utama</h5>
                        <div class="row">
                            <div class="col-md-6">
                                @if($package->hasMedia('featured_image'))
                                    <div class="mb-3">
                                        <img src="{{ $package->getFirstMedia('featured_image')->getUrl('preview') }}" 
                                             class="img-fluid rounded border" 
                                             alt="Featured Image"
                                             style="max-height: 300px; width: auto;">
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        Belum ada foto utama yang diunggah.
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('admin.upload.featured', $package->id) }}" 
                                      method="POST" 
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="featured_image" class="form-label">Pilih Foto Utama</label>
                                        <input type="file" class="form-control" id="featured_image" name="featured_image" required>
                                        <div class="form-text">Format: JPG, PNG, GIF, atau WebP. Maksimal 5MB.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload Foto Utama
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Gallery Images Upload -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Galeri Foto</h5>
                            <span class="badge bg-primary">
                                {{ $package->getMedia('gallery')->count() }} Gambar
                            </span>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            @foreach($package->getMedia('gallery') as $media)
                                <div class="col-md-3">
                                    <div class="card h-100">
                                        <img src="{{ $media->getUrl('thumb') }}" 
                                             class="card-img-top" 
                                             alt="Gallery Image">
                                        <div class="card-footer p-2 text-center">
                                            <form action="{{ route('admin.delete.image', $media->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('admin.upload.gallery', $package->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="gallery_images" class="form-label">Tambah Gambar ke Galeri</label>
                                <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple>
                                <div class="form-text">Pilih satu atau beberapa gambar. Format: JPG, PNG, GIF, atau WebP. Maksimal 5MB per gambar.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-images me-1"></i> Upload ke Galeri
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('filament.admin.resources.package-templates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
