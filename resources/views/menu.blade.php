@extends('layouts.app')

@section('title', 'Our Menu')

@section('content')
<!-- Hero Section -->
<section class="food-hero">
    <div class="container mx-auto px-4 py-16">
        <h1>Delicious Food Delivered To You</h1>
        <p>Order your favorite meals from the best restaurants in town. Fast delivery, delicious food, and great prices!</p>
        <div class="mt-8">
            <a href="#menu" class="btn">Order Now</a>
            <a href="#menu" class="btn btn-outline">View Menu</a>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section id="menu" class="py-12 bg-food-bg">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-food-dark">Our Special Menu</h2>
        
        <div class="food-grid">
            <!-- Food Item 1 -->
            <x-food-item 
                name="Jeera Chawal" 
                :price="25000" 
                :rating="4.5"
                description="Fragrant basmati rice cooked with cumin seeds and aromatic spices, a perfect side dish."
                image="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8amVlcmElMjBjaGF3YWx8ZW58MHx8MHx8fDA%3D"
                color="green"
            />
            
            <!-- Food Item 2 -->
            <x-food-item 
                name="Kadai Chicken" 
                :price="45000" 
                :rating="4.8"
                description="Tender chicken pieces cooked with bell peppers, onions, and freshly ground spices in a rich tomato gravy."
                image="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a2FkYWklMjBjaGlja2VufGVufDB8fDB8fHww"
                color="orange"
            />
            
            <!-- Food Item 3 -->
            <x-food-item 
                name="Palak Paneer" 
                :price="35000" 
                :rating="4.6"
                description="Fresh spinach cooked with homemade cottage cheese in a creamy, spiced gravy, served with naan."
                image="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGFsYWslMjBwYW5lZXJ8ZW58MHx8MHx8fDA%3D"
                color="green"
            />
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-food-button text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Hungry? We're open now!</h2>
        <p class="text-xl mb-8">Order online or visit us today</p>
        <a href="#" class="bg-white text-food-button font-bold py-3 px-8 rounded-lg hover:bg-opacity-90 transition-colors duration-200">
            Order Now <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>
@endsection
