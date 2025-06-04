@extends('layouts.customer')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">My Reservations</h1>
        <a href="{{ route('reservations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
            New Reservation
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($reservations->isEmpty())
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600 mb-4">You don't have any reservations yet.</p>
            <a href="{{ route('reservations.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Make your first reservation
            </a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach ($reservations as $reservation)
                    <li>
                        <a href="{{ route('customer.dashboard.reservations.show', $reservation->id) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-blue-600 truncate">
                                        {{ $reservation->eventType->name }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'declined' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-gray-100 text-gray-800',
                                                'completed' => 'bg-blue-100 text-blue-800',
                                                'awaiting_first_payment' => 'bg-purple-100 text-purple-800',
                                                'cancelled_payment_expired' => 'bg-red-100 text-red-800',
                                            ][$reservation->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                            {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $reservation->event_date->format('F d, Y') }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $reservation->event_time }} - {{ $reservation->end_time }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                                        </svg>
                                        {{ $reservation->guest_count }} {{ Str::plural('guest', $reservation->guest_count) }}
                                    </div>
                                </div>
                                @if($reservation->payments->isNotEmpty())
                                    @php
                                        $payment = $reservation->payments->first();
                                        $paymentStatuses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'under_review' => 'bg-blue-100 text-blue-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'expired' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusClass = $paymentStatuses[$payment->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <div class="mt-2
                                    ">
                                        <span class="text-sm font-medium text-gray-900">Payment: </span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                            @if($payment->due_date)
                                                (Due: {{ $payment->due_date->format('M d, Y') }})
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection
