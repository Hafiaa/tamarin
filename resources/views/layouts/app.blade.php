<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tamacafe') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --bs-primary: #b9c24b !important;
            --bs-primary-rgb: 185, 194, 75 !important;
        }
        .badge.bg-primary {
            background-color: #b9c24b !important;
            border-color: #b9c24b !important;
        }
        
        /* Custom Success Color */
        :root {
            --bs-success: #5b7917 !important;
            --bs-success-rgb: 91, 121, 23 !important;
        }
        
        .btn-success,
        .bg-success,
        .badge.bg-success,
        .alert-success,
        .border-success,
        .btn-outline-success {
            --bs-btn-color: #fff !important;
            --bs-btn-bg: #5b7917 !important;
            --bs-btn-border-color: #5b7917 !important;
            --bs-btn-hover-bg: #4a6313 !important;
            --bs-btn-hover-border-color: #3f550f !important;
            --bs-btn-active-bg: #3f550f !important;
            --bs-btn-active-border-color: #34460d !important;
        }
        
        .text-success,
        .alert-success {
            --bs-text-opacity: 1 !important;
            color: #5b7917 !important;
        }
        
        .btn-outline-success {
            --bs-btn-color: #5b7917 !important;
            --bs-btn-bg: transparent !important;
            --bs-btn-border-color: #5b7917 !important;
            --bs-btn-hover-bg: #5b7917 !important;
            --bs-btn-hover-color: #fff !important;
            --bs-btn-active-bg: #4a6313 !important;
            --bs-btn-active-color: #fff !important;
            --bs-btn-active-border-color: #3f550f !important;
        }
        .btn-outline-primary {
            color: #b9c24b !important;
            border-color: #b9c24b !important;
        }
        .btn-primary, .bg-primary {
            background-color: #b9c24b !important;
            border-color: #b9c24b !important;
        }
        .btn-outline-primary:hover {
            background-color: #b9c24b !important;
            color: #fff !important;
        }
        .text-primary {
            color: #b9c24b !important;
        }
        .badge.bg-primary {
            background-color: #b9c24b !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts.navigation')

        <main>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
