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
        <span>Rp{{ number_format($price, 0, ',', '.') }}</span>
    </div>
    
    <!-- Food Image -->
    <div class="mb-4 h-48 bg-white bg-opacity-20 rounded-lg overflow-hidden">
        <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover">
    </div>
    
    <!-- Food Info -->
    <h3 class="text-xl font-bold mb-2">{{ $name }}</h3>
    
    <!-- Rating -->
    <div class="rating mb-3">
        @for($i = 1; $i <= 5; $i++)
            @if($i <= $rating)
                <i class="fas fa-star"></i>
            @elseif($i - 0.5 <= $rating)
                <i class="fas fa-star-half-alt"></i>
            @else
                <i class="far fa-star"></i>
            @endif
        @endfor
        <span class="ml-1">{{ number_format($rating, 1) }}</span>
    </div>
    
    <!-- Description -->
    <p class="text-sm mb-4">{{ $description }}</p>
    
    <!-- Order Button -->
    <button class="order-btn w-full">
        <i class="fas fa-shopping-cart mr-2"></i> Order Now
    </button>
</div>
