<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Package - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            z-index: 1;
        }
        .step.active .step-number {
            background-color: #3b82f6;
            color: white;
        }
        .step.completed .step-number {
            background-color: #10b981;
            color: white;
        }
        .step-title {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .step.active .step-title {
            color: #1f2937;
            font-weight: 500;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 0;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 0;
            height: 2px;
            background-color: #10b981;
            z-index: 0;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        .step.completed:not(:last-child)::after {
            transform: scaleX(1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">
                        {{ config('app.name') }}
                    </a>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('customer.dashboard.profile.edit') }}" class="text-gray-600 hover:text-gray-900">
                                {{ Auth::user()->name }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Progress Bar -->
                <div class="px-6 py-8 sm:px-10">
                    <div class="step-indicator">
                        <div class="step {{ in_array($currentStep, [1, 2, 3]) ? 'completed' : '' }} {{ $currentStep === 1 ? 'active' : '' }}">
                            <div class="step-number">1</div>
                            <div class="step-title">Event Details</div>
                        </div>
                        <div class="step {{ $currentStep >= 2 ? 'completed' : '' }} {{ $currentStep === 2 ? 'active' : '' }}">
                            <div class="step-number">2</div>
                            <div class="step-title">Services</div>
                        </div>
                        <div class="step {{ $currentStep === 3 ? 'active' : '' }}">
                            <div class="step-number">3</div>
                            <div class="step-title">Review & Submit</div>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="px-6 pb-8 sm:px-10">
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
