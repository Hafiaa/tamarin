<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewMenuItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Menu Categories if they don't exist
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Delicious starters to begin your meal',
                'sort_order' => 1,
                'items' => [
                    [
                        'name' => 'Spring Rolls',
                        'description' => 'Crispy vegetable spring rolls with sweet chili sauce',
                        'price' => 35000,
                    ],
                    [
                        'name' => 'Chicken Satay',
                        'description' => 'Grilled chicken skewers with peanut sauce',
                        'price' => 40000,
                    ],
                    [
                        'name' => 'Beef Empanadas',
                        'description' => 'Crispy pastry filled with seasoned ground beef',
                        'price' => 45000,
                    ],
                ]
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Hearty and satisfying main dishes',
                'sort_order' => 2,
                'items' => [
                    [
                        'name' => 'Grilled Salmon',
                        'description' => 'Fresh salmon fillet with lemon butter sauce',
                        'price' => 120000,
                    ],
                    [
                        'name' => 'Beef Rendang',
                        'description' => 'Tender beef in rich and spicy coconut curry',
                        'price' => 85000,
                    ],
                    [
                        'name' => 'Chicken Katsu Curry',
                        'description' => 'Crispy breaded chicken with Japanese curry sauce',
                        'price' => 75000,
                    ],
                ]
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to end your meal',
                'sort_order' => 3,
                'items' => [
                    [
                        'name' => 'Chocolate Lava Cake',
                        'description' => 'Warm chocolate cake with a molten center, served with vanilla ice cream',
                        'price' => 55000,
                    ],
                    [
                        'name' => 'Mango Sticky Rice',
                        'description' => 'Sweet sticky rice with fresh mango and coconut milk',
                        'price' => 45000,
                    ],
                ]
            ],
            [
                'name' => 'Beverages',
                'description' => 'Refreshing drinks',
                'sort_order' => 4,
                'items' => [
                    [
                        'name' => 'Iced Matcha Latte',
                        'description' => 'Refreshing iced green tea latte',
                        'price' => 35000,
                    ],
                    [
                        'name' => 'Fresh Orange Juice',
                        'description' => 'Freshly squeezed orange juice',
                        'price' => 30000,
                    ],
                ]
            ]
        ];


        foreach ($categories as $categoryData) {
            // Check if category exists
            $category = MenuCategory::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                    'sort_order' => $categoryData['sort_order'],
                    'is_active' => true
                ]
            );

            // Add menu items
            foreach ($categoryData['items'] as $itemData) {
                MenuItem::firstOrCreate(
                    [
                        'name' => $itemData['name'],
                        'menu_category_id' => $category->id
                    ],
                    [
                        'description' => $itemData['description'],
                        'price' => $itemData['price'],
                        'type' => 'food',
                        'is_available' => true,
                        'sort_order' => 1
                    ]
                );
            }
        }

        $this->command->info('Menu items added successfully!');
    }
}
