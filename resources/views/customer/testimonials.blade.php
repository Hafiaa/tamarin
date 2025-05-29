@extends('layouts.app')

@section('title', 'My Testimonials')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-0">My Testimonials</h1>
                <p class="text-muted mb-0">View and manage your reviews</p>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Content -->
<div class="container py-5">
    <!-- Testimonials List -->
    @if($testimonials->count() > 0)
        <div class="row">
            @foreach($testimonials as $testimonial)
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="testimonial-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span class="badge 
                                    @if($testimonial->status == 'pending') bg-warning 
                                    @elseif($testimonial->status == 'published') bg-success 
                                    @elseif($testimonial->status == 'rejected') bg-danger 
                                    @endif">
                                    {{ ucfirst($testimonial->status) }}
                                </span>
                            </div>
                            
                            <div class="testimonial-content mb-4">
                                <p class="mb-0">{{ $testimonial->content }}</p>
                            </div>
                            
                            <div class="testimonial-event">
                                <p class="mb-1 text-muted">
                                    <i class="fas fa-calendar-alt me-2"></i> 
                                    <a href="{{ route('customer.dashboard.reservations.show', $testimonial->reservation->id) }}" class="text-decoration-none">
                                        {{ $testimonial->reservation->eventType->name }} - 
                                        {{ \Carbon\Carbon::parse($testimonial->reservation->event_date)->format('M d, Y') }}
                                    </a>
                                </p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-clock me-2"></i> Submitted on {{ $testimonial->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        
                        @if($testimonial->status == 'pending')
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editTestimonialModal{{ $testimonial->id }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTestimonialModal{{ $testimonial->id }}">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Edit Testimonial Modal -->
                <div class="modal fade" id="editTestimonialModal{{ $testimonial->id }}" tabindex="-1" aria-labelledby="editTestimonialModalLabel{{ $testimonial->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTestimonialModalLabel{{ $testimonial->id }}">Edit Testimonial</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('customer.dashboard.testimonials.store', $testimonial->reservation->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="rating{{ $testimonial->id }}" class="form-label">Rating</label>
                                        <div class="rating-input">
                                            @for($i = 1; $i <= 5; $i++)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating{{ $testimonial->id }}_{{ $i }}" value="{{ $i }}" {{ $testimonial->rating == $i ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rating{{ $testimonial->id }}_{{ $i }}">{{ $i }}</label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="content{{ $testimonial->id }}" class="form-label">Review</label>
                                        <textarea class="form-control" id="content{{ $testimonial->id }}" name="content" rows="5" required>{{ $testimonial->content }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Testimonial</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Testimonial Modal -->
                <div class="modal fade" id="deleteTestimonialModal{{ $testimonial->id }}" tabindex="-1" aria-labelledby="deleteTestimonialModalLabel{{ $testimonial->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteTestimonialModalLabel{{ $testimonial->id }}">Delete Testimonial</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this testimonial? This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('customer.dashboard.testimonials.store', $testimonial->reservation->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $testimonials->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <img src="{{ asset('images/no-testimonials.svg') }}" alt="No Testimonials" class="img-fluid mb-3" style="max-width: 200px;">
            <h4>No Testimonials Found</h4>
            <p class="text-muted">You haven't submitted any reviews yet.</p>
            <a href="{{ route('customer.dashboard.reservations') }}" class="btn btn-primary">View Completed Events</a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .testimonial-rating {
        font-size: 1.25rem;
    }
</style>
@endpush
