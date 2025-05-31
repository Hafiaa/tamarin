@extends('layouts.app')

@section('title', 'Our Menu')

@section('content')
<div class="food-hero">
    <div style="max-width: 1200px; margin: 0 auto; padding: 4rem 1rem; text-align: center;">
        <h1>Our Delicious Menu</h1>
        <p style="max-width: 800px; margin: 0 auto 2rem auto; font-size: 1.25rem; line-height: 1.6;">
            Discover our wide variety of delicious dishes made with fresh ingredients and authentic flavors
        </p>
        <div style="margin-top: 2rem;">
            <a href="#menu" class="btn">View Menu</a>
            <a href="#" class="btn btn-outline">Order Online</a>
        </div>
    </div>
</div>

<div id="menu" style="max-width: 1400px; margin: 0 auto; padding: 3rem 1rem;">
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
        
        <!-- Food Item 4 -->
        <x-food-item 
            name="Paneer Tikka" 
            :price="30000" 
            :rating="4.7"
            description="Cubes of paneer marinated in spiced yogurt and grilled to perfection, served with mint chutney."
            image="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGFuZWVyJTIwdGlra2F8ZW58MHx8MHx8fDA%3D"
            color="orange"
        />
        
        <!-- Food Item 5 -->
        <x-food-item 
            name="Butter Chicken" 
            :price="40000" 
            :rating="4.9"
            description="Tender chicken pieces cooked in a rich, creamy tomato-based gravy with aromatic spices."
            image="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YnV0dGVyJTIwY2hpY2tlbnxlbnwwfHwwfHx8MA%3D%3D"
            color="green"
        />
        
        <!-- Food Item 6 -->
        <x-food-item 
            name="Vegetable Biryani" 
            :price="35000" 
            :rating="4.5"
            description="Fragrant basmati rice cooked with mixed vegetables and aromatic spices, served with raita."
            image="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dmVnZXRhYmxlJTIwYmlyeWFuaXxlbnwwfHwwfHx8MA%3D%3D"
            color="orange"
        />
    </div>
</div>
@endsection
