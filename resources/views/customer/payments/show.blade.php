@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Detail Pembayaran #{{ $payment->id }}</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Informasi Pembayaran</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ID Pembayaran:</strong> {{ $payment->id }}</p>
                                <p><strong>ID Reservasi:</strong> {{ $payment->reservation_id }}</p>
                                <p><strong>Jenis Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</p>
                                <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge 
                                        @if($payment->status === 'payment_pending_verification') bg-warning
                                        @elseif($payment->status === 'verified') bg-success
                                        @elseif($payment->status === 'rejected') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                    </span>
                                </p>
                                <p><strong>Tanggal Pembayaran:</strong> {{ $payment->payment_date ? $payment->payment_date->format('d M Y H:i') : '-' }}</p>
                                <p><strong>Batas Waktu Pembayaran:</strong> {{ $payment->due_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="mb-4">
                            <h5>Catatan</h5>
                            <hr>
                            <p>{{ $payment->notes }}</p>
                        </div>
                    @endif

                    @if($payment->hasMedia('payment_proofs'))
                        <div class="mb-4">
                            <h5>Bukti Pembayaran</h5>
                            <hr>
                            <div class="text-center">
                                <img src="{{ $payment->getFirstMediaUrl('payment_proofs') }}" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 500px;">
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('customer.dashboard.payments') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pembayaran
                        </a>
                        
                        @if($payment->status === 'rejected' && $payment->rejection_reason)
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rejectionReasonModal">
                                <i class="fas fa-info-circle me-1"></i> Lihat Alasan Penolakan
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($payment->status === 'rejected' && $payment->rejection_reason)
<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionReasonModal" tabindex="-1" aria-labelledby="rejectionReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="rejectionReasonModalLabel">Alasan Penolakan Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Alasan:</strong></p>
                <p>{{ $payment->rejection_reason }}</p>
                
                @if($payment->reservation->status === 'payment_rejected')
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Silakan perbaiki bukti pembayaran Anda dan unggah ulang melalui halaman pembayaran.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
