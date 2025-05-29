@extends('layouts.app')

@section('title', 'Leave a Review')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard.reservations') }}">Reservations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leave a Review</li>
                    </ol>
                </nav>
                <h1 class="fw-bold mb-0">Leave a Review</h1>
                <p class="text-muted mb-0">Share your experience with us</p>
            </div>
        </div>
    </div>
</div>

<!-- Create Testimonial Content -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="reservation-summary mb-4">
                        <h5 class="card-title mb-3">Event Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1">Event Type</p>
                                <p class="fw-bold mb-0">{{ $reservation->eventType->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1">Package</p>
                                <p class="fw-bold mb-0">
                                    @if($reservation->packageTemplate)
                                        {{ $reservation->packageTemplate->name }}
                                    @else
                                        Custom Package
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1">Event Date</p>
                                <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($reservation->event_date)->format('F d, Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1">Event Time</p>
                                <p class="fw-bold mb-0">{{ $reservation->event_time }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="card-title mb-3">Your Review</h5>
                    <form action="{{ route('customer.dashboard.testimonials.store', $reservation->id) }}" method="POST">
                        @csrf
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <label class="form-label">Rating</label>
                            <div class="rating-stars mb-2">
                                <div class="d-flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="star-rating me-2">
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                            <label for="star{{ $i }}" class="star-label">
                                                <i class="far fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label">Your Experience</label>
                            <textarea class="form-control" id="content" name="content" rows="5" placeholder="Tell us about your experience..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Your review will be visible to other customers after approval.</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_photos" name="allow_photos" value="1" {{ old('allow_photos') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_photos">
                                    I allow Tamacafe to use my event photos for promotional purposes
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                            <a href="{{ route('customer.dashboard.reservations.show', $reservation->id) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rating-stars .star-rating {
        position: relative;
        display: inline-block;
        font-size: 2rem;
    }
    
    .rating-stars .star-rating input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .rating-stars .star-label {
        cursor: pointer;
        color: #ddd;
    }
    
    .rating-stars .star-label .fas {
        display: none;
    }
    
    .rating-stars .star-rating input:checked ~ .star-label .far {
        display: none;
    }
    
    .rating-stars .star-rating input:checked ~ .star-label .fas {
        display: inline-block;
        color: #ffb700;
    }
    
    .rating-stars .star-rating:hover .star-label .far {
        display: none;
    }
    
    .rating-stars .star-rating:hover .star-label .fas {
        display: inline-block;
        color: #ffb700;
    }
    
    .rating-stars .star-rating input:checked ~ .star-label:hover .far,
    .rating-stars .star-rating:hover ~ .star-rating .star-label .far {
        display: inline-block;
    }
    
    .rating-stars .star-rating input:checked ~ .star-label:hover .fas,
    .rating-stars .star-rating:hover ~ .star-rating .star-label .fas {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-rating');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', () => {
                // Fill in this star and all previous stars
                for (let i = 0; i <= index; i++) {
                    stars[i].querySelector('.far').style.display = 'none';
                    stars[i].querySelector('.fas').style.display = 'inline-block';
                    stars[i].querySelector('.fas').style.color = '#ffb700';
                }
                
                // Empty all following stars
                for (let i = index + 1; i < stars.length; i++) {
                    stars[i].querySelector('.far').style.display = 'inline-block';
                    stars[i].querySelector('.fas').style.display = 'none';
                }
            });
        });
        
        // Reset to checked state when mouse leaves the container
        document.querySelector('.rating-stars').addEventListener('mouseleave', () => {
            stars.forEach((star) => {
                const input = star.querySelector('input');
                if (input.checked) {
                    star.querySelector('.far').style.display = 'none';
                    star.querySelector('.fas').style.display = 'inline-block';
                } else {
                    star.querySelector('.far').style.display = 'inline-block';
                    star.querySelector('.fas').style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
