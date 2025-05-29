@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Our Gallery</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                        </ol>
                    </nav>
                    <p class="lead mt-3">Explore our space and past events through our photo gallery.</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/gallery-header.jpg') }}" alt="Gallery" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Tabs Section -->
    <section class="gallery-section py-5">
        <div class="container">
            <!-- Gallery Filter Tabs -->
            <ul class="nav nav-pills gallery-filter mb-5 justify-content-center" id="galleryTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All Photos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="venue-tab" data-bs-toggle="pill" data-bs-target="#venue" type="button" role="tab" aria-controls="venue" aria-selected="false">Our Venue</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="weddings-tab" data-bs-toggle="pill" data-bs-target="#weddings" type="button" role="tab" aria-controls="weddings" aria-selected="false">Weddings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="birthdays-tab" data-bs-toggle="pill" data-bs-target="#birthdays" type="button" role="tab" aria-controls="birthdays" aria-selected="false">Birthdays</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="corporate-tab" data-bs-toggle="pill" data-bs-target="#corporate" type="button" role="tab" aria-controls="corporate" aria-selected="false">Corporate Events</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="food-tab" data-bs-toggle="pill" data-bs-target="#food" type="button" role="tab" aria-controls="food" aria-selected="false">Food & Drinks</button>
                </li>
            </ul>

            <!-- Gallery Tab Content -->
            <div class="tab-content" id="galleryTabContent">
                <!-- All Photos Tab -->
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="row g-4 gallery-grid">
                        @for ($i = 1; $i <= 12; $i++)
                            <div class="col-md-4 col-lg-3 gallery-item">
                                <a href="{{ asset('images/gallery/gallery-' . $i . '.jpg') }}" class="gallery-link" data-lightbox="all-gallery" data-title="Gallery Image {{ $i }}">
                                    <div class="gallery-image-wrapper">
                                        <img src="{{ asset('images/gallery/gallery-' . $i . '.jpg') }}" alt="Gallery Image {{ $i }}" class="img-fluid rounded">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Venue Tab -->
                <div class="tab-pane fade" id="venue" role="tabpanel" aria-labelledby="venue-tab">
                    <div class="row g-4 gallery-grid">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-md-4 col-lg-3 gallery-item">
                                <a href="{{ asset('images/gallery/venue-' . $i . '.jpg') }}" class="gallery-link" data-lightbox="venue-gallery" data-title="Venue Image {{ $i }}">
                                    <div class="gallery-image-wrapper">
                                        <img src="{{ asset('images/gallery/venue-' . $i . '.jpg') }}" alt="Venue Image {{ $i }}" class="img-fluid rounded">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Weddings Tab -->
                <div class="tab-pane fade" id="weddings" role="tabpanel" aria-labelledby="weddings-tab">
                    <div class="row g-4 gallery-grid">
                        @for ($i = 1; $i <= 8; $i++)
                            <div class="col-md-4 col-lg-3 gallery-item">
                                <a href="{{ asset('images/gallery/wedding-' . $i . '.jpg') }}" class="gallery-link" data-lightbox="wedding-gallery" data-title="Wedding Image {{ $i }}">
                                    <div class="gallery-image-wrapper">
                                        <img src="{{ asset('images/gallery/wedding-' . $i . '.jpg') }}" alt="Wedding Image {{ $i }}" class="img-fluid rounded">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Birthdays Tab -->
                <div class="tab-pane fade" id="birthdays" role="tabpanel" aria-labelledby="birthdays-tab">
                    <div class="row g-4 gallery-grid">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-md-4 col-lg-3 gallery-item">
                                <a href="{{ asset('images/gallery/birthday-' . $i . '.jpg') }}" class="gallery-link" data-lightbox="birthday-gallery" data-title="Birthday Image {{ $i }}">
                                    <div class="gallery-image-wrapper">
                                        <img src="{{ asset('images/gallery/birthday-' . $i . '.jpg') }}" alt="Birthday Image {{ $i }}" class="img-fluid rounded">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Corporate Events Tab -->
                <div class="tab-pane fade" id="corporate" role="tabpanel" aria-labelledby="corporate-tab">
                    <div class="row g-4 gallery-grid">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-md-4 col-lg-3 gallery-item">
                                <a href="{{ asset('images/gallery/corporate-' . $i . '.jpg') }}" class="gallery-link" data-lightbox="corporate-gallery" data-title="Corporate Event Image {{ $i }}">
                                    <div class="gallery-image-wrapper">
                                        <img src="{{ asset('images/gallery/corporate-' . $i . '.jpg') }}" alt="Corporate Event Image {{ $i }}" class="img-fluid rounded">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Food & Drinks Tab -->
                <div class="tab-pane fade" id="food" role="tabpanel" aria-labelledby="food-tab">
                    <div class="row g-4 gallery-grid">
                        @for ($i = 1; $i <= 8; $i++)
                            <div class="col-md-4 col-lg-3 gallery-item">
                                <a href="{{ asset('images/gallery/food-' . $i . '.jpg') }}" class="gallery-link" data-lightbox="food-gallery" data-title="Food Image {{ $i }}">
                                    <div class="gallery-image-wrapper">
                                        <img src="{{ asset('images/gallery/food-' . $i . '.jpg') }}" alt="Food Image {{ $i }}" class="img-fluid rounded">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Gallery Section -->
    <section class="video-gallery-section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Video Gallery</h2>
                <p class="section-subtitle">Watch videos from our events and venue</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 mb-4">
                    <div class="video-card h-100 bg-white rounded shadow-sm overflow-hidden">
                        <div class="video-wrapper position-relative">
                            <img src="{{ asset('images/video-thumbnail-1.jpg') }}" alt="Venue Tour" class="img-fluid w-100">
                            <a href="#" class="video-play-button position-absolute top-50 start-50 translate-middle" data-bs-toggle="modal" data-bs-target="#videoModal1">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                        <div class="video-content p-3">
                            <h5 class="video-title">Tamacafe Venue Tour</h5>
                            <p class="video-description">Take a virtual tour of our beautiful venue and event spaces.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="video-card h-100 bg-white rounded shadow-sm overflow-hidden">
                        <div class="video-wrapper position-relative">
                            <img src="{{ asset('images/video-thumbnail-2.jpg') }}" alt="Wedding Highlights" class="img-fluid w-100">
                            <a href="#" class="video-play-button position-absolute top-50 start-50 translate-middle" data-bs-toggle="modal" data-bs-target="#videoModal2">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                        <div class="video-content p-3">
                            <h5 class="video-title">Wedding Highlights at Tamacafe</h5>
                            <p class="video-description">Highlights from beautiful weddings hosted at our venue.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modals -->
    <div class="modal fade" id="videoModal1" tabindex="-1" aria-labelledby="videoModal1Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModal1Label">Tamacafe Venue Tour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Tamacafe Venue Tour" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="videoModal2" tabindex="-1" aria-labelledby="videoModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModal2Label">Wedding Highlights at Tamacafe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Wedding Highlights at Tamacafe" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h3 class="cta-title mb-2">Want to Host Your Event at Tamacafe?</h3>
                    <p class="cta-text mb-0">Contact us today to schedule your next memorable event at our venue.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg">Book Now</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .gallery-filter .nav-link {
        color: #6c757d;
        border-radius: 30px;
        padding: 8px 20px;
        margin: 0 5px 10px;
        transition: all 0.3s ease;
    }
    
    .gallery-filter .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }
    
    .gallery-filter .nav-link:hover:not(.active) {
        background-color: #f8f9fa;
    }
    
    .gallery-image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 5px;
    }
    
    .gallery-image-wrapper img {
        transition: all 0.5s ease;
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 123, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .gallery-overlay i {
        color: #fff;
        font-size: 2rem;
    }
    
    .gallery-image-wrapper:hover img {
        transform: scale(1.1);
    }
    
    .gallery-image-wrapper:hover .gallery-overlay {
        opacity: 1;
    }
    
    .video-play-button {
        width: 60px;
        height: 60px;
        background-color: rgba(0, 123, 255, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .video-play-button:hover {
        background-color: #007bff;
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    .video-card {
        transition: all 0.3s ease;
    }
    
    .video-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize lightbox for gallery images
    document.addEventListener('DOMContentLoaded', function() {
        // If you're using a lightbox library like lightbox.js or fancybox,
        // you would initialize it here
    });
</script>
@endpush
