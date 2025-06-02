@extends('custom-package.layout')

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
        
        @foreach(old('services', []) as $index => $service)
            <input type="hidden" name="services[{{ $index }}][service_item_id]" value="{{ $service['service_item_id'] }}">
            <input type="hidden" name="services[{{ $index }}][quantity]" value="{{ $service['quantity'] }}">
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
                        @foreach($services as $index => $service)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-12 sm:gap-4 sm:px-6">
                                <div class="col-span-8">
                                    <div class="font-medium text-gray-900">{{ $service['service_item']->name }}</div>
                                    @if($service['notes'])
                                        <div class="mt-1 text-sm text-gray-500">{{ $service['notes'] }}</div>
                                    @endif
                                </div>
                                <div class="col-span-2 text-sm text-gray-900">
                                    {{ $service['quantity'] }} × {{ number_format($service['unit_price'], 0, ',', '.') }} IDR
                                </div>
                                <div class="col-span-2 text-right font-medium text-gray-900">
                                    {{ number_format($service['total_price'], 0, ',', '.') }} IDR
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Subtotal -->
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-12 sm:gap-4 sm:px-6 border-t border-gray-200">
                            <div class="col-span-10 text-right font-medium text-gray-900">
                                Subtotal
                            </div>
                            <div class="col-span-2 text-right font-medium text-gray-900">
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
                <button type="button" 
                    onclick="window.location.href='{{ route('custom-package.step2') }}'"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back
                </button>
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    id="submitButton">
                    Submit Reservation
                </button>
            </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format budget input
        const budgetInput = document.getElementById('budget');
        if (budgetInput) {
            budgetInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                document.getElementById('budgetInput').value = value;
            });
        }
        
        // Handle file previews
        const fileInput = document.getElementById('reference_files');
        const filePreview = document.getElementById('file-preview');
        
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                filePreview.innerHTML = '';
                
                if (this.files.length > 0) {
                    Array.from(this.files).forEach(file => {
                        const filePreviewItem = document.createElement('div');
                        filePreviewItem.className = 'file-preview';
                        
                        let previewContent = '';
                        
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                filePreviewItem.innerHTML = `
                                    <img src="${e.target.result}" alt="${file.name}">
                                    <div class="file-info">
                                        <div class="font-medium truncate">${file.name}</div>
                                        <div>${(file.size / 1024).toFixed(1)} KB</div>
                                    </div>
                                    <div class="file-remove">×</div>
                                `;
                                
                                // Add remove functionality
                                const removeBtn = filePreviewItem.querySelector('.file-remove');
                                removeBtn.addEventListener('click', function() {
                                    filePreviewItem.remove();
                                    // Remove the file from the input
                                    const dt = new DataTransfer();
                                    const input = fileInput;
                                    const { files } = input;
                                    
                                    for (let i = 0; i < files.length; i++) {
                                        if (files[i].name !== file.name) {
                                            dt.items.add(files[i]);
                                        }
                                    }
                                    
                                    input.files = dt.files;
                                });
                                
                                filePreview.appendChild(filePreviewItem);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            filePreviewItem.innerHTML = `
                                <div class="p-2 bg-gray-100 rounded">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="file-info">
                                    <div class="font-medium truncate">${file.name}</div>
                                    <div>${(file.size / 1024).toFixed(1)} KB</div>
                                </div>
                                <div class="file-remove">×</div>
                            `;
                            
                            // Add remove functionality
                            const removeBtn = filePreviewItem.querySelector('.file-remove');
                            removeBtn.addEventListener('click', function() {
                                filePreviewItem.remove();
                                // Remove the file from the input
                                const dt = new DataTransfer();
                                const input = fileInput;
                                const { files } = input;
                                
                                for (let i = 0; i < files.length; i++) {
                                    if (files[i].name !== file.name) {
                                        dt.items.add(files[i]);
                                    }
                                }
                                
                                input.files = dt.files;
                            });
                            
                            filePreview.appendChild(filePreviewItem);
                        }
                    });
                }
            });
        }
        
        // Form submission handling
        const form = document.getElementById('reviewForm');
        const submitButton = document.getElementById('submitButton');
        
        if (form && submitButton) {
            form.addEventListener('submit', function(e) {
                if (!document.getElementById('terms').checked) {
                    e.preventDefault();
                    alert('Please agree to the terms and conditions');
                    return false;
                }
                
                // Disable the submit button to prevent double submission
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                `;
            });
        }
        // Handle file preview
        const fileInput = document.getElementById('reference_files');
        const filePreview = document.getElementById('filePreview');
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                filePreview.innerHTML = ''; // Clear previous previews
                
                for (const file of e.target.files) {
                    const filePreviewItem = document.createElement('div');
                    filePreviewItem.className = 'file-preview';
                    
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        filePreviewItem.appendChild(img);
                    } else {
                        const icon = document.createElement('div');
                        icon.className = 'text-gray-400';
                        icon.innerHTML = `
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        `;
                        filePreviewItem.appendChild(icon);
                    }
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info';
                    fileInfo.innerHTML = `
                        <div class="font-medium truncate" style="max-width: 150px;">${file.name}</div>
                        <div>${(file.size / 1024).toFixed(1)} KB</div>
                    `;
                    
                    filePreviewItem.appendChild(fileInfo);
                    
                    const removeBtn = document.createElement('span');
                    removeBtn.className = 'file-remove';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function() {
                        // Create a new DataTransfer object to handle file removal
                        const dt = new DataTransfer();
                        const { files } = fileInput;
                        
                        // Add all files except the one to be removed
                        for (let i = 0; i < files.length; i++) {
                            if (i !== Array.from(fileInput.files).indexOf(file)) {
                                dt.items.add(files[i]);
                            }
                        }
                        
                        // Update the file input
                        fileInput.files = dt.files;
                        
                        // Remove the preview
                        filePreviewItem.remove();
                        
                        // Trigger change event to update form data
                        fileInput.dispatchEvent(new Event('change'));
                    };
                    
                    filePreviewItem.appendChild(removeBtn);
                    filePreview.appendChild(filePreviewItem);
                }
            });
        }
        
        // Form submission handling
        const form = document.getElementById('reviewForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Add loading state to submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;
                }
            });
        }
    });
</script>
@endpush
