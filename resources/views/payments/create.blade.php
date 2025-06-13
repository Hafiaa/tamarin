@extends('layouts.app')

@section('title', 'Make a Payment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Make a Payment</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Payment Details</h5>
                        <p class="mb-1"><strong>Reservation ID:</strong> #{{ $reservation->id }}</p>
                        <p class="mb-1"><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($reservation->event_date)->format('F j, Y') }}</p>
                        <p class="mb-1"><strong>Total Amount:</strong> Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
                        <p class="mb-0"><strong>Remaining Balance:</strong> <span class="text-primary font-weight-bold">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span></p>
                    </div>

                    <form action="{{ route('customer.dashboard.payments.store', ['id' => $reservation->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount to Pay (Rp)</label>
                            <input type="number" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   min="1" 
                                   max="{{ $remainingAmount }}" 
                                   value="{{ old('amount', $remainingAmount) }}" 
                                   required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Pilih metode pembayaran</option>
                                <option value="bca" {{ old('payment_method') == 'bca' ? 'selected' : '' }}>BCA</option>
                                <option value="bni" {{ old('payment_method') == 'bni' ? 'selected' : '' }}>BNI</option>
                                <option value="mandiri" {{ old('payment_method') == 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet (Dana, OVO, GoPay, dll)</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" 
                                   class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" 
                                   name="payment_date" 
                                   value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                                   max="{{ now()->format('Y-m-d') }}" 
                                   required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_proof" class="form-label">Payment Proof (Image/PDF)</label>
                            <input type="file" 
                                   class="form-control @error('payment_proof') is-invalid @enderror" 
                                   id="payment_proof" 
                                   name="payment_proof" 
                                   accept="image/*,.pdf" 
                                   required>
                            <div class="form-text">Upload a clear image of your transfer receipt or payment proof (max: 5MB)</div>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Any additional information about this payment">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('customer.dashboard.reservations') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Back to Reservations
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Submit Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Instructions</h5>
                </div>
                <div class="card-body">
                    <h6>Instruksi Pembayaran:</h6>
                    <div class="mb-3">
                        <h6 class="fw-bold">Transfer Bank:</h6>
                        <ul class="mb-3">
                            <li><strong>BCA</strong>: 123 456 7890 (TAMARIN CAFE)</li>
                            <li><strong>BNI</strong>: 987 654 3210 (TAMARIN CAFE)</li>
                            <li><strong>Mandiri</strong>: 567 891 2345 (TAMARIN CAFE)</li>
                        </ul>
                        
                        <h6 class="fw-bold">E-Wallet:</h6>
                        <ul class="mb-3">
                            <li><strong>Dana</strong>: 0812 3456 7890 (TAMARIN CAFE)</li>
                            <li><strong>OVO</strong>: 0812 3456 7890 (TAMARIN CAFE)</li>
                            <li><strong>GoPay</strong>: 0812 3456 7890 (TAMARIN CAFE)</li>
                        </ul>
                        
                        <div class="alert alert-info mb-0">
                            <p class="mb-1"><strong>Catatan Penting:</strong></p>
                            <ol class="mb-0">
                                <li>Gunakan ID Reservasi sebagai referensi pembayaran</li>
                                <li>Unggah bukti pembayaran menggunakan form di atas</li>
                                <li>Pembayaran akan diverifikasi maksimal 1x24 jam</li>
                            </ol>
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
    .card {
        border-radius: 0.5rem;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    .btn {
        border-radius: 0.375rem;
    }
</style>
@endpush
