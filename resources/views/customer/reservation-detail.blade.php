@extends('layouts.app')

@section('title', 'Reservation Details')

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
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
                <h1 class="fw-bold mb-0">Reservation #{{ $reservation->id }}</h1>
                <p class="text-muted mb-0">{{ $reservation->eventType->name }} - {{ \Carbon\Carbon::parse($reservation->event_date)->format('M d, Y') }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge 
                    @if($reservation->status === \App\Models\Reservation::STATUS_PENDING) bg-warning 
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_APPROVED) bg-success 
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_COMPLETED) bg-info 
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_CANCELLED) bg-danger 
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_DECLINED) bg-secondary 
                    @endif 
                    p-2 fs-6 mb-2">
                    @if($reservation->status === \App\Models\Reservation::STATUS_PENDING)
                        Menunggu Konfirmasi Admin
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_APPROVED)
                        Dikonfirmasi
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_COMPLETED)
                        Selesai
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_CANCELLED)
                        Dibatalkan
                    @elseif($reservation->status === \App\Models\Reservation::STATUS_DECLINED)
                        Ditolak
                    @endif
                </span>
                <div class="mt-2">
                    @php
                        $paidAmount = $reservation->payments->where('status', 'approved')->sum('amount');
                        $remainingAmount = $reservation->total_price - $paidAmount;
                    @endphp
                    
                    @if($reservation->status === \App\Models\Reservation::STATUS_APPROVED && $remainingAmount > 0)
                        <a href="{{ route('customer.dashboard.payments.create', $reservation->id) }}" class="btn btn-success">
                            <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                        </a>
                    @endif
                    
                    @if($reservation->status === \App\Models\Reservation::STATUS_COMPLETED && !$reservation->testimonial)
                        <a href="{{ route('customer.dashboard.testimonials.create', $reservation->id) }}" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-star me-2"></i> Beri Ulasan
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reservation Details Content -->
<div class="container py-5">
    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <!-- Event Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
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
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Number of Guests</p>
                            <p class="fw-bold mb-0">{{ $reservation->guest_count }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Reservation Date</p>
                            <p class="fw-bold mb-0">{{ $reservation->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted mb-1">Special Requests</p>
                            <p class="mb-0">{{ $reservation->special_requests ?: 'No special requests' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Package Details</h5>
                </div>
                <div class="card-body">
                    @if($reservation->packageTemplate)
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ $reservation->packageTemplate->name }}</h6>
                            <p>{{ $reservation->packageTemplate->description }}</p>
                        </div>
                        
                        @if($reservation->packageTemplate->serviceItems->count() > 0)
                            <h6 class="fw-bold mb-3">Included Items</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reservation->packageTemplate->serviceItems as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->pivot->quantity }}</td>
                                                <td>{{ $item->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @elseif($reservation->customPackage)
                        <h6 class="fw-bold mb-3">Custom Package Items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($reservation->customPackage->items as $itemId => $quantity)
                                        @php 
                                            $item = \App\Models\ServiceItem::find($itemId);
                                            $subtotal = $item->price * $quantity;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $quantity }}</td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }} IDR</td>
                                            <td>{{ number_format($subtotal, 0, ',', '.') }} IDR</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th>{{ number_format($total, 0, ',', '.') }} IDR</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No package details available.</p>
                    @endif
                </div>
            </div>

            <!-- Payment History Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment History</h5>
                    @if($remainingAmount > 0 && $reservation->status != 'cancelled')
                        <a href="{{ route('customer.dashboard.payments.create', $reservation->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle me-1"></i> Make Payment
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($reservation->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservation->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td>{{ number_format($payment->amount, 0, ',', '.') }} IDR</td>
                                            <td>
                                                @if($payment->payment_method == 'bank_transfer')
                                                    <span><i class="fas fa-university me-1"></i> Bank Transfer</span>
                                                @elseif($payment->payment_method == 'credit_card')
                                                    <span><i class="fas fa-credit-card me-1"></i> Credit Card</span>
                                                @elseif($payment->payment_method == 'cash')
                                                    <span><i class="fas fa-money-bill-wave me-1"></i> Cash</span>
                                                @else
                                                    <span>{{ $payment->payment_method }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No payment records found.</p>
                    @endif
                </div>
            </div>

            <!-- Revision History Card -->
            @if($revisions->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Revision History</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($revisions as $revision)
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-primary"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <span class="timeline-date">{{ $revision->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        <div class="timeline-body">
                                            <h6 class="mb-2">{{ $revision->title }}</h6>
                                            <p class="mb-0">{{ $revision->description }}</p>
                                            
                                            @if($revision->changes)
                                                <div class="mt-2">
                                                    <p class="mb-1 fw-bold">Changes:</p>
                                                    <ul class="mb-0">
                                                        @foreach($revision->changes as $field => $change)
                                                            <li>
                                                                <strong>{{ ucfirst($field) }}:</strong> 
                                                                {{ $change['from'] }} → {{ $change['to'] }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Price:</span>
                        <span class="fw-bold">{{ number_format($reservation->total_price, 0, ',', '.') }} IDR</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Paid Amount:</span>
                        <span class="fw-bold text-success">{{ number_format($paidAmount, 0, ',', '.') }} IDR</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Remaining:</span>
                        <span class="fw-bold {{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($remainingAmount, 0, ',', '.') }} IDR
                        </span>
                    </div>
                    
                    <div class="progress mb-3" style="height: 10px;">
                        @php $paymentPercentage = $reservation->total_price > 0 ? ($paidAmount / $reservation->total_price) * 100 : 0; @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentPercentage }}%;" aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-center mb-0">
                        <small class="text-muted">{{ number_format($paymentPercentage, 0) }}% Paid</small>
                    </p>
                    
                    @if($remainingAmount > 0 && $reservation->status != 'cancelled')
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('customer.dashboard.payments.create', $reservation->id) }}" class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i> Make Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="fas fa-user me-2 text-primary"></i> {{ $reservation->user->name }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2 text-primary"></i> {{ $reservation->user->email }}
                    </p>
                    @if($reservation->user->phone)
                        <p class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i> {{ $reservation->user->phone }}
                        </p>
                    @endif
                    @if($reservation->user->address)
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $reservation->user->address }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.dashboard.reservations') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Reservations
                        </a>
                        
                        @if($reservation->status == 'completed')
                            <a href="{{ route('customer.dashboard.testimonials.create', $reservation->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-star me-2"></i> Leave Review
                            </a>
                        @endif
                        
                        <a href="{{ route('company.contact') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-headset me-2"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-dot {
        position: absolute;
        left: -30px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        top: 5px;
    }
    
    .timeline-content {
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .timeline-item:last-child .timeline-content {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .timeline-date {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
</style>
@endpush
