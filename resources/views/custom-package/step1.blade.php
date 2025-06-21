@extends('custom-package.layout')

@section('content')
<div class="form-container px-3 px-md-4 px-lg-5">
    <div class="mb-4">
        <h2 class="h4 mb-2">Event Details</h2>
        <p class="text-muted mb-0">Please fill in your event information</p>
    </div>
    
    <form action="{{ route('custom-package.process-step1') }}" method="POST" id="step1Form">
        @csrf
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="row g-4">
            <!-- Event Type -->
            <div class="col-12">
                <label for="event_type_id" class="form-label">
                    Tipe Acara <span class="text-danger">*</span>
                </label>
                <select id="event_type_id" name="event_type_id" class="form-select @error('event_type_id') is-invalid @enderror" required>
                    <option value="">Pilih tipe acara</option>
                    @foreach($eventTypes as $eventType)
                        <option value="{{ $eventType->id }}" {{ old('event_type_id') == $eventType->id ? 'selected' : '' }}>
                            {{ $eventType->name }}
                        </option>
                    @endforeach
                </select>
                @error('event_type_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Event Date -->
            <div class="col-md-6">
                <label for="event_date" class="form-label">
                    Tanggal Acara <span class="text-danger">*</span>
                </label>
                <input type="date" id="event_date" name="event_date" 
                       class="form-control @error('event_date') is-invalid @enderror" 
                       min="{{ now()->format('Y-m-d') }}"
                       value="{{ old('event_date') }}" required>
                @error('event_date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Event Time -->
            <div class="col-md-6">
                <label for="event_time" class="form-label">
                    Waktu Acara <span class="text-danger">*</span>
                </label>
                <input type="time" id="event_time" name="event_time" 
                       class="form-control @error('event_time') is-invalid @enderror" 
                       value="{{ old('event_time') }}" required>
                @error('event_time')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Guest Count -->
            <div class="col-md-6">
                <label for="guest_count" class="form-label">
                    Jumlah Tamu <span class="text-danger">*</span>
                </label>
                <input type="number" id="guest_count" name="guest_count" 
                       class="form-control @error('guest_count') is-invalid @enderror" 
                       min="1" 
                       max="1000"
                       value="{{ old('guest_count') }}" required>
                @error('guest_count')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Decoration Theme -->
            <div class="col-md-6">
                <label for="decoration_theme" class="form-label">
                    Tema Dekorasi (Opsional)
                </label>
                <select id="decoration_theme" name="decoration_theme" class="form-select">
                    <option value="">Pilih Tema Dekorasi</option>
                    <option value="Romantic">Romantic</option>
                    <option value="Garden">Garden</option>
                    <option value="Minimalist">Minimalist</option>
                    <option value="Rustic">Rustic</option>
                    <option value="Vintage">Vintage</option>
                    <option value="Custom">Custom (Sebutkan di catatan)</option>
                </select>
                <small class="form-text text-muted">Pilih tema dekorasi yang diinginkan</small>
            </div>
            
            <!-- Bride's Name -->
            <div class="col-md-6">
                <label for="bride_name" class="form-label">Nama Pengantin Wanita (Opsional)</label>
                <input type="text" id="bride_name" name="bride_name"
                       class="form-control @error('bride_name') is-invalid @enderror" 
                       value="{{ old('bride_name') }}"
                       placeholder="Nama pengantin wanita">
                @error('bride_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Groom's Name -->
            <div class="col-md-6">
                <label for="groom_name" class="form-label">Nama Pengantin Pria (Opsional)</label>
                <input type="text" id="groom_name" name="groom_name"
                       class="form-control @error('groom_name') is-invalid @enderror" 
                       value="{{ old('groom_name') }}"
                       placeholder="Nama pengantin pria">
                @error('groom_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Additional Notes -->
            <div class="col-12">
                <label for="special_requests" class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea id="special_requests" name="special_requests" rows="3"
                          class="form-control @error('special_requests') is-invalid @enderror"
                          placeholder="Permintaan khusus atau catatan tambahan">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Navigation Buttons -->
            <div class="col-12 mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4" id="submitButton">
                    <span id="buttonText">Lanjut ke Langkah Berikutnya</span>
                    <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status" aria-hidden="true"></span>
                </button>
            </div>
            
            @push('scripts')
            <script>
                document.getElementById('step1Form').addEventListener('submit', function() {
                    const button = document.getElementById('submitButton');
                    const spinner = document.getElementById('spinner');
                    const buttonText = document.getElementById('buttonText');
                    
                    button.disabled = true;
                    spinner.classList.remove('d-none');
                    buttonText.textContent = 'Memproses...';
                });
            </script>
            @endpush
        </div>
    </form>
@endsection
