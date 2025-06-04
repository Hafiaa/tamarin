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

                    <form method="POST" action="{{ route('reservations.store') }}" id="reservationForm" x-data="reservationForm()">
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
                                    id="event_type_id" name="event_type_id" required
                                    x-model="eventType" @change="toggleWeddingFields()">
                                <option value="">Pilih Jenis Acara</option>
                                @foreach($eventTypes as $eventType)
                                    <option value="{{ $eventType->id }}" 
                                        {{ old('event_type_id', $package->event_type_id ?? '') == $eventType->id ? 'selected' : '' }} 
                                        data-name="{{ strtolower($eventType->name) }}">
                                        {{ $eventType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Wedding Fields (Conditional) -->
                        <div x-show="showWeddingFields" x-transition>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="bride_name">Nama Calon Pengantin Wanita *</label>
                                        <input type="text" class="form-control @error('bride_name') is-invalid @enderror" 
                                               id="bride_name" name="bride_name" 
                                               value="{{ old('bride_name') }}"
                                               :required="showWeddingFields">
                                        @error('bride_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="groom_name">Nama Calon Pengantin Pria *</label>
                                        <input type="text" class="form-control @error('groom_name') is-invalid @enderror" 
                                               id="groom_name" name="groom_name" 
                                               value="{{ old('groom_name') }}"
                                               :required="showWeddingFields">
                                        @error('groom_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
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
                            @error('event_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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

@push('styles')
<!-- Removed flatpickr CSS as we're using native date input -->
<style>
    /* Calendar container */
    .flatpickr-calendar {
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    /* Calendar header */
    .flatpickr-months {
        border-radius: 8px 8px 0 0;
        padding: 10px 0;
        background: #4f46e5;
    }
    
    .flatpickr-current-month {
        font-size: 1.1em;
        color: white;
    }
    
    .flatpickr-months .flatpickr-month {
        color: white;
    }
    
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        color: white;
        fill: white;
        padding: 10px;
    }
    
    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    /* Calendar days */
    .flatpickr-day {
        border-radius: 4px;
        margin: 2px;
        max-width: calc(100% / 7 - 4px);
        height: 36px;
        line-height: 36px;
    }
    
    .flatpickr-day:hover {
        background: #e9e7ff;
        border-color: #c7d2fe;
    }
    
    .flatpickr-day.selected, 
    .flatpickr-day.selected:hover {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }
    
    .flatpickr-day.today {
        border-color: #a5b4fc;
        font-weight: bold;
    }
    
    .flatpickr-day.today:hover {
        background: #e0e7ff;
    }
    
    .flatpickr-day.weekend {
        color: #ef4444;
    }
    
    /* Blocked dates */
    .flatpickr-day.blocked {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        text-decoration: line-through;
        cursor: not-allowed;
    }
    
    .flatpickr-day.blocked:hover {
        background: #fee2e2 !important;
    }
    
    /* Calendar footer */
    .flatpickr-time {
        border-top: 1px solid #e5e7eb;
        padding: 10px 0;
    }
    
    /* Input group */
    .input-group-text {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .input-group-text:hover {
        background-color: #e9ecef;
    }
    
    /* Status messages */
    .text-success {
        color: #10b981 !important;
    }
    
    .text-danger {
        color: #ef4444 !important;
    }
    
    .form-text {
        display: block;
        margin-top: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<!-- Removed flatpickr JS as we're using native date input -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reservationForm', () => ({
            eventType: '',
            showWeddingFields: false,
            loading: false,
            dateAvailable: null,
            dateMessage: 'Pilih tanggal untuk memeriksa ketersediaan',
            blockedDates: [],
            flatpickrInstance: null,
            minBookingDays: 3, // Minimum days in advance to book
            
            init() {
                this.toggleWeddingFields();
                this.initializeDatePicker();
                this.loadBlockedDates();
            },
            
            initializeDatePicker() {
                // Simple date initialization using native date input
                const dateInput = document.getElementById('event_date');
                if (dateInput) {
                    // Set minimum date to today
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.min = today;
                    
                    // Initialize with default date if empty
                    if (!dateInput.value) {
                        const defaultDate = new Date();
                        defaultDate.setDate(defaultDate.getDate() + this.minBookingDays);
                        dateInput.value = defaultDate.toISOString().split('T')[0];
                    }
                    
                    // Add change event listener
                    dateInput.addEventListener('change', () => {
                        this.checkDateAvailability();
                    });
                }
            },
            
            async loadBlockedDates() {
                try {
                    const response = await fetch('{{ route("blocked-dates.list") }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin'
                    });
                    
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    
                    const data = await response.json();
                    
                    if (data.success && Array.isArray(data.dates)) {
                        this.blockedDates = data.dates;
                        
                        if (this.flatpickrInstance) {
                            this.flatpickrInstance.set('disable', this.blockedDates);
                            this.flatpickrInstance.redraw();
                        }
                        
                        // Check initial date if already selected
                        if (this.$refs.eventDate.value) {
                            this.checkDateAvailability();
                        }
                    }
                } catch (error) {
                    console.error('Error loading blocked dates:', error);
                    this.dateMessage = 'Gagal memuat data tanggal yang tidak tersedia';
                }
            },
            
            toggleWeddingFields() {
                const selectedOption = this.$el.querySelector('#event_type_id option:checked');
                if (selectedOption) {
                    const eventName = selectedOption.textContent.toLowerCase();
                    this.showWeddingFields = eventName.includes('wedding') || 
                                           eventName.includes('pernikahan') || 
                                           eventName.includes('lamaran') || 
                                           eventName.includes('engagement');
                } else {
                    this.showWeddingFields = false;
                }
            },
            
            async checkDateAvailability() {
                const dateInput = document.getElementById('event_date');
                const dateValue = dateInput.value;
                
                if (!dateValue) {
                    this.dateAvailable = false;
                    this.dateMessage = 'Silakan pilih tanggal terlebih dahulu';
                    return;
                }
                
                this.loading = true;
                this.dateMessage = 'Memeriksa ketersediaan...';
                
                try {
                    const response = await fetch('{{ route("reservations.check-availability") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ date: dateValue })
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const data = await response.json();
                    
                    if (data.available) {
                        this.dateAvailable = true;
                        this.dateMessage = 'Tanggal tersedia';
                        dateInput.classList.remove('is-invalid');
                        dateInput.classList.add('is-valid');
                    } else {
                        this.dateAvailable = false;
                        this.dateMessage = data.message || 'Tanggal tidak tersedia';
                        dateInput.classList.add('is-invalid');
                        dateInput.classList.remove('is-valid');
                    }
                } catch (error) {
                    console.error('Error checking date availability:', error);
                    this.dateAvailable = false;
                    this.dateMessage = 'Gagal memeriksa ketersediaan. Silakan coba lagi.';
                    dateInput.classList.add('is-invalid');
                } finally {
                    this.loading = false;
                }
            },
            
            validateForm(event) {
                // Wedding fields validation
                if (this.showWeddingFields) {
                    const brideName = this.$el.querySelector('#bride_name')?.value.trim();
                    const groomName = this.$el.querySelector('#groom_name')?.value.trim();
                    
                    if (!brideName || !groomName) {
                        event.preventDefault();
                        alert('Mohon lengkapi nama calon pengantin');
                        return false;
                    }
                }
                
                // Date validation
                if (!this.$refs.eventDate.value) {
                    event.preventDefault();
                    alert('Mohon pilih tanggal acara');
                    return false;
                }
                
                if (this.dateAvailable !== true) {
                    event.preventDefault();
                    alert('Mohon pastikan tanggal yang dipilih tersedia');
                    return false;
                }
                
                return true;
            }
        }));
    });
</script>
@endpush

@endsection
