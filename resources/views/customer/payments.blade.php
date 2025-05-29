@extends('layouts.app')

@section('title', 'My Payments')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-0">My Payments</h1>
                <p class="text-muted mb-0">View and manage your payment history</p>
            </div>
        </div>
    </div>
</div>

<!-- Payments Content -->
<div class="container py-5">
    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4" id="paymentsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="pill" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">Approved</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="pill" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">Rejected</button>
        </li>
    </ul>

    <!-- Payments List -->
    <div class="tab-content" id="paymentsTabsContent">
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            @if($payments->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Date</th>
                                        <th>Reservation</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>#{{ $payment->id }}</td>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('customer.dashboard.reservations.show', $payment->reservation->id) }}" class="text-decoration-none">
                                                    {{ $payment->reservation->eventType->name }} - 
                                                    {{ \Carbon\Carbon::parse($payment->reservation->event_date)->format('M d, Y') }}
                                                </a>
                                            </td>
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
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $payment->id }}">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Payment Modal -->
                                        <div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $payment->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="paymentModalLabel{{ $payment->id }}">Payment Details #{{ $payment->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <p class="text-muted mb-1">Payment Date</p>
                                                                <p class="fw-bold mb-0">{{ $payment->created_at->format('F d, Y h:i A') }}</p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <p class="text-muted mb-1">Amount</p>
                                                                <p class="fw-bold mb-0">{{ number_format($payment->amount, 0, ',', '.') }} IDR</p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <p class="text-muted mb-1">Payment Method</p>
                                                                <p class="fw-bold mb-0">
                                                                    @if($payment->payment_method == 'bank_transfer')
                                                                        <i class="fas fa-university me-1"></i> Bank Transfer
                                                                    @elseif($payment->payment_method == 'credit_card')
                                                                        <i class="fas fa-credit-card me-1"></i> Credit Card
                                                                    @elseif($payment->payment_method == 'cash')
                                                                        <i class="fas fa-money-bill-wave me-1"></i> Cash
                                                                    @else
                                                                        {{ $payment->payment_method }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <p class="text-muted mb-1">Status</p>
                                                                <p class="fw-bold mb-0">
                                                                    @if($payment->status == 'pending')
                                                                        <span class="badge bg-warning">Pending</span>
                                                                    @elseif($payment->status == 'approved')
                                                                        <span class="badge bg-success">Approved</span>
                                                                    @elseif($payment->status == 'rejected')
                                                                        <span class="badge bg-danger">Rejected</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <p class="text-muted mb-1">Reservation</p>
                                                                <p class="fw-bold mb-0">
                                                                    <a href="{{ route('customer.dashboard.reservations.show', $payment->reservation->id) }}" class="text-decoration-none">
                                                                        {{ $payment->reservation->eventType->name }} - 
                                                                        {{ \Carbon\Carbon::parse($payment->reservation->event_date)->format('M d, Y') }}
                                                                    </a>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <p class="text-muted mb-1">Payment Date</p>
                                                                <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}</p>
                                                            </div>
                                                            
                                                            @if($payment->notes)
                                                                <div class="col-12 mb-3">
                                                                    <p class="text-muted mb-1">Notes</p>
                                                                    <p class="mb-0">{{ $payment->notes }}</p>
                                                                </div>
                                                            @endif
                                                            
                                                            @if($payment->getFirstMediaUrl('payment_proof'))
                                                                <div class="col-12">
                                                                    <p class="text-muted mb-1">Payment Proof</p>
                                                                    <div class="payment-proof-image">
                                                                        <img src="{{ $payment->getFirstMediaUrl('payment_proof') }}" alt="Payment Proof" class="img-fluid rounded">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/no-payments.svg') }}" alt="No Payments" class="img-fluid mb-3" style="max-width: 200px;">
                    <h4>No Payments Found</h4>
                    <p class="text-muted">You haven't made any payments yet.</p>
                    <a href="{{ route('customer.dashboard.reservations') }}" class="btn btn-primary">View My Reservations</a>
                </div>
            @endif
        </div>
        
        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <!-- Content for pending payments will be loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading pending payments...</p>
            </div>
        </div>
        
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            <!-- Content for approved payments will be loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading approved payments...</p>
            </div>
        </div>
        
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            <!-- Content for rejected payments will be loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading rejected payments...</p>
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
        const tabs = document.querySelectorAll('#paymentsTabs button');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target').replace('#', '');
                if (target !== 'all') {
                    fetch(`/dashboard/payments/filter/${target}`)
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
