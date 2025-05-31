@props([
    'name',
    'price',
    'rating',
    'description',
    'image',
    'color' => 'green' // 'green' or 'orange'
])

<div class="food-card {{ $color }} p-6 relative">
    <!-- Price Tag -->
    <div class="price-tag">
        <span>${{ number_format($price, 2) }}</span>
    </div>
    
    <!-- Food Image -->
    <div style="height: 12rem; overflow: hidden; border-radius: 0.5rem; margin-bottom: 1rem;">
        <img src="{{ $image }}" alt="{{ $name }}" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    
    <!-- Food Info -->
    <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $name }}</h3>
    
    <!-- Rating -->
    <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
        <div class="rating">
            @for ($i = 0; $i < 5; $i++)
                @if ($i < $rating)
                    <i class="fas fa-star"></i>
                @else
                    <i class="far fa-star"></i>
                @endif
            @endfor
        </div>
        <span style="margin-left: 0.5rem; font-size: 0.875rem;">({{ $rating }})</span>
    </div>
    
    <!-- Description -->
    <p style="margin-bottom: 1rem;">{{ $description }}</p>
    
    <!-- Order Button -->
    <button class="order-btn">
        <i class="fas fa-shopping-cart" style="margin-right: 0.5rem;"></i> Add to Order
    </button>
</div>
