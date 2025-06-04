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
                            <div class="input-group">
                                <input type="text" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" 
                                       value="{{ old('event_date') }}"
                                       required
                                       x-ref="eventDate"
                                       placeholder="Pilih tanggal..."
                                       readonly>
                                <button class="btn btn-outline-secondary" type="button" id="checkDateBtn" @click="checkDateAvailability()">
                                    <span x-show="!loading">Cek Ketersediaan</span>
                                    <span class="spinner-border spinner-border-sm" x-show="loading" role="status" aria-hidden="true"></span>
                                </button>
                                <div class="invalid-feedback" id="dateFeedback"></div>
                            </div>
                            <small class="text-muted" id="dateAvailability"></small>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Style for blocked dates */
    .flatpickr-day.blocked {
        border-color: #dc3545 !important;
        background: #fff0f0 !important;
        color: #dc3545 !important;
        text-decoration: line-through;
    }
    
    .flatpickr-day.blocked:hover {
        background: #ffdddd !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        console.log('Alpine.js initialized');
        
        Alpine.data('reservationForm', () => ({
            eventType: '',
            showWeddingFields: false,
            loading: false,
            dateAvailable: null,
            dateMessage: '',
            blockedDates: [],
            flatpickrInstance: null,
            
            init() {
                // Initialize with current values
                this.toggleWeddingFields();
                
                // Initialize Flatpickr
                this.initializeDatePicker();
                
                // Load blocked dates
                this.loadBlockedDates();
            },
            
            initializeDatePicker() {
                console.log('Initializing date picker...');
                
                // Make sure the element exists
                if (!this.$refs.eventDate) {
                    console.error('Date input element not found');
                    return;
                }
                
                // Initialize Flatpickr with basic options first
                this.flatpickrInstance = flatpickr(this.$refs.eventDate, {
                    locale: 'id',
                    minDate: 'today',
                    dateFormat: 'Y-m-d',
                    disable: [],
                    onChange: (selectedDates, dateStr) => {
                        console.log('Date selected:', dateStr);
                        if (dateStr) {
                            this.checkDateAvailability();
                        }
                    },
                    onDayCreate: (dObj, dStr, fp, dayElem) => {
                        try {
                            // Format the date from the calendar day element to YYYY-MM-DD
                            const currentDateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                            
                            if (this.blockedDates.includes(currentDateStr)) {
                                dayElem.classList.add('blocked');
                                dayElem.title = 'Tanggal tidak tersedia';
                                // The class 'blocked' should handle styling via CSS.
                                // Direct style manipulation can be removed if CSS is sufficient.
                                // dayElem.style.textDecoration = 'line-through'; 
                            }
                        } catch (e) {
                            console.error('Error in onDayCreate:', e);
                        }
                    }
                });
                
                console.log('Date picker initialized');
            },
            
            async loadBlockedDates() {
                console.log('Loading blocked dates...');
                try {
                    const response = await fetch('{{ route("blocked-dates.list") }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin'
                    });
                    
                    console.log('Blocked dates response status:', response.status);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Blocked dates data:', data);
                    
                    if (data.success && Array.isArray(data.dates)) {
                        this.blockedDates = data.dates;
                        console.log('Blocked dates loaded:', this.blockedDates);
                        
                        // Update Flatpickr with blocked dates
                        if (this.flatpickrInstance) {
                            this.flatpickrInstance.set('disable', this.blockedDates);
                            this.flatpickrInstance.redraw();
                            console.log('Flatpickr updated with blocked dates');
                        }
                    } else {
                        console.error('Invalid blocked dates format:', data);
                    }
                } catch (error) {
                    console.error('Error loading blocked dates:', error);
                }
            },
            },
            
            toggleWeddingFields() {
                const selectedOption = this.$el.querySelector('#event_type_id option:checked');
                if (selectedOption) {
                    const eventName = selectedOption.textContent.toLowerCase();
                    this.showWeddingFields = eventName.includes('wedding') || eventName.includes('pernikahan') || 
                                           eventName.includes('lamaran') || eventName.includes('engagement');
                } else {
                    this.showWeddingFields = false;
                }
            },
            
            async checkDateAvailability() {
                const dateInput = this.$refs.eventDate;
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
                        body: JSON.stringify({
                            date: dateValue
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.available) {
                        this.dateAvailable = true;
                        this.dateMessage = 'Tanggal tersedia! 🎉';
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
                    this.dateMessage = 'Terjadi kesalahan saat memeriksa ketersediaan';
                    dateInput.classList.add('is-invalid');
                } finally {
                    this.loading = false;
                }
            },
            
            validateForm(event) {
                if (this.showWeddingFields) {
                    const brideName = this.$el.querySelector('#bride_name').value.trim();
                    const groomName = this.$el.querySelector('#groom_name').value.trim();
                    
                    if (!brideName || !groomName) {
                        event.preventDefault();
                        alert('Mohon lengkapi nama calon pengantin');
                        return false;
                    }
                }
                
                if (this.dateAvailable === false) {
                    event.preventDefault();
                    alert('Mohon pilih tanggal yang tersedia');
                    return false;
                }
                
                return true;
            }
        }));
    });
</script>
@endpush

@endsection
