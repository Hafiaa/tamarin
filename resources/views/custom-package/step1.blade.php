@extends('custom-package.layout')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Event Details</h2>
    
    <form action="{{ route('custom-package.process-step1') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <!-- Event Type -->
            <div>
                <label for="event_type_id" class="block text-sm font-medium text-gray-700">Event Type <span class="text-red-500">*</span></label>
                <select id="event_type_id" name="event_type_id" required
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Select an event type</option>
                    @foreach($eventTypes as $eventType)
                        <option value="{{ $eventType->id }}" {{ old('event_type_id') == $eventType->id ? 'selected' : '' }}>
                            {{ $eventType->name }}
                        </option>
                    @endforeach
                </select>
                @error('event_type_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date <span class="text-red-500">*</span></label>
                    <input type="date" id="event_date" name="event_date" required
                           min="{{ now()->format('Y-m-d') }}"
                           value="{{ old('event_date', $reservation->event_date ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Event Time -->
                <div>
                    <label for="event_time" class="block text-sm font-medium text-gray-700">Event Time <span class="text-red-500">*</span></label>
                    <input type="time" id="event_time" name="event_time" required
                           value="{{ old('event_time', $reservation->event_time ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('event_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Guest Count -->
            <div class="w-1/2">
                <label for="guest_count" class="block text-sm font-medium text-gray-700">Number of Guests <span class="text-red-500">*</span></label>
                <input type="number" id="guest_count" name="guest_count" min="1" required
                       value="{{ old('guest_count', $reservation->guest_count ?? '') }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('guest_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Couple Names -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bride's Name -->
                <div>
                    <label for="bride_name" class="block text-sm font-medium text-gray-700">Bride's Name (Optional)</label>
                    <input type="text" id="bride_name" name="bride_name"
                           value="{{ old('bride_name', $reservation->bride_name ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('bride_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Groom's Name -->
                <div>
                    <label for="groom_name" class="block text-sm font-medium text-gray-700">Groom's Name (Optional)</label>
                    <input type="text" id="groom_name" name="groom_name"
                           value="{{ old('groom_name', $reservation->groom_name ?? '') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('groom_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Special Requests -->
            <div>
                <label for="special_requests" class="block text-sm font-medium text-gray-700">Special Requests (Optional)</label>
                <textarea id="special_requests" name="special_requests" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('special_requests', $reservation->special_requests ?? '') }}</textarea>
                @error('special_requests')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Navigation Buttons -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <div class="space-x-3">
                    <a href="{{ route('home') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Next: Select Services
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
