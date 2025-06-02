@extends('layouts.app')

@section('title', 'Reservation Details')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('customer.dashboard.reservations') }}" class="text-decoration-none d-inline-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Back to Reservations
        </a>
    </div>
    
    <!-- Reservation Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                @if($reservation->packageTemplate)
                    {{ $reservation->packageTemplate->name }}
                @else
                    Custom Package
                @endif
                <span class="badge ms-2 {{ 
                    $reservation->status == 'confirmed' ? 'bg-success' : 
                    ($reservation->status == 'pending' ? 'bg-warning' : 
                    ($reservation->status == 'cancelled' ? 'bg-danger' : 'bg-info')) 
                }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </h1>
            <p class="text-muted mb-0">
                {{ $reservation->eventType->name }} • 
                {{ \Carbon\Carbon::parse($reservation->event_date)->format('l, F j, Y') }} at {{ $reservation->event_time }}
            </p>
        </div>
        <div class="text-end">
            <div class="h4 mb-1">
                {{ number_format($reservation->total_price, 0, ',', '.') }} IDR
            </div>
            <div class="text-muted small">
                @php
                    $paidAmount = $reservation->payments->where('status', 'approved')->sum('amount');
                    $paymentPercentage = $reservation->total_price > 0 ? ($paidAmount / $reservation->total_price) * 100 : 100;
                @endphp
                {{ number_format($paidAmount, 0, ',', '.') }} IDR paid ({{ round($paymentPercentage) }}%)
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            @if($reservation->customPackageItems->count() > 0)
                <!-- Custom Package Services -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Selected Services</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($reservation->customPackageItems as $item)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $item->serviceItem->name }}</h6>
                                            @if($item->notes)
                                                <p class="text-muted small mb-2">{{ $item->notes }}</p>
                                            @endif
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark me-2">
                                                    {{ $item->quantity }} × {{ number_format($item->unit_price, 0, ',', '.') }} IDR
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="h6 mb-0">
                                                {{ number_format($item->total_price, 0, ',', '.') }} IDR
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Event Details -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small mb-1">Event Type</h6>
                            <p class="mb-0">{{ $reservation->eventType->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small mb-1">Date & Time</h6>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($reservation->event_date)->format('l, F j, Y') }}<br>
                                {{ $reservation->event_time }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small mb-1">Number of Guests</h6>
                            <p class="mb-0">{{ $reservation->guest_count }}</p>
                        </div>
                        @if($reservation->bride_name || $reservation->groom_name)
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted small mb-1">Couple's Names</h6>
                                <p class="mb-0">{{ $reservation->bride_name }} & {{ $reservation->groom_name }}</p>
                            </div>
                        @endif
                        @if($reservation->special_requests)
                            <div class="col-12">
                                <h6 class="text-muted small mb-1">Special Requests</h6>
                                <p class="mb-0">{{ $reservation->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Reference Files -->
            @if($reservation->reference_files && count($reservation->reference_files) > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Reference Files</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($reservation->reference_files as $file)
                                @php
                                    $fileExt = pathinfo($file, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100 border">
                                        @if($isImage)
                                            <img src="{{ Storage::url($file) }}" class="card-img-top" alt="Reference image" style="height: 120px; object-fit: cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 120px;">
                                                <i class="fas fa-file-alt fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body p-2 text-center">
                                            <p class="card-text small text-truncate mb-1" title="{{ basename($file) }}">
                                                {{ basename($file) }}
                                            </p>
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="fas fa-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Payment History -->
            @if($reservation->payments->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Payment History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservation->payments->sortByDesc('created_at') as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>{{ number_format($payment->amount, 0, ',', '.') }} IDR</td>
                                            <td>
                                                @if($payment->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($payment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->receipt_path)
                                                    <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-receipt"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Reservation History -->
            @if($reservation->revisions->count() > 0)
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Reservation History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($reservation->revisions->sortByDesc('created_at') as $revision)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $revision->title }}</h6>
                                        <small class="text-muted">{{ $revision->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $revision->description }}</p>
                                    <small class="text-muted">Updated by {{ $revision->user->name }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @if($reservation->status == 'pending')
                        <a href="#" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="fas fa-times-circle me-2"></i> Cancel Request
                        </a>
                    @endif
                    
                    @if($reservation->status == 'confirmed' && $reservation->total_price > $reservation->payments->where('status', 'approved')->sum('amount'))
                        <a href="{{ route('customer.dashboard.payments.create', $reservation->id) }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-credit-card me-2"></i> Make Payment
                        </a>
                    @endif
                    
                    <a href="#" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-file-invoice me-2"></i> Download Invoice
                    </a>
                    
                    <a href="#" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
                        <i class="fas fa-envelope me-2"></i> Send Message
                    </a>
                </div>
            </div>
            
            <!-- Summary Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>{{ number_format($reservation->total_price, 0, ',', '.') }} IDR</span>
                    </div>
                    @if($reservation->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Discount</span>
                            <span class="text-danger">-{{ number_format($reservation->discount_amount, 0, ',', '.') }} IDR</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (10%)</span>
                        <span>{{ number_format($reservation->total_price * 0.1, 0, ',', '.') }} IDR</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>{{ number_format($reservation->total_price * 1.1, 0, ',', '.') }} IDR</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Paid</span>
                        <span class="text-success">{{ number_format($paidAmount, 0, ',', '.') }} IDR</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Remaining Balance</span>
                        <span class="text-primary">{{ number_format(($reservation->total_price * 1.1) - $paidAmount, 0, ',', '.') }} IDR</span>
                    </div>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p class="small mb-3">If you have any questions about your reservation, feel free to contact our support team.</p>
                    <a href="{{ route('company.contact') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-headset me-2"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customer.dashboard.reservations.cancel', $reservation->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for cancellation</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendMessageModalLabel">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="message_subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message_content" class="form-label">Message</label>
                        <textarea class="form-control" id="message_content" name="content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .progress {
        height: 8px;
        border-radius: 4px;
    }
</style>
@endpush
