@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <!-- Success Message -->
            <h2 class="mt-3 text-2xl font-bold text-gray-900">Thank You for Your Request!</h2>
            <p class="mt-2 text-gray-600">
                We've received your custom package request and will review it shortly. 
                You'll receive a confirmation email with the details of your request.
            </p>
            
            <!-- Reservation Details -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6 text-left max-w-2xl mx-auto">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Your Request Details</h3>
                <dl class="space-y-3">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Request ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $reservation->event_date->format('l, F j, Y') }} at {{ $reservation->event_time }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $reservation->eventType->name }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Selected Services</h4>
                    <ul class="space-y-3">
                        @foreach($reservation->customPackageItems as $item)
                            <li class="flex justify-between">
                                <span class="text-sm text-gray-600">
                                    {{ $item->serviceItem->name }} ({{ $item->quantity }}×)
                                </span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ number_format($item->total_price, 0, ',', '.') }} IDR
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                        <span class="text-base font-medium text-gray-900">Total</span>
                        <span class="text-base font-bold text-gray-900">
                            {{ number_format($reservation->total_price, 0, ',', '.') }} IDR
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">What's Next?</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Our team will review your request and get back to you within 24-48 hours. 
                    You can check the status of your request in your account dashboard.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Back to Home
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View My Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
