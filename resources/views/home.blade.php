@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section position-relative">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/hero-1.jpg') }}" class="d-block w-100" alt="Tamacafe Event Space">
                    <div class="carousel-caption">
                        <h1>Selamat Datang di Tamacafe</h1>
                        <p>Tempat sempurna untuk acara dan pertemuan yang tak terlupakan</p>
                        <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg">Jelajahi Acara</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/hero-2.jpg') }}" class="d-block w-100" alt="Tamacafe Cafe">
                    <div class="carousel-caption">
                        <h1>Makanan & Minuman Lezat</h1>
                        <p>Nikmati menu istimewa kami yang dibuat dengan penuh cinta</p>
                        <a href="{{ route('menu.cafe') }}" class="btn btn-primary btn-lg">Lihat Menu</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/hero-3.jpg') }}" class="d-block w-100" alt="Tamacafe Events">
                    <div class="carousel-caption">
                        <h1>Buat Kenangan Tak Terlupakan</h1>
                        <p>Pesan acara spesial Anda bersama kami hari ini</p>
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Selanjutnya</span>
            </button>
        </div>
    </section>

    <!-- Announcements Section -->
    @if($announcements->count() > 0)
        <section class="announcements-section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="announcements-slider">
                            @foreach($announcements as $announcement)
                                <div class="announcement-card bg-light p-4 rounded shadow-sm">
                                    <h5 class="announcement-title">{{ $announcement->title }}</h5>
                                    <p class="announcement-content">{{ $announcement->content }}</p>
                                    @if($announcement->link_url)
                                        <a href="{{ $announcement->link_url }}" class="btn btn-sm btn-outline-primary">
                                            {{ $announcement->link_text ?? 'Learn More' }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Paket Event Unggulan -->
    <section class="featured-packages-section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Paket Event Unggulan</h2>
                <p class="section-subtitle">Temukan paket event terbaik kami</p>
            </div>
            @if($featuredPackages->count() > 0)
                <div class="row">
                    @foreach($featuredPackages as $package)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="position-relative">
                                    <img src="{{ $package->getFirstMediaUrl('cover_image') ?: asset('images/placeholder.jpg') }}" 
                                         class="card-img-top" 
                                         alt="{{ $package->name }}"
                                         style="height: 200px; object-fit: cover;">
                                    @if($package->is_featured)
                                        <span class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded">
                                            <i class="fas fa-star me-1"></i> Unggulan
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary" style="background-color: #b9c24b !important;">{{ $package->eventType->name ?? 'Umum' }}</span>
                                        <span class="text-primary fw-bold">
                                            Mulai {{ number_format($package->base_price, 0, ',', '.') }} IDR
                                        </span>
                                    </div>
                                    <h5 class="card-title fw-bold">{{ $package->name }}</h5>
                                    <p class="card-text text-muted">
                                        {{ Str::limit(strip_tags($package->description), 120) }}
                                    </p>
                                    <div class="mt-3">
                                        <div class="d-flex align-items-center text-muted small mb-2">
                                            <i class="fas fa-users me-2"></i>
                                            <span>Maks. {{ $package->max_people }} orang</span>
                                        </div>
                                        @if($package->discount > 0)
                                            <div class="d-flex align-items-center text-danger small">
                                                <i class="fas fa-tag me-2"></i>
                                                <span>Diskon {{ $package->discount }}%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('events.show', $package->slug) }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           style="color: #b9c24b !important; border-color: #b9c24b !important;">
                                            <i class="fas fa-eye me-1"></i> Lihat Detail
                                        </a>
                                        <a href="{{ route('reservations.create', $package->id) }}" 
                                           class="btn btn-primary btn-sm"
                                           style="background-color: #b9c24b !important; border-color: #b9c24b !important;">
                                            <i class="fas fa-calendar-check me-1"></i> Pesan Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary" 
                       style="color: #b9c24b !important; border-color: #b9c24b !important;">
                        <i class="fas fa-list me-1"></i> Lihat Semua Paket
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Belum ada paket event tersedia saat ini</h5>
                    <p class="text-muted">Silakan kembali lagi nanti untuk melihat penawaran terbaru kami.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Why Choose Tamacafe</h2>
                <p class="section-subtitle">We're committed to making your events special</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-utensils fa-3x text-primary"></i>
                        </div>
                        <h4 class="feature-title">Delicious Food</h4>
                        <p class="feature-text">Our expert chefs prepare mouthwatering dishes using only the freshest ingredients.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                        </div>
                        <h4 class="feature-title">Perfect Location</h4>
                        <p class="feature-text">Conveniently located with a beautiful ambiance to host your special events.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-headset fa-3x text-primary"></i>
                        </div>
                        <h4 class="feature-title">Exceptional Service</h4>
                        <p class="feature-text">Our dedicated team ensures your event runs smoothly from start to finish.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section
    @if($testimonials->count() > 0)
        <section class="testimonials-section py-5 bg-light">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2 class="section-title">What Our Customers Say</h2>
                    <p class="section-subtitle">Read testimonials from our satisfied clients</p>
                </div>
                <div class="row">
                    @foreach($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="testimonial-card h-100 bg-white p-4 rounded shadow-sm">
                                <div class="testimonial-rating mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p class="testimonial-content mb-4">"{{ Str::limit($testimonial->content, 150) }}"</p>
                                <div class="testimonial-author d-flex align-items-center">
                                    <div class="author-avatar me-3">
                                        <img src="{{ $testimonial->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.jpg') }}" alt="{{ $testimonial->user->name }}" class="rounded-circle" width="50" height="50">
                                    </div>
                                    <div class="author-info">
                                        <h6 class="author-name mb-0">{{ $testimonial->user->name }}</h6>
                                        <p class="author-event mb-0 text-muted small">{{ $testimonial->reservation->eventType->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif -->

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h3 class="cta-title mb-2">Ready to Book Your Event?</h3>
                    <p class="cta-text mb-0">Contact us today to schedule your next memorable event at Tamacafe.</p>
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
    .hero-section {
        height: 600px;
        overflow: hidden;
    }
    
    .carousel-item {
        height: 600px;
    }
    
    .carousel-item img {
        object-fit: cover;
        height: 100%;
        filter: brightness(0.7);
    }
    
    .carousel-caption {
        top: 50%;
        transform: translateY(-50%);
        bottom: auto;
    }
    
    .feature-card {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .testimonial-card {
        transition: all 0.3s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize announcements slider if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript for sliders or other interactive elements here
    });
</script>
@endpush
