@extends('layouts.app')

@section('title', 'Event Menu')

@push('styles')
<style>
    .menu-category {
        margin-bottom: 3rem;
    }
    .menu-category h2 {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .menu-item {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .menu-item:hover {
        transform: translateY(-5px);
    }
    .menu-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .menu-item-content {
        padding: 1.5rem;
    }
    .menu-item h3 {
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 0.5rem;
    }
    .menu-item .price {
        color: #e74c3c;
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    .menu-item p {
        color: #7f8c8d;
        margin-bottom: 0;
    }
    .category-filter {
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .category-filter .btn {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .debug-info {
        background: #fff3cd;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">Event Menu</h1>
            <p class="lead">Special menu for your events and gatherings</p>
        </div>
    </div>

    @if(isset($debug))
    <div class="debug-info">
        <h5>Debug Info:</h5>
        <ul class="mb-0">
            <li>Jumlah Kategori: {{ $debug['categories_count'] }}</li>
            <li>Jumlah Menu Item: {{ $debug['menu_items_count'] }}</li>
            <li>Memiliki Kategori 'Event Specials': {{ $debug['has_event_specials'] ? 'Ya' : 'Tidak' }}</li>
        </ul>
    </div>
    @endif
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Event Menu</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Event Menu</li>
                        </ol>
                    </nav>
                    <p class="lead mt-3">Explore our catering options for your special events at Tamacafe.</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/event-menu-header.jpg') }}" alt="Event Menu" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Section -->
    <div class="container">
        <!-- Category Filter -->
        <div class="category-filter">
            <h5 class="mb-3">Filter by Category:</h5>
            <div class="d-flex flex-wrap">
                <a href="{{ route('menu.event') }}" class="btn {{ !$categoryId ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All Items
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu.event', ['category' => $category->id]) }}" 
                       class="btn {{ $categoryId == $category->id ? 'btn-primary' : 'btn-outline-secondary' }} ms-2">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Menu Items -->
        <div class="menu-items">
            @if($menuItems->count() > 0)
                @foreach($menuItems->groupBy('menuCategory.name') as $categoryName => $items)
                    <div class="menu-category">
                        <h2>{{ $categoryName }}</h2>
                        <div class="row">
                            @foreach($items as $item)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="menu-item h-100">
                                        @if($item->getFirstMediaUrl('image'))
                                            <img src="{{ $item->getFirstMediaUrl('image') }}" alt="{{ $item->name }}" class="img-fluid">
                                        @else
                                            <div style="background: #eee; height: 200px; display: flex; align-items: center; justify-content: center;">
                                                <span>No Image</span>
                                            </div>
                                        @endif
                                        <div class="menu-item-content">
                                            <h3>{{ $item->name }}</h3>
                                            <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                            <p>{{ $item->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <p class="mb-0">No menu items found for this category. Please check back later or try a different category.</p>
                </div>
            @endif
        </div>
    </div>
    <!-- End Menu Section -->

    <!-- Catering Packages Section -->
    <section class="py-5 bg-light mt-5">
        <div class="container">
            <h2 class="text-center mb-5">Catering Packages</h2>
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="package-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="package-icon mb-3">
                            <i class="fas fa-utensils fa-3x text-primary"></i>
                        </div>
                        <h4 class="package-title">Basic Package</h4>
                        <p class="package-price mb-3">IDR 150,000 / person</p>
                        <ul class="package-features list-unstyled mb-4">
                            <li><i class="fas fa-check text-success me-2"></i> 3 appetizers</li>
                            <li><i class="fas fa-check text-success me-2"></i> 2 main courses</li>
                            <li><i class="fas fa-check text-success me-2"></i> 1 dessert</li>
                            <li><i class="fas fa-check text-success me-2"></i> Soft drinks</li>
                        </ul>
                        <a href="{{ route('reservations.create') }}" class="btn btn-outline-primary">Book This Package</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="package-card text-center p-4 bg-white rounded shadow-sm h-100 featured">
                        <div class="package-badge position-absolute top-0 start-50 translate-middle">
                            <span class="badge bg-primary">Most Popular</span>
                        </div>
                        <div class="package-icon mb-3">
                            <i class="fas fa-crown fa-3x text-primary"></i>
                        </div>
                        <h4 class="package-title">Premium Package</h4>
                        <p class="package-price mb-3">IDR 250,000 / person</p>
                        <ul class="package-features list-unstyled mb-4">
                            <li><i class="fas fa-check text-success me-2"></i> 5 appetizers</li>
                            <li><i class="fas fa-check text-success me-2"></i> 3 main courses</li>
                            <li><i class="fas fa-check text-success me-2"></i> 2 desserts</li>
                            <li><i class="fas fa-check text-success me-2"></i> Soft drinks & juices</li>
                            <li><i class="fas fa-check text-success me-2"></i> Coffee & tea station</li>
                        </ul>
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary">Book This Package</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="package-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="package-icon mb-3">
                            <i class="fas fa-gem fa-3x text-primary"></i>
                        </div>
                        <h4 class="package-title">Luxury Package</h4>
                        <p class="package-price mb-3">IDR 350,000 / person</p>
                        <ul class="package-features list-unstyled mb-4">
                            <li><i class="fas fa-check text-success me-2"></i> 7 appetizers</li>
                            <li><i class="fas fa-check text-success me-2"></i> 4 main courses</li>
                            <li><i class="fas fa-check text-success me-2"></i> 3 desserts</li>
                            <li><i class="fas fa-check text-success me-2"></i> Premium beverages</li>
                            <li><i class="fas fa-check text-success me-2"></i> Coffee & tea station</li>
                            <li><i class="fas fa-check text-success me-2"></i> Chocolate fountain</li>
                        </ul>
                        <a href="{{ route('reservations.create') }}" class="btn btn-outline-primary">Book This Package</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="mb-2">Looking for a custom menu for your event?</p>
                <a href="{{ route('company.contact') }}" class="btn btn-outline-primary">Contact Us for Custom Options</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">What Our Clients Say</h2>
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="testimonial-card p-4 bg-white rounded shadow-sm h-100">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-content mb-4">"The food at our wedding was absolutely amazing! Our guests couldn't stop raving about the quality and presentation. Tamacafe's catering service exceeded our expectations."</p>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="author-avatar me-3">
                                <img src="{{ asset('images/testimonial-1.jpg') }}" alt="Sarah & David" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="author-info">
                                <h6 class="author-name mb-0">Sarah & David</h6>
                                <p class="author-event mb-0 text-muted small">Wedding Reception</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="testimonial-card p-4 bg-white rounded shadow-sm h-100">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-content mb-4">"We hired Tamacafe for our company's annual dinner, and they delivered a fantastic experience. The menu was diverse, the food was delicious, and the service was impeccable. Highly recommended!"</p>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="author-avatar me-3">
                                <img src="{{ asset('images/testimonial-2.jpg') }}" alt="Michael Chen" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="author-info">
                                <h6 class="author-name mb-0">Michael Chen</h6>
                                <p class="author-event mb-0 text-muted small">Corporate Event</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card p-4 bg-white rounded shadow-sm h-100">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <p class="testimonial-content mb-4">"The birthday party catering from Tamacafe was perfect! The food was fresh, tasty, and beautifully presented. The staff was professional and accommodating to our dietary requirements."</p>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="author-avatar me-3">
                                <img src="{{ asset('images/testimonial-3.jpg') }}" alt="Jessica Williams" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="author-info">
                                <h6 class="author-name mb-0">Jessica Williams</h6>
                                <p class="author-event mb-0 text-muted small">Birthday Celebration</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h3 class="cta-title mb-2">Ready to Plan Your Event?</h3>
                    <p class="cta-text mb-0">Contact us today to discuss your catering needs and create a custom menu for your special occasion.</p>
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
    .menu-item-card {
        transition: all 0.3s ease;
        background-color: #fff;
    }
    
    .menu-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .menu-item-price {
        font-weight: 600;
    }
    
    .category-title {
        position: relative;
        padding-bottom: 10px;
    }
    
    .category-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: #007bff;
    }
    
    .package-card {
        transition: all 0.3s ease;
        position: relative;
        margin-top: 20px;
    }
    
    .package-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .package-card.featured {
        border: 2px solid #007bff;
        z-index: 1;
    }
    
    .package-price {
        font-size: 1.25rem;
        font-weight: 600;
        color: #28a745;
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
