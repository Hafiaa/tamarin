@extends('layouts.app')

@section('title', $package->name)

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Event Packages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $package->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Details Section -->
    <section class="package-details-section py-5">
        <div class="container">
            <div class="row">
                <!-- Package Images -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="package-gallery">
                        <div class="main-image mb-3">
                            <img src="{{ $package->getFirstMediaUrl('cover_image') ?: asset('images/placeholder.jpg') }}" 
                                 class="img-fluid rounded" alt="{{ $package->name }}">
                        </div>
                        <div class="gallery-thumbnails row g-2">
                            @if($package->getMedia('gallery')->count() > 0)
                                @foreach($package->getMedia('gallery') as $media)
                                    <div class="col-3">
                                        <img src="{{ $media->getUrl() }}" class="img-fluid rounded" alt="{{ $package->name }}">
                                    </div>
                                @endforeach
                            @else
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="col-3">
                                        <img src="{{ asset('images/placeholder-' . $i . '.jpg') }}" class="img-fluid rounded" alt="Gallery Image">
                                    </div>
                                @endfor
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Package Info -->
                <div class="col-lg-6">
                    <div class="package-info">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">{{ $package->eventType->name }}</span>
                            <div class="package-price">
                                <span class="price-label">Starting from</span>
                                <span class="price-value">{{ number_format($package->base_price, 0, ',', '.') }} IDR</span>
                            </div>
                        </div>
                        <h1 class="package-title mb-3">{{ $package->name }}</h1>
                        <div class="package-description mb-4">
                            <p>{{ $package->description }}</p>
                        </div>
                        
                        <div class="package-highlights mb-4">
                            <h4 class="highlights-title mb-3">Package Highlights</h4>
                            <ul class="highlights-list">
                                @foreach($package->highlights ?? ['Venue setup and decoration', 'Professional event staff', 'Basic sound system', 'Customizable menu options'] as $highlight)
                                    <li><i class="fas fa-check-circle text-success me-2"></i> {{ $highlight }}</li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="package-capacity mb-4">
                            <h4 class="capacity-title mb-3">Capacity & Details</h4>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="capacity-item">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <span class="capacity-label">Capacity:</span>
                                        <span class="capacity-value">{{ $package->min_guests }} - {{ $package->max_guests }} guests</span>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="capacity-item">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span class="capacity-label">Duration:</span>
                                        <span class="capacity-value">{{ $package->duration ?? '4 hours' }}</span>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="capacity-item">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span class="capacity-label">Availability:</span>
                                        <span class="capacity-value">{{ $package->availability ?? 'All week' }}</span>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="capacity-item">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span class="capacity-label">Venue:</span>
                                        <span class="capacity-value">{{ $package->venue ?? 'Tamacafe Event Space' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="package-actions">
                            <a href="{{ route('reservations.create', $package->id) }}" class="btn btn-primary btn-lg me-2">Book This Package</a>
                            <a href="{{ route('company.contact') }}" class="btn btn-outline-primary btn-lg">Ask a Question</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Items Section -->
    <section class="service-items-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">What's Included</h2>
            
            <div class="row">
                @if($package->serviceItems->count() > 0)
                    @foreach($package->serviceItems as $serviceItem)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="service-item-card h-100 bg-white p-4 rounded shadow-sm">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="service-item-icon me-3">
                                        <i class="fas fa-{{ $serviceItem->icon ?? 'check' }} fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="service-item-title mb-0">{{ $serviceItem->name }}</h5>
                                </div>
                                <p class="service-item-description mb-2">{{ $serviceItem->description }}</p>
                                <div class="service-item-quantity">
                                    <span class="quantity-label">Quantity:</span>
                                    <span class="quantity-value">{{ $serviceItem->pivot->quantity ?? 1 }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <p class="mb-0">Service items details will be provided upon inquiry. Please contact us for more information.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Related Packages Section -->
    @if($relatedPackages->count() > 0)
        <section class="related-packages-section py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5">You May Also Like</h2>
                
                <div class="row">
                    @foreach($relatedPackages as $relatedPackage)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ $relatedPackage->getFirstMediaUrl('cover_image') ?: asset('images/placeholder.jpg') }}" 
                                     class="card-img-top" alt="{{ $relatedPackage->name }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary">{{ $relatedPackage->eventType->name }}</span>
                                        <span class="package-price">{{ number_format($relatedPackage->base_price, 0, ',', '.') }} IDR</span>
                                    </div>
                                    <h5 class="card-title">{{ $relatedPackage->name }}</h5>
                                    <p class="card-text">{{ Str::limit($relatedPackage->description, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                        <a href="{{ route('events.show', $relatedPackage->id) }}" class="btn btn-outline-primary">View Details</a>
                                        <a href="{{ route('reservations.create', $relatedPackage->id) }}" class="btn btn-primary">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h3 class="cta-title mb-2">Ready to Book Your Event?</h3>
                    <p class="cta-text mb-0">Contact us today to schedule your next memorable event at Tamacafe.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('reservations.create', $package->id) }}" class="btn btn-light btn-lg">Book Now</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .package-price {
        text-align: right;
    }
    
    .price-label {
        display: block;
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .price-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #28a745;
    }
    
    .highlights-list {
        list-style: none;
        padding-left: 0;
    }
    
    .highlights-list li {
        margin-bottom: 0.5rem;
    }
    
    .service-item-card {
        transition: all 0.3s ease;
    }
    
    .service-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .gallery-thumbnails img {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .gallery-thumbnails img:hover {
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Gallery image viewer functionality
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.gallery-thumbnails img');
        const mainImage = document.querySelector('.main-image img');
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    });
</script>
@endpush
