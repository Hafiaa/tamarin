@extends('layouts.app')

@section('title', 'My Reservations')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-0">My Reservations</h1>
                <p class="text-muted mb-0">View and manage your event reservations</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('events.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Book New Event
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reservations Content -->
<div class="container py-5">
    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4" id="reservationsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="upcoming-tab" data-bs-toggle="pill" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">Upcoming</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-tab" data-bs-toggle="pill" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">Past</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cancelled-tab" data-bs-toggle="pill" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
        </li>
    </ul>

    <!-- Reservations List -->
    <div class="tab-content" id="reservationsTabsContent">
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            @if($reservations->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Date & Time</th>
                                        <th>Guests</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            <td>
                                                <strong>{{ $reservation->eventType->name }}</strong>
                                                @if($reservation->packageTemplate)
                                                    <br>
                                                    <small class="text-muted">{{ $reservation->packageTemplate->name }}</small>
                                                @elseif($reservation->customPackageItems->count() > 0)
                                                    <br>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-purple-100 text-purple-800 me-2">Custom</span>
                                                        <small class="text-muted">
                                                            {{ $reservation->customPackageItems->count() }} service{{ $reservation->customPackageItems->count() > 1 ? 's' : '' }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($reservation->event_date)->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $reservation->event_time }}</small>
                                            </td>
                                            <td>{{ $reservation->guest_count }}</td>
                                            <td>
                                                @if($reservation->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($reservation->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($reservation->status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @elseif($reservation->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $paidAmount = $reservation->payments->where('status', 'approved')->sum('amount');
                                                    $paymentPercentage = $reservation->total_price > 0 ? ($paidAmount / $reservation->total_price) * 100 : 0;
                                                @endphp
                                                @if($reservation->total_price > 0)
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentPercentage }}%;" aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="d-block mt-1">
                                                        {{ number_format($paidAmount, 0, ',', '.') }} / {{ number_format($reservation->total_price, 0, ',', '.') }} IDR
                                                    </small>
                                                @else
                                                    <span class="badge bg-secondary">No payment required</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('customer.dashboard.reservations.show', $reservation->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($paidAmount < $reservation->total_price && $reservation->status != 'cancelled')
                                                            <li><a class="dropdown-item" href="{{ route('customer.dashboard.payments.create', $reservation->id) }}">Make Payment</a></li>
                                                        @endif
                                                        @if($reservation->status == 'completed')
                                                            <li><a class="dropdown-item" href="{{ route('customer.dashboard.testimonials.create', $reservation->id) }}">Leave Review</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reservations->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/no-events.svg') }}" alt="No Reservations" class="img-fluid mb-3" style="max-width: 200px;">
                    <h4>No Reservations Found</h4>
                    <p class="text-muted">You haven't made any reservations yet.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary">Book an Event</a>
                </div>
            @endif
        </div>
        
        <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <!-- Content for upcoming reservations will be loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading upcoming reservations...</p>
            </div>
        </div>
        
        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
            <!-- Content for past reservations will be loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading past reservations...</p>
            </div>
        </div>
        
        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
            <!-- Content for cancelled reservations will be loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading cancelled reservations...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // This script would handle loading the different tabs via AJAX
    // For now, we'll just simulate the tabs with static content
    document.addEventListener('DOMContentLoaded', function() {
        // Example of how you would implement this with real AJAX
        /*
        const tabs = document.querySelectorAll('#reservationsTabs button');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target').replace('#', '');
                if (target !== 'all') {
                    fetch(`/dashboard/reservations/filter/${target}`)
                        .then(response => response.html())
                        .then(html => {
                            document.querySelector(`#${target}`).innerHTML = html;
                        });
                }
            });
        });
        */
    });
</script>
@endpush
