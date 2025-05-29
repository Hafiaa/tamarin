@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Buat Reservasi Baru</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservations.store') }}">
                        @csrf
                        
                        @if($package)
                            <input type="hidden" name="package_template_id" value="{{ $package->id }}">
                            <div class="alert alert-info">
                                <strong>Paket yang dipilih:</strong> {{ $package->name }}
                                <div class="mt-2">
                                    <strong>Harga:</strong> Rp {{ number_format($package->base_price, 0, ',', '.') }}
                                </div>
                            </div>
                        @endif
                        
                        <div class="form-group mb-3">
                            <label for="event_type_id">Jenis Acara *</label>
                            <select class="form-control @error('event_type_id') is-invalid @enderror" 
                                    id="event_type_id" name="event_type_id" required>
                                <option value="">Pilih Jenis Acara</option>
                                @foreach($eventTypes as $eventType)
                                    <option value="{{ $eventType->id }}" 
                                        {{ old('event_type_id', $package->event_type_id ?? '') == $eventType->id ? 'selected' : '' }}>
                                        {{ $eventType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="event_date">Tanggal Acara *</label>
                            <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                   id="event_date" name="event_date" 
                                   value="{{ old('event_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_time">Waktu Mulai *</label>
                                    <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                           id="event_time" name="event_time" 
                                           value="{{ old('event_time') }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_time">Waktu Selesai *</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" 
                                           value="{{ old('end_time') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="guest_count">Jumlah Tamu *</label>
                            <input type="number" class="form-control @error('guest_count') is-invalid @enderror" 
                                   id="guest_count" name="guest_count" 
                                   value="{{ old('guest_count', 1) }}" min="1" required>
                            @error('guest_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimal 1 orang</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="special_requests">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                      id="special_requests" name="special_requests" 
                                      rows="3" 
                                      placeholder="Contoh: Ada 2 orang vegetarian, perlu akses kursi roda, dll.">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Pastikan data yang Anda masukkan sudah benar. Setelah dikirim, Anda tidak dapat mengubah reservasi ini.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Buat Reservasi</button>
                            <a href="{{ route('customer.dashboard.index') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('event_date').min = today;
    });
</script>
@endpush

@endsection
