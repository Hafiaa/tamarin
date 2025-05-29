<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        // Coffee items
        $this->createCoffeeItems();
        
        // Non-coffee items
        $this->createNonCoffeeItems();
        
        // Food items
        $this->createFoodItems();
        
        // Dessert items
        $this->createDessertItems();
        
        // Seasonal specials
        $this->createSeasonalItems();
        
        // Event menu items
        $this->createEventMenuItems();
    }
    
    private function createCoffeeItems(): void
    {
        $coffeeCategory = \App\Models\MenuCategory::where('name', 'Coffee')->first();
        
        $items = [
            [
                'name' => 'Espresso',
                'description' => 'A concentrated form of coffee served in small, strong shots',
                'price' => 25000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and a silky layer of foam',
                'price' => 32000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Iced Latte',
                'description' => 'Espresso with cold milk and ice',
                'price' => 35000,
                'type' => 'cold',
                'is_available' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Caramel Macchiato',
                'description' => 'Espresso with vanilla-flavored syrup, steamed milk and caramel drizzle',
                'price' => 38000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 4,
            ],
        ];
        
        foreach ($items as $item) {
            $coffeeCategory->menuItems()->create($item);
        }
    }
    
    private function createNonCoffeeItems(): void
    {
        $category = \App\Models\MenuCategory::where('name', 'Non-Coffee')->first();
        
        $items = [
            [
                'name' => 'Hot Chocolate',
                'description' => 'Creamy hot chocolate with whipped cream',
                'price' => 28000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Iced Matcha Latte',
                'description' => 'Japanese green tea powder with milk and ice',
                'price' => 35000,
                'type' => 'cold',
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Strawberry Smoothie',
                'description' => 'Fresh strawberries blended with yogurt',
                'price' => 32000,
                'type' => 'cold',
                'is_available' => true,
                'sort_order' => 3,
            ],
        ];
        
        foreach ($items as $item) {
            $category->menuItems()->create($item);
        }
    }
    
    private function createFoodItems(): void
    {
        $category = \App\Models\MenuCategory::where('name', 'Food')->first();
        
        $items = [
            [
                'name' => 'Avocado Toast',
                'description' => 'Sourdough bread with mashed avocado, cherry tomatoes, and poached eggs',
                'price' => 45000,
                'type' => 'breakfast',
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Chicken Panini',
                'description' => 'Grilled chicken with mozzarella, pesto, and sun-dried tomatoes',
                'price' => 55000,
                'type' => 'lunch',
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Caesar Salad',
                'description' => 'Fresh romaine lettuce with Caesar dressing, croutons, and parmesan',
                'price' => 42000,
                'type' => 'lunch',
                'is_available' => true,
                'sort_order' => 3,
            ],
        ];
        
        foreach ($items as $item) {
            $category->menuItems()->create($item);
        }
    }
    
    private function createDessertItems(): void
    {
        $category = \App\Models\MenuCategory::where('name', 'Desserts')->first();
        
        if (!$category) return;
        
        $items = [
            [
                'name' => 'New York Cheesecake',
                'description' => 'Classic cheesecake with strawberry topping',
                'price' => 35000,
                'type' => 'cake',
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Chocolate Brownie',
                'description' => 'Warm chocolate brownie with vanilla ice cream',
                'price' => 28000,
                'type' => 'brownie',
                'is_available' => true,
                'sort_order' => 2,
            ],
        ];
        
        foreach ($items as $item) {
            $category->menuItems()->create($item);
        }
    }
    
    private function createSeasonalItems(): void
    {
        $category = \App\Models\MenuCategory::where('name', 'Seasonal Specials')->first();
        
        $items = [
            [
                'name' => 'Pumpkin Spice Latte',
                'description' => 'Seasonal favorite with espresso, steamed milk, and pumpkin spice',
                'price' => 40000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Peppermint Mocha',
                'description' => 'Chocolate, peppermint, and espresso with steamed milk',
                'price' => 42000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 2,
            ],
        ];
        
        foreach ($items as $item) {
            $category->menuItems()->create($item);
        }
    }
    
    private function createEventMenuItems(): void
    {
        $category = \App\Models\MenuCategory::firstOrCreate(
            ['name' => 'Event Specials'],
            ['description' => 'Special menu items available for a limited time during events', 'is_active' => true]
        );
        
        $items = [
            [
                'name' => 'Midnight Jazz Coffee',
                'description' => 'Exclusive blend with hints of chocolate and caramel, perfect for late-night jazz sessions',
                'price' => 45000,
                'type' => 'hot',
                'is_available' => true,
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'Festival Iced Latte',
                'description' => 'Limited edition iced latte with vanilla and hazelnut syrup, topped with whipped cream',
                'price' => 38000,
                'type' => 'cold',
                'is_available' => true,
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'name' => 'Anniversary Cake',
                'description' => 'Special celebration cake with layers of chocolate and raspberry',
                'price' => 65000,
                'type' => 'dessert',
                'is_available' => true,
                'sort_order' => 3,
                'is_featured' => true,
            ],
            [
                'name' => 'Sunset Mocktail',
                'description' => 'Refreshing blend of tropical fruits with a hint of mint, perfect for summer evenings',
                'price' => 35000,
                'type' => 'beverage',
                'is_available' => true,
                'sort_order' => 4,
                'is_featured' => true,
            ]
        ];
        
        foreach ($items as $item) {
            $category->menuItems()->create($item);
        }
    }
}
