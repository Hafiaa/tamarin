<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Tamacafe Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('events.*') ? 'active' : '' }}" href="#" id="eventsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-alt me-1"></i> Acara
                    </a>
                    @php
                        $activeEventTypes = \App\Models\EventType::where('is_active', true)
                            ->orderBy('name')
                            ->get();
                    @endphp
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="eventsDropdown">
                        @if($activeEventTypes->count() > 0)
                            @foreach($activeEventTypes as $eventType)
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" 
                                       href="{{ route('events.index', ['event_type' => $eventType->id]) }}">
                                        <span class="badge bg-primary me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                        {{ $eventType->name }}
                                    </a>
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('events.index') }}">
                                <i class="fas fa-list-ul me-2 text-muted"></i>
                                Semua Paket Acara
                            </a>
                        </li>
                        @auth
                            @if(auth()->user()->hasRole('admin'))
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center text-success" 
                                       href="{{ route('filament.admin.resources.event-types.index') }}">
                                        <i class="fas fa-cog me-2"></i>
                                        Kelola Jenis Acara
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('menu.*') ? 'active' : '' }}" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('menu.cafe') }}">Cafe Menu</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('menu.event') }}">Event Menu</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('company.*') ? 'active' : '' }}" href="#" id="companyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        About Us
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="companyDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('company.about') }}">Our Story</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('company.gallery') }}">Gallery</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('company.contact') }}">Contact Us</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="d-flex">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.dashboard.index') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.dashboard.reservations') }}">
                                    <i class="fas fa-calendar-alt me-2"></i> My Reservations
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.dashboard.payments') }}">
                                    <i class="fas fa-credit-card me-2"></i> My Payments
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.dashboard.profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i> Edit Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
