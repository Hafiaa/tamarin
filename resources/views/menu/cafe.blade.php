@extends('layouts.app')

@section('title', 'Cafe Menu')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Cafe Menu</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cafe Menu</li>
                        </ol>
                    </nav>
                    <p class="lead mt-3">Discover our delicious selection of food and beverages available at Tamacafe.</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/cafe-menu-header.jpg') }}" alt="Cafe Menu" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Section -->
    <section class="menu-section py-5">
        <div class="container">
            <!-- Category Filter -->
            <div class="menu-filter mb-5">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="filter-title mb-3">Browse by Category</h5>
                        <div class="btn-group flex-wrap" role="group" aria-label="Menu category filter">
                            <a href="{{ route('menu.cafe') }}" class="btn {{ !$categoryId ? 'btn-primary' : 'btn-outline-primary' }} me-2 mb-2">
                                All Items
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('menu.cafe', ['category' => $category->id]) }}" 
                                   class="btn {{ $categoryId == $category->id ? 'btn-primary' : 'btn-outline-primary' }} me-2 mb-2">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="menu-items">
                @forelse($menuItems->groupBy('menuCategory.name') as $categoryName => $items)
                    <div class="menu-category mb-5">
                        <h3 class="category-title mb-4">{{ $categoryName }}</h3>
                        <div class="row">
                            @foreach($items as $item)
                                <div class="col-md-6 mb-4">
                                    <div class="menu-item-card h-100 p-3 border rounded shadow-sm">
                                        <div class="row g-0">
                                            <div class="col-4">
                                                <img src="{{ $item->getFirstMediaUrl('image') ?: asset('images/menu-placeholder.jpg') }}" 
                                                     class="img-fluid rounded" alt="{{ $item->name }}">
                                            </div>
                                            <div class="col-8 ps-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h5 class="menu-item-name mb-1">{{ $item->name }}</h5>
                                                    <span class="menu-item-price badge bg-success">{{ number_format($item->price, 0, ',', '.') }} IDR</span>
                                                </div>
                                                <p class="menu-item-description mb-2">{{ $item->description }}</p>
                                                @if($item->dietary_info)
                                                    <div class="dietary-info">
                                                        @foreach(explode(',', $item->dietary_info) as $info)
                                                            <span class="badge bg-light text-dark me-1">{{ trim($info) }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <p class="mb-0">No menu items found. Please try a different category or check back later.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Special Offers Section -->
    <section class="special-offers-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Special Offers</h2>
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="special-offer-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="offer-icon mb-3">
                            <i class="fas fa-coffee fa-3x text-primary"></i>
                        </div>
                        <h4 class="offer-title">Happy Hour</h4>
                        <p class="offer-description">Enjoy 20% off all beverages every weekday from 2 PM to 5 PM.</p>
                        <p class="offer-period mb-0">Valid: Monday - Friday</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="special-offer-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="offer-icon mb-3">
                            <i class="fas fa-birthday-cake fa-3x text-primary"></i>
                        </div>
                        <h4 class="offer-title">Birthday Special</h4>
                        <p class="offer-description">Free dessert for the birthday person with a minimum order of 2 main courses.</p>
                        <p class="offer-period mb-0">Valid: Everyday (ID required)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="special-offer-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="offer-icon mb-3">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h4 class="offer-title">Group Discount</h4>
                        <p class="offer-description">10% off for groups of 6 or more people dining together.</p>
                        <p class="offer-period mb-0">Valid: Everyday</p>
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
                    <h3 class="cta-title mb-2">Planning an Event?</h3>
                    <p class="cta-text mb-0">Check out our event menu options and packages for your next gathering.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('menu.event') }}" class="btn btn-light btn-lg">View Event Menu</a>
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
    
    .special-offer-card {
        transition: all 0.3s ease;
    }
    
    .special-offer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
</style>
@endpush
