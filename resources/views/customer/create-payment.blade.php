@extends('layouts.app')

@section('title', 'Make Payment')

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
                        <li class="breadcrumb-item active" aria-current="page">Make Payment</li>
                    </ol>
                </nav>
                <h1 class="fw-bold mb-0">Make Payment</h1>
                <p class="text-muted mb-0">For Reservation #{{ $reservation->id }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Payment Content -->
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
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
                    
                    <form action="{{ route('customer.dashboard.payments.store', $reservation->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="amount" class="form-label">Payment Amount (IDR)</label>
                            <div class="input-group">
                                <span class="input-group-text">IDR</span>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $remainingAmount) }}" min="1" max="{{ $remainingAmount }}" required>
                            </div>
                            <div class="form-text">
                                Remaining balance: IDR {{ number_format($remainingAmount, 0, ',', '.') }}
                            </div>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="bankTransferDetails" class="payment-method-details mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Bank Transfer Details</h6>
                                    <p class="mb-1">Please transfer to one of the following accounts:</p>
                                    <div class="mb-3">
                                        <p class="fw-bold mb-1">Bank BCA</p>
                                        <p class="mb-1">Account Number: 1234567890</p>
                                        <p class="mb-0">Account Name: PT Tamacafe Indonesia</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="fw-bold mb-1">Bank Mandiri</p>
                                        <p class="mb-1">Account Number: 0987654321</p>
                                        <p class="mb-0">Account Name: PT Tamacafe Indonesia</p>
                                    </div>
                                    <p class="mb-0 text-danger">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Please include your Reservation ID (#{{ $reservation->id }}) in the transfer description.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="creditCardDetails" class="payment-method-details mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Credit Card Details</h6>
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="expiry_date" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="XXX">
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label for="card_holder" class="form-label">Card Holder Name</label>
                                        <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="Name on card">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="cashDetails" class="payment-method-details mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Cash Payment</h6>
                                    <p class="mb-0">
                                        Please visit our office to make a cash payment. Our office is open Monday to Friday, 9:00 AM to 5:00 PM.
                                        <br>Don't forget to bring your Reservation ID (#{{ $reservation->id }}).
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_proof" class="form-label">Payment Proof (for bank transfers)</label>
                            <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                            <div class="form-text">Upload a screenshot or photo of your payment receipt (JPEG, PNG, or PDF).</div>
                            @error('payment_proof')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i> Submit Payment
                            </button>
                            <a href="{{ route('customer.dashboard.reservations.show', $reservation->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Reservation
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Reservation Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Reservation Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Event Type</p>
                        <p class="fw-bold mb-0">{{ $reservation->eventType->name }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Package</p>
                        <p class="fw-bold mb-0">
                            @if($reservation->packageTemplate)
                                {{ $reservation->packageTemplate->name }}
                            @else
                                Custom Package
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Event Date</p>
                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($reservation->event_date)->format('F d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Event Time</p>
                        <p class="fw-bold mb-0">{{ $reservation->event_time }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Number of Guests</p>
                        <p class="fw-bold mb-0">{{ $reservation->guest_count }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Summary Card -->
            <div class="card border-0 shadow-sm">
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
                        <span class="fw-bold text-danger">{{ number_format($remainingAmount, 0, ',', '.') }} IDR</span>
                    </div>
                    
                    <div class="progress mb-3" style="height: 10px;">
                        @php $paymentPercentage = $reservation->total_price > 0 ? ($paidAmount / $reservation->total_price) * 100 : 0; @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentPercentage }}%;" aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-center mb-0">
                        <small class="text-muted">{{ number_format($paymentPercentage, 0) }}% Paid</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide payment method details
        const paymentMethodSelect = document.getElementById('payment_method');
        const bankTransferDetails = document.getElementById('bankTransferDetails');
        const creditCardDetails = document.getElementById('creditCardDetails');
        const cashDetails = document.getElementById('cashDetails');
        const paymentProofInput = document.getElementById('payment_proof');
        
        function updatePaymentMethodDetails() {
            const selectedMethod = paymentMethodSelect.value;
            
            // Hide all payment method details
            bankTransferDetails.style.display = 'none';
            creditCardDetails.style.display = 'none';
            cashDetails.style.display = 'none';
            
            // Show selected payment method details
            if (selectedMethod === 'bank_transfer') {
                bankTransferDetails.style.display = 'block';
                paymentProofInput.setAttribute('required', 'required');
            } else if (selectedMethod === 'credit_card') {
                creditCardDetails.style.display = 'block';
                paymentProofInput.removeAttribute('required');
            } else if (selectedMethod === 'cash') {
                cashDetails.style.display = 'block';
                paymentProofInput.removeAttribute('required');
            }
        }
        
        // Initial update
        updatePaymentMethodDetails();
        
        // Update on change
        paymentMethodSelect.addEventListener('change', updatePaymentMethodDetails);
        
        // Format credit card input
        const cardNumberInput = document.getElementById('card_number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.substr(0, 16);
                }
                
                // Add spaces every 4 digits
                const parts = [];
                for (let i = 0; i < value.length; i += 4) {
                    parts.push(value.substr(i, 4));
                }
                
                e.target.value = parts.join(' ');
            });
        }
        
        // Format expiry date input
        const expiryDateInput = document.getElementById('expiry_date');
        if (expiryDateInput) {
            expiryDateInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.substr(0, 4);
                }
                
                if (value.length > 2) {
                    value = value.substr(0, 2) + '/' + value.substr(2);
                }
                
                e.target.value = value;
            });
        }
        
        // Format CVV input
        const cvvInput = document.getElementById('cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 3) {
                    value = value.substr(0, 3);
                }
                
                e.target.value = value;
            });
        }
    });
</script>
@endpush
