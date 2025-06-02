@extends('custom-package.layout')

@push('styles')
<style>
    .service-category {
        margin-bottom: 2.5rem;
    }
    .service-item {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }
    .service-item:hover {
        border-color: #9ca3af;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .service-item.selected {
        border-color: #6366f1;
        background-color: #f5f3ff;
    }
    .service-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.375rem;
    }
    .quantity-selector {
        display: flex;
        align-items: center;
    }
    .quantity-btn {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        cursor: pointer;
        user-select: none;
    }
    .quantity-input {
        width: 3rem;
        text-align: center;
        border: 1px solid #d1d5db;
        border-left: none;
        border-right: none;
        height: 2rem;
        -moz-appearance: textfield;
    }
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Services</h2>
    
    <form action="{{ route('custom-package.process-step2') }}" method="POST" id="servicesForm">
        @csrf
        
        <!-- Hidden fields from step 1 -->
        <input type="hidden" name="event_type_id" value="{{ old('event_type_id') }}">
        <input type="hidden" name="event_date" value="{{ old('event_date') }}">
        <input type="hidden" name="event_time" value="{{ old('event_time') }}">
        <input type="hidden" name="guest_count" value="{{ old('guest_count') }}">
        <input type="hidden" name="bride_name" value="{{ old('bride_name') }}">
        <input type="hidden" name="groom_name" value="{{ old('groom_name') }}">
        <input type="hidden" name="special_requests" value="{{ old('special_requests') }}">
        
        <!-- Hidden fields for services will be added here by JavaScript -->
        <div id="servicesContainer"></div>
        
        <div class="space-y-8">
            @foreach($serviceItems as $category => $items)
                <div class="service-category">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ ucfirst($category) }}</h3>
                    <div class="space-y-4">
                        @foreach($items as $item)
                            @php
                                $serviceKey = 'service_' . $item->id;
                                $isSelected = in_array($item->id, array_column(old('services', []), 'service_item_id'));
                                $quantity = $selectedServices[$item->id] ?? 1;
                            @endphp
                            
                            <div class="service-item {{ $isSelected ? 'selected' : '' }}" 
                                 data-service-id="{{ $item->id }}"
                                 x-data="{
                                    isSelected: {{ $isSelected ? 'true' : 'false' }},
                                    quantity: {{ $quantity }},
                                    minQty: {{ $item->min_quantity }},
                                    maxQty: {{ $item->max_quantity ?? 'null' }},
                                    price: {{ $item->base_price }},
                                    updateTotal() {
                                        this.$dispatch('update-total', { 
                                            id: {{ $item->id }}, 
                                            quantity: this.quantity,
                                            price: this.price * this.quantity
                                        });
                                    }
                                 }"
                                 x-init="if(isSelected) { updateTotal(); }">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex items-start space-x-4">
                                        @if($item->hasMedia('default'))
                                            <img src="{{ $item->getFirstMediaUrl('default', 'thumb') }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="service-image">
                                        @else
                                            <div class="service-image bg-gray-200 flex items-center justify-center text-gray-400">
                                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $item->name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $item->description }}</p>
                                            <div class="mt-2">
                                                <span class="text-lg font-semibold text-indigo-600">
                                                    {{ number_format($item->base_price, 0, ',', '.') }} IDR
                                                </span>
                                                @if($item->min_quantity > 1)
                                                    <span class="text-xs text-gray-500 ml-2">Min. {{ $item->min_quantity }} pcs</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 md:mt-0 flex items-center space-x-4">
                                        <div class="quantity-selector" x-show="isSelected">
                                            <button type="button" 
                                                    class="quantity-btn rounded-l-md"
                                                    @click="if(quantity > minQty) { quantity--; updateTotal(); }"
                                                    :class="{ 'opacity-50 cursor-not-allowed': quantity <= minQty }">
                                                -
                                            </button>
                                            <input type="number" 
                                                   x-model.number="quantity"
                                                   :min="minQty" 
                                                   :max="maxQty || ''"
                                                   class="quantity-input"
                                                   @change="updateTotal()"
                                                   name="services[{{ $item->id }}][quantity]"
                                                   form="servicesForm">
                                            <button type="button" 
                                                    class="quantity-btn rounded-r-md"
                                                    @click="if(!maxQty || quantity < maxQty) { quantity++; updateTotal(); }"
                                                    :class="{ 'opacity-50 cursor-not-allowed': maxQty && quantity >= maxQty }">
                                                +
                                            </button>
                                        </div>
                                        
                                        <button type="button"
                                                class="px-4 py-2 rounded-md text-sm font-medium"
                                                :class="isSelected ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                                @click="isSelected = !isSelected; if(isSelected) { updateTotal(); } else { $dispatch('remove-total', {{ $item->id }}); }">
                                            <span x-text="isSelected ? 'Selected' : 'Select'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <!-- Navigation Buttons -->
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <button type="button" 
                        onclick="window.history.back()"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back
                </button>
                <div class="space-x-3">
                    <button type="submit" 
                            @click="prepareForm()"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Review & Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function prepareForm(event) {
        event.preventDefault();
        const servicesContainer = document.getElementById('servicesContainer');
        servicesContainer.innerHTML = ''; // Clear previous inputs
        
        // Get all selected services
        const selectedServices = document.querySelectorAll('.service-item.selected');
        
        if (selectedServices.length === 0) {
            alert('Silakan pilih setidaknya satu layanan');
            return false;
        }
        
        // Create a form data object to build the correct structure
        const formData = new FormData();
        
        // Add step 1 data
        formData.append('event_type_id', document.querySelector('input[name="event_type_id"]').value);
        formData.append('event_date', document.querySelector('input[name="event_date"]').value);
        formData.append('event_time', document.querySelector('input[name="event_time"]').value);
        formData.append('guest_count', document.querySelector('input[name="guest_count"]').value);
        formData.append('bride_name', document.querySelector('input[name="bride_name"]').value || '');
        formData.append('groom_name', document.querySelector('input[name="groom_name"]').value || '');
        formData.append('special_requests', document.querySelector('input[name="special_requests"]').value || '');
        
        // Add CSRF token
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        // Add services data with proper structure
        selectedServices.forEach((service, index) => {
            const serviceId = service.getAttribute('data-service-id');
            const quantity = service.querySelector('.quantity-input')?.value || 1;
            const notes = service.querySelector('.notes-input')?.value || '';
            
            formData.append(`services[${index}][service_item_id]`, serviceId);
            formData.append(`services[${index}][quantity]`, quantity);
            if (notes) {
                formData.append(`services[${index}][notes]`, notes);
            }
        });
        
        // Submit the form using fetch API to ensure proper data structure
        fetch('{{ route('custom-package.process-step2') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json().then(data => {
                    if (data.error) {
                        alert(data.error);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
    
    document.addEventListener('alpine:init', () => {
        Alpine.data('servicesForm', () => ({
            selectedServices: {},
            totalPrice: 0,
            
            init() {
                // Initialize with any previously selected services
                @php
                    $services = old('services', []);
                    $validServices = [];
                    
                    foreach ($services as $service) {
                        if (isset($service['service_item_id'])) {
                            $validServices[] = [
                                'service_item_id' => $service['service_item_id'],
                                'quantity' => $service['quantity'] ?? 1,
                                'price' => $service['price'] ?? 0
                            ];
                        }
                    }
                @endphp
                
                @foreach($validServices as $service)
                    this.selectedServices[{{ $service['service_item_id'] }}] = {
                        quantity: {{ $service['quantity'] }},
                        price: {{ $service['price'] }}
                    };
                    this.totalPrice += ({{ $service['quantity'] }} * {{ $service['price'] }});
                @endforeach
                
                this.$watch('selectedServices', value => {
                    console.log('Selected services updated:', value);
                }, { deep: true });
            },
            
            updateTotal(event) {
                const { id, quantity, price } = event.detail;
                
                if (!this.selectedServices[id]) {
                    this.selectedServices[id] = { quantity: 0, price: 0 };
                }
                
                // Update the total price by removing the old value and adding the new one
                this.totalPrice -= (this.selectedServices[id].quantity * this.selectedServices[id].price);
                this.selectedServices[id] = { quantity, price: price / quantity };
                this.totalPrice += price;
                
                console.log(`Updated service ${id}:`, this.selectedServices[id]);
                console.log('New total:', this.totalPrice);
            },
            
            removeFromTotal(event) {
                const id = event.detail;
                
                if (this.selectedServices[id]) {
                    this.totalPrice -= (this.selectedServices[id].quantity * this.selectedServices[id].price);
                    delete this.selectedServices[id];
                    
                    console.log(`Removed service ${id}`);
                    console.log('New total:', this.totalPrice);
                }
            }
        }));
    });
</script>
@endpush
