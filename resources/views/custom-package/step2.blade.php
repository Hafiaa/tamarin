@extends('custom-package.layout')

@push('styles')
<style>
.service-card {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1.25rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
}

.service-card:hover {
    border-color: #cbd5e0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.service-card.selected {
    border-color: #4299e1;
    background-color: #ebf8ff;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.75rem;
    justify-content: flex-end;
}

.quantity-btn {
    width: 28px;
    height: 28px;
    border: 1px solid #cbd5e0;
    background: #f8fafc;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.quantity-btn:hover {
    background: #e2e8f0;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #cbd5e0;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
}

.service-category {
    margin-bottom: 2.5rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.service-category h3 {
    color: #2d3748;
    margin-bottom: 1.25rem;
}

.service-category:last-child {
    margin-bottom: 1.5rem;
}

.navigation-buttons {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid currentColor;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.75s linear infinite;
    margin-right: 0.5rem;
    vertical-align: middle;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Hide number input spinners */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Inisialisasi store sederhana
    Alpine.store('selectedServices', {});
    
    // Inisialisasi dari data yang ada
    @if(old('services'))
        @foreach(old('services') as $service)
            Alpine.store('selectedServices')['{{ $service['service_item_id'] }}'] = {
                service_item_id: {{ $service['service_item_id'] }},
                quantity: {{ $service['quantity'] ?? 1 }},
                notes: '{{ $service['notes'] ?? '' }}'
            };
        @endforeach
    @endif
    
    // Komponen untuk item layanan
    Alpine.data('serviceItem', (id, minQty, maxQty, price) => ({
        id: id,
        quantity: minQty,
        minQty: minQty,
        maxQty: maxQty,
        price: price,
        isSelected: false,
        
        init() {
            // Cek apakah layanan ini sudah dipilih
            const selectedService = Alpine.store('selectedServices')[this.id];
            if (selectedService) {
                this.isSelected = true;
                this.quantity = parseInt(selectedService.quantity) || this.minQty;
            }
            
            // Update hidden inputs saat inisialisasi
            this.updateHiddenInputs();
        },
        
        toggleSelection() {
            this.isSelected = !this.isSelected;
            
            if (this.isSelected) {
                this.quantity = Math.max(parseInt(this.quantity) || 1, this.minQty);
                Alpine.store('selectedServices')[this.id] = {
                    service_item_id: this.id,
                    quantity: this.quantity,
                    notes: ''
                };
            } else {
                delete Alpine.store('selectedServices')[this.id];
            }
            
            this.updateHiddenInputs();
        },
        
        updateQuantity() {
            // Pastikan quantity berupa angka dan dalam range yang valid
            const newQty = parseInt(this.quantity) || 0;
            this.quantity = Math.min(Math.max(newQty, this.minQty), this.maxQty || Infinity);
            
            if (this.isSelected) {
                // Pastikan layanan ada di store
                if (!Alpine.store('selectedServices')[this.id]) {
                    Alpine.store('selectedServices')[this.id] = {
                        service_item_id: this.id,
                        quantity: this.quantity,
                        notes: ''
                    };
                } else {
                    Alpine.store('selectedServices')[this.id].quantity = this.quantity;
                }
                this.updateHiddenInputs();
            }
        },
        
        increment() {
            if (this.maxQty === null || this.quantity < this.maxQty) {
                this.quantity++;
                if (this.isSelected) {
                    // Pastikan layanan ada di store
                    if (!Alpine.store('selectedServices')[this.id]) {
                        Alpine.store('selectedServices')[this.id] = {
                            service_item_id: this.id,
                            quantity: this.quantity,
                            notes: ''
                        };
                    } else {
                        Alpine.store('selectedServices')[this.id].quantity = this.quantity;
                    }
                    this.updateHiddenInputs();
                }
            }
        },
        
        decrement() {
            if (this.quantity > this.minQty) {
                this.quantity--;
                if (this.isSelected) {
                    Alpine.store('selectedServices')[this.id].quantity = this.quantity;
                    this.updateHiddenInputs();
                }
            }
        },
        
        updateHiddenInputs() {
            const container = document.getElementById('servicesContainer');
            if (!container) return;
            
            // Clear existing inputs
            container.innerHTML = '';
            
            // Add new inputs for each selected service
            Object.values(Alpine.store('selectedServices')).forEach((service, index) => {
                ['service_item_id', 'quantity', 'notes'].forEach(field => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `services[${index}][${field}]`;
                    input.value = service[field] || '';
                    container.appendChild(input);
                });
            });
        },
        
        formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price);
        }
    }));
});

// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('servicesForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update hidden inputs terakhir kali sebelum submit
            const container = document.getElementById('servicesContainer');
            if (container) {
                container.innerHTML = ''; // Clear existing inputs
                
                // Add hidden inputs for each selected service
                const selectedServices = Alpine.store('selectedServices');
                let hasValidService = false;
                let index = 0;
                
                for (const [id, service] of Object.entries(selectedServices)) {
                    // Pastikan quantity > 0 dan service valid
                    const quantity = parseInt(service.quantity) || 0;
                    if (quantity > 0 && service.service_item_id) {
                        const fields = {
                            'service_item_id': service.service_item_id || id,
                            'quantity': quantity,
                            'notes': service.notes || ''
                        };
                        
                        for (const [key, value] of Object.entries(fields)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `services[${index}][${key}]`;
                            input.value = value;
                            container.appendChild(input);
                        }
                        index++;
                        hasValidService = true;
                    }
                }
                
                // Debug: Tampilkan data yang akan dikirim
                console.log('Data yang akan dikirim:', container.innerHTML);
                
                // Validasi minimal 1 layanan dipilih
                if (!hasValidService) {
                    e.preventDefault();
                    alert('Pilih setidaknya satu layanan dengan jumlah minimal 1');
                    return false;
                }
            }
            
            // Tampilkan loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            }
            
            return true;
        });
    }
});
</script>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="h3 mb-4">Pilih Layanan</h2>
            <p class="text-muted mb-4">Silakan pilih layanan yang Anda butuhkan</p>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('custom-package.process-step2') }}" method="POST" id="servicesForm" x-data="formHandler" @submit="prepareFormData">
                @csrf
                <!-- Hidden inputs for selected services will be added here by Alpine.js -->
                <div id="servicesContainer"></div>
                
                <div class="service-categories">
                    @foreach($serviceItems as $category => $items)
                        <div class="service-category">
                            <h3 class="h5 mb-3">{{ ucfirst($category) }}</h3>
                            <div class="row g-3">
                                @foreach($items as $item)
                                    @php
                                        $isSelected = isset($selectedServices[$item->id]);
                                        $quantity = $isSelected ? $selectedServices[$item->id]['quantity'] : $item->min_quantity;
                                    @endphp
                                    
                                    <div class="col-12">
                                        <div class="service-card"
                                             :class="{ 'selected': isSelected }"
                                             x-data="serviceItem(
                                                 {{ $item->id }}, 
                                                 {{ $item->min_quantity }}, 
                                                 {{ $item->max_quantity ?? 'null' }}, 
                                                 {{ $item->base_price }}
                                             )"
                                             @click="toggleSelection()"
                                             x-init="
                                                 // Initialize from Alpine.store if exists
                                                 if (Alpine.store('services').selectedServices[{{ $item->id }}]) {
                                                     isSelected = true;
                                                     quantity = Alpine.store('services').selectedServices[{{ $item->id }}].quantity;
                                                 }
                                             ">
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1">{{ $item->name }}</h5>
                                                    @if($item->description)
                                                        <p class="text-muted small mb-0">{{ $item->description }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold" x-text="formatPrice(price * quantity)"></div>
                                                    <div class="quantity-controls" x-show="isSelected">
                                                        <button type="button" class="quantity-btn" @click.stop="decrement">-</button>
                                                        <input type="number" 
                                                               x-model.number="quantity" 
                                                               :min="minQty" 
                                                               :max="maxQty || ''" 
                                                               class="quantity-input" 
                                                               @click.stop>
                                                        <button type="button" class="quantity-btn" @click.stop="increment">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="navigation-buttons">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('custom-package.step1') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4" :disabled="isLoading">
                            <span x-show="!isLoading">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </span>
                            <span x-show="isLoading">
                                <span class="loading-spinner"></span>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
