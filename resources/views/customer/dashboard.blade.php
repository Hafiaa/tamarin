@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-0">Welcome, {{ $user->name }}</h1>
                <p class="text-muted mb-0">Manage your reservations and account</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Book New Event
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="container py-5">
    <!-- Stats Overview -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex">
                    <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Upcoming Events</h5>
                        <p class="card-text display-6 fw-bold">{{ $upcomingReservations->count() }}</p>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('customer.dashboard.reservations') }}" class="text-decoration-none">View all reservations <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex">
                    <div class="icon-box bg-warning text-white rounded-circle p-3 me-3">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Pending Payments</h5>
                        <p class="card-text display-6 fw-bold">{{ $pendingPayments }}</p>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('customer.dashboard.payments') }}" class="text-decoration-none">View all payments <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex">
                    <div class="icon-box bg-success text-white rounded-circle p-3 me-3">
                        <i class="fas fa-user-edit fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Account</h5>
                        <p class="card-text">Manage your profile</p>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('customer.dashboard.profile.edit') }}" class="text-decoration-none">Edit profile <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Reservations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Upcoming Reservations</h5>
                </div>
                <div class="card-body">
                    @if($upcomingReservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingReservations as $reservation)
                                        <tr>
                                            <td>
                                                <strong>{{ $reservation->eventType->name }}</strong>
                                                @if($reservation->packageTemplate)
                                                    <br>
                                                    <small class="text-muted">{{ $reservation->packageTemplate->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($reservation->event_date)->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $reservation->event_time }}</small>
                                            </td>
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
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentPercentage }}%;" aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="d-block mt-1">{{ number_format($paidAmount, 0, ',', '.') }} / {{ number_format($reservation->total_price, 0, ',', '.') }} IDR</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('customer.dashboard.reservations.show', $reservation->id) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                                @if($paidAmount < $reservation->total_price)
                                                    <a href="{{ route('customer.dashboard.payments.create', $reservation->id) }}" class="btn btn-sm btn-success">Pay</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <img src="{{ asset('images/no-events.svg') }}" alt="No Events" class="img-fluid mb-3" style="max-width: 200px;">
                            <h5>No Upcoming Reservations</h5>
                            <p class="text-muted">You don't have any upcoming events scheduled.</p>
                            <a href="{{ route('reservations.create') }}" class="btn btn-primary">Book an Event</a>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('customer.dashboard.reservations') }}" class="text-decoration-none">View all reservations <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('events.index') }}" class="text-decoration-none">
                                <div class="quick-link-card p-3 border rounded text-center h-100">
                                    <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-0">Explore Events</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('menu.event') }}" class="text-decoration-none">
                                <div class="quick-link-card p-3 border rounded text-center h-100">
                                    <i class="fas fa-utensils fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-0">Event Menu</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('customer.dashboard.testimonials') }}" class="text-decoration-none">
                                <div class="quick-link-card p-3 border rounded text-center h-100">
                                    <i class="fas fa-star fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-0">My Testimonials</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('company.contact') }}" class="text-decoration-none">
                                <div class="quick-link-card p-3 border rounded text-center h-100">
                                    <i class="fas fa-headset fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-0">Contact Support</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quick-link-card {
        transition: all 0.3s ease;
    }
    
    .quick-link-card:hover {
        background-color: #f8f9fa;
        transform: translateY(-5px);
    }
</style>
@endpush
