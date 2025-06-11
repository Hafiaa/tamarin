@extends('layouts.app')

@section('title', 'Paket Acara - ' . config('app.name'))

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-5 position-relative">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title mb-3">
                        @if(isset($selectedEventType))
                            Paket {{ $selectedEventType->name }}
                        @else
                            Pilihan Paket Acara
                        @endif
                    </h1>
                    <p class="lead text-muted mb-4">
                        Temukan paket acara terbaik untuk hari spesial Anda. Kami menawarkan berbagai pilihan paket yang dapat disesuaikan dengan kebutuhan Anda.
                    </p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a></li>
                            @if(isset($selectedEventType))
                                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Paket Acara</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $selectedEventType->name }}</li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">Semua Paket</li>
                            @endif
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                    <img src="{{ asset('images/event-header.jpg') }}" alt="Paket Acara" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>

    <!-- Event Packages Section -->
    <section class="event-packages-section py-5">
        <div class="container">
            <!-- Event Type Filter -->
            <div class="event-filter mb-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="filter-title mb-3">
                                    <i class="fas fa-filter me-2 text-primary"></i> Filter Jenis Acara
                                </h5>
                                <div class="d-flex flex-wrap align-items-center">
                                    <a href="{{ route('events.index') }}" 
                                       class="btn btn-sm mb-2 me-2 {{ !$eventTypeId ? 'btn-primary' : 'btn-outline-primary' }}">
                                        <i class="fas fa-th-large me-1"></i> Semua
                                    </a>
                                    @foreach($eventTypes as $eventType)
                                        <a href="{{ route('events.index', ['event_type' => $eventType->id]) }}" 
                                           class="btn btn-sm mb-2 me-2 {{ $eventTypeId == $eventType->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                            <i class="fas {{ $eventType->icon ?? 'fa-calendar' }} me-1"></i>
                                            {{ $eventType->name }}
                                            @if($eventType->packages_count > 0)
                                                <span class="badge bg-white text-primary ms-1">{{ $eventType->packages_count }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Paket Event -->
            <div class="row">
                @forelse($packages as $package)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                            <div class="position-relative" style="height: 200px; background-color: #f8f9fa; overflow: hidden;">
                                @if($package->getFirstMediaUrl('cover_image'))
                                    <img src="{{ $package->getFirstMediaUrl('cover_image') }}" 
                                         class="card-img-top h-100 w-100" 
                                         alt="{{ $package->name }}"
                                         style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light">
                                        <i class="fas fa-image fa-4x text-muted"></i>
                                    </div>
                                @endif
                                
                                @if($package->is_featured)
                                    <span class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded">
                                        <i class="fas fa-star me-1"></i> Unggulan
                                    </span>
                                @endif
                                @if($package->discount > 0)
                                    <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded">
                                        <i class="fas fa-tag me-1"></i> Diskon {{ $package->discount }}%
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary">
                                        <i class="fas {{ $package->eventType->icon ?? 'fa-calendar' }} me-1"></i>
                                        {{ $package->eventType->name ?? 'Umum' }}
                                    </span>
                                    <div class="text-end">
                                        @if($package->discount > 0)
                                            <small class="text-decoration-line-through text-muted d-block">
                                                {{ number_format($package->base_price, 0, ',', '.') }} IDR
                                            </small>
                                            <span class="h5 text-primary fw-bold mb-0">
                                                {{ number_format($package->base_price * (1 - $package->discount/100), 0, ',', '.') }} IDR
                                            </span>
                                        @else
                                            <span class="h5 text-primary fw-bold mb-0">
                                                {{ number_format($package->base_price, 0, ',', '.') }} IDR
                                            </span>
                                        @endif
                                        <small class="d-block text-muted">/ {{ $package->duration }} jam</small>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold mb-2">{{ $package->name }}</h5>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit(strip_tags($package->description), 120) }}
                                </p>
                                
                                <div class="package-features mb-3">
                                    <h6 class="h6 mb-2">
                                        <i class="fas fa-check-circle text-success me-1"></i> Yang Termasuk:
                                    </h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($package->highlights ?? [] as $highlight)
                                            <li class="mb-1">
                                                <i class="fas fa-check text-success small me-2"></i>
                                                <small>{{ $highlight }}</small>
                                            </li>
                                            @if($loop->iteration >= 3) @break @endif
                                        @endforeach
                                        @if(count($package->highlights ?? []) > 3)
                                            <li class="text-muted small">+{{ count($package->highlights) - 3 }} fasilitas lainnya</li>
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="d-flex align-items-center text-muted small mb-3">
                                    <div class="me-3">
                                        <i class="fas fa-users me-1"></i>
                                        <span>Maks. {{ $package->max_people }} orang</span>
                                    </div>
                                    @if($package->min_people > 0)
                                        <div>
                                            <i class="fas fa-user-friends me-1"></i>
                                            <span>Min. {{ $package->min_people }} orang</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('events.show', $package->slug) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </a>
                                    <a href="{{ route('reservations.create', $package->id) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar-check me-1"></i> Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">Belum ada paket acara tersedia</h5>
                                <p class="text-muted mb-0">Silakan coba filter lain atau hubungi kami untuk kebutuhan khusus Anda.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $packages->links() }}
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-gradient-primary text-white position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
            <div class="position-absolute" style="top: -50%; left: -10%; width: 50%; height: 200%; background: url('{{ asset('images/pattern.png') }}') center/cover;"></div>
        </div>
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h3 class="cta-title fw-bold mb-3">Tidak Menemukan yang Anda Cari?</h3>
                    <p class="cta-text lead mb-0">
                        Tim profesional kami siap membantu mewujudkan acara impian Anda. 
                        Hubungi kami untuk konsultasi dan penawaran paket khusus sesuai kebutuhan Anda.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('company.contact') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-paper-plane me-2"></i> Hubungi Kami
                    </a>
                    <div class="mt-3">
                        <a href="tel:{{ setting('site.phone') }}" class="text-white text-decoration-none d-inline-flex align-items-center">
                            <i class="fas fa-phone-alt me-2"></i> {{ setting('site.phone') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .event-filter .btn {
        transition: all 0.2s ease;
    }
    
    .event-filter .btn:hover {
        transform: translateY(-2px);
    }
    
    .package-price {
        font-weight: 600;
        color: #28a745;
    }
</style>
@endpush
