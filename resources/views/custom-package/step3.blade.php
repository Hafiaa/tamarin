@extends('custom-package.layout')

@php
    // Debug data yang diterima
    // dd($services, $eventType);
@endphp

@push('styles')
<style>
    .service-item {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .service-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    .file-preview {
        display: inline-flex;
        align-items: center;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .file-preview img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
    }
    .file-preview .file-info {
        font-size: 0.75rem;
        color: #4b5563;
    }
    .file-preview .file-remove {
        margin-left: 0.5rem;
        color: #ef4444;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Review & Submit</h2>
    
    <form action="{{ route('custom-package.store') }}" method="POST" enctype="multipart/form-data" id="reviewForm">
    @csrf
        
        <!-- Hidden fields from previous steps -->
        <input type="hidden" name="event_type_id" value="{{ old('event_type_id') }}">
        <input type="hidden" name="event_date" value="{{ old('event_date') }}">
        <input type="hidden" name="event_time" value="{{ old('event_time') }}">
        <input type="hidden" name="guest_count" value="{{ old('guest_count') }}">
        <input type="hidden" name="bride_name" value="{{ old('bride_name') }}">
        <input type="hidden" name="groom_name" value="{{ old('groom_name') }}">
        <input type="hidden" name="special_requests" value="{{ old('special_requests') }}">
        
        @php
            $services = $services ?? [];
            $subtotal = 0;
        @endphp
        
        @foreach($services as $index => $service)
            @php
                $serviceId = $service['service_item']->id ?? ($service['service_item_id'] ?? null);
                $quantity = $service['quantity'] ?? 1;
                $unitPrice = $service['unit_price'] ?? 0;
                $serviceTotal = $quantity * $unitPrice;
                $subtotal += $serviceTotal;
            @endphp
            <input type="hidden" name="services[{{ $index }}][service_item_id]" value="{{ $serviceId }}">
            <input type="hidden" name="services[{{ $index }}][quantity]" value="{{ $quantity }}">
            <input type="hidden" name="services[{{ $index }}][notes]" value="{{ $service['notes'] ?? '' }}">
        @endforeach
        
        <!-- Budget and Reference Files -->
        <input type="hidden" name="budget" value="{{ old('budget', 0) }}" id="budgetInput">
        <input type="hidden" name="terms" value="1" id="termsInput">
        
        <div class="space-y-8">
            <!-- Event Details -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Event Details
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Event Type
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $eventType->name }}
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Date & Time
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ \Carbon\Carbon::parse(old('event_date'))->format('l, F j, Y') }} at {{ old('event_time') }}
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Number of Guests
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ old('guest_count') }}
                            </dd>
                        </div>
                        @if(old('bride_name') || old('groom_name'))
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Couple's Names
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ old('bride_name') }} & {{ old('groom_name') }}
                            </dd>
                        </div>
                        @endif
                        @if(old('special_requests'))
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Special Requests
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                                {{ old('special_requests') }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            <!-- Selected Services -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Selected Services
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        @if(count($services) > 0)
                            @foreach($services as $service)
                                @php
                                    $serviceItem = $service['service_item'] ?? null;
                                    $serviceId = $serviceItem->id ?? ($service['service_item_id'] ?? null);
                                    $serviceName = $serviceItem->name ?? 'Layanan #' . $serviceId;
                                    $serviceImage = $serviceItem->image ?? null;
                                    $quantity = $service['quantity'] ?? 1;
                                    $unitPrice = $service['unit_price'] ?? 0;
                                    $serviceTotal = $quantity * $unitPrice;
                                @endphp
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-12 sm:gap-4 sm:px-6 border-b border-gray-100">
                                    <div class="col-span-8 flex items-start">
                                        @if($serviceImage)
                                            <img src="{{ $serviceImage }}" alt="{{ $serviceName }}" class="h-16 w-16 flex-shrink-0 rounded-md object-cover mr-4">
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $serviceName }}</div>
                                            @if(!empty($service['notes']))
                                                <div class="mt-1 text-sm text-gray-500">{{ $service['notes'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900">
                                        {{ $quantity }} × {{ number_format($unitPrice, 0, ',', '.') }} IDR
                                    </div>
                                    <div class="col-span-2 text-right font-medium text-gray-900">
                                        {{ number_format($serviceTotal, 0, ',', '.') }} IDR
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="px-6 py-4 text-center text-gray-500">
                                Tidak ada layanan yang dipilih. Silakan kembali ke langkah sebelumnya.
                            </div>
                        @endif
                        
                        <!-- Subtotal -->
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-12 sm:gap-4 sm:px-6 border-t border-gray-200 bg-gray-50">
                            <div class="col-span-10 text-right font-semibold text-gray-900">
                                Subtotal
                            </div>
                            <div class="col-span-2 text-right font-semibold text-gray-900">
                                {{ number_format($subtotal, 0, ',', '.') }} IDR
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Budget & Reference Files -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Additional Information
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <!-- Budget -->
                    <div class="mb-6">
                        <label for="budget" class="block text-sm font-medium text-gray-700">
                            Your Budget (Optional)
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">IDR</span>
                            </div>
                            <input type="number" name="budget" id="budget" 
                                   value="{{ old('budget') }}"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-14 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                   placeholder="0">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Let us know your budget to help us better assist you.
                        </p>
                    </div>
                    
                    <!-- Reference Files -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Reference Files (Optional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="reference_files" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload files</span>
                                        <input id="reference_files" name="reference_files[]" type="file" multiple class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, PDF up to 5MB
                                </p>
                            </div>
                        </div>
                        
                        <!-- Preview of selected files -->
                        <div id="filePreview" class="mt-2 flex flex-wrap">
                            <!-- Files will be previewed here -->
                        </div>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="mt-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" 
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                       {{ old('terms') ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-medium text-gray-700">I agree to the </label>
                                <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>
                                <span class="text-red-500">*</span>
                                @error('terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="flex justify-between pt-6">
                <a href="{{ route('custom-package.step2') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" :disabled="isLoading">
                    <span x-show="!isLoading">
                        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Initialize form handler
        Alpine.data('formHandler', () => ({
            isLoading: false,
            
            init() {
                // Initialize any form related functionality here
            },
            
            submitForm() {
                // Show loading state
                this.isLoading = true;
                
                // The form will be submitted normally after this
                return true;
            }
        }));
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Format budget input
        const budgetInput = document.getElementById('budget');
        if (budgetInput) {
            budgetInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = this.value.replace(/[^0-9]/g, '');
                // Format with thousand separators
                this.value = new Intl.NumberFormat('id-ID').format(value);
                // Update hidden input
                document.getElementById('budgetInput').value = value;
            });
        }

        // Handle file preview
        const fileInput = document.getElementById('reference_files');
        const filePreview = document.getElementById('filePreview');
        
        if (fileInput && filePreview) {
            fileInput.addEventListener('change', function(e) {
                filePreview.innerHTML = '';
                
                if (this.files.length > 0) {
                    Array.from(this.files).forEach((file, index) => {
                        const filePreviewItem = document.createElement('div');
                        filePreviewItem.className = 'file-preview';
                        
                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            filePreviewItem.appendChild(img);
                        } else {
                            const icon = document.createElement('div');
                            icon.className = 'file-icon';
                            icon.innerHTML = `
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            `;
                            filePreviewItem.appendChild(icon);
                        }
                        
                        const fileInfo = document.createElement('div');
                        fileInfo.className = 'file-info';
                        fileInfo.innerHTML = `
                            <div class="font-medium truncate">${file.name}</div>
                            <div class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</div>
                        `;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'file-remove';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.onclick = function() {
                            const dt = new DataTransfer();
                            const input = fileInput;
                            
                            // Add all files except the one being removed
                            Array.from(input.files).forEach((f, i) => {
                                if (i !== index) {
                                    dt.items.add(f);
                                }
                            });
                            
                            // Update file input
                            input.files = dt.files;
                            
                            // Remove preview
                            filePreviewItem.remove();
                            
                            // Trigger change event
                            input.dispatchEvent(new Event('change'));
                        };
                        
                        filePreviewItem.appendChild(fileInfo);
                        filePreviewItem.appendChild(removeBtn);
                        filePreview.appendChild(filePreviewItem);
                    });
                }
            });
        }
        
        // Format currency on page load
        document.querySelectorAll('.currency').forEach(function(element) {
            const value = parseFloat(element.textContent.replace(/[^0-9.-]+/g,""));
            if (!isNaN(value)) {
                element.textContent = new Intl.NumberFormat('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }
        });
        
        // Handle form submission
        const form = document.getElementById('reviewForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    // Disable button and show loading state
                    submitBtn.disabled = true;
                    const originalHtml = submitBtn.innerHTML;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;
                    
                    // Re-enable button if form submission fails
                    setTimeout(() => {
                        if (!form.checkValidity()) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalHtml;
                        }
                    }, 5000);
                }
            });
        }
    });
</script>
@endpush
