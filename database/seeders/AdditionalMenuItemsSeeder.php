<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class AdditionalMenuItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Define categories and their items
        $categories = [
            [
                'name' => 'Desserts - Pancake/Waffle',
                'description' => 'Sweet and fluffy pancakes and waffles',
                'sort_order' => 12,
                'items' => [
                    ['name' => 'Pancake/Waffle Green Tea', 'price' => 35000],
                    ['name' => 'Pancake/Waffle Oreo', 'price' => 35000],
                    ['name' => 'Pancake/Waffle Peanut Butter', 'price' => 35000],
                    ['name' => 'Pancake/Waffle Vanilla', 'price' => 30000],
                    ['name' => 'Pancake/Waffle Strawberry', 'price' => 35000],
                    ['name' => 'Pancake/Waffle Chocolate', 'price' => 35000],
                ]
            ],
            [
                'name' => 'Desserts - Others',
                'description' => 'Sweet treats to end your meal',
                'sort_order' => 13,
                'items' => [
                    ['name' => 'Toast Nuttela', 'price' => 35000],
                    ['name' => 'Toast Strawberry', 'price' => 30000],
                    ['name' => 'Toast Choco Cheese', 'price' => 35000],
                    ['name' => 'Toast Choco Peanut', 'price' => 35000],
                    ['name' => 'Pisang Goreng', 'price' => 20000],
                    ['name' => 'Pisang Bakar', 'price' => 25000],
                    ['name' => 'Kue Cubit', 'price' => 20000],
                    ['name' => 'Poffertjess', 'price' => 30000],
                    ['name' => 'Eskrim Goreng', 'price' => 30000],
                    ['name' => 'Banana Split', 'price' => 35000],
                    ['name' => 'Banana Nugget', 'price' => 25000],
                    ['name' => 'Ice Cream (Scoop)', 'price' => 20000],
                    ['name' => 'Es Salju Buah', 'price' => 35000],
                    ['name' => 'Es Salju Oreo', 'price' => 35000],
                ]
            ],
            [
                'name' => 'Drinks - Smoothies',
                'description' => 'Refreshing fruit smoothies',
                'sort_order' => 14,
                'items' => [
                    ['name' => 'Yakult Smoothie', 'price' => 30000],
                    ['name' => 'Lychee Smoothie', 'price' => 30000],
                    ['name' => 'Strawberry Smoothie', 'price' => 30000],
                    ['name' => 'Mango Smoothie', 'price' => 30000],
                    ['name' => 'Banana Smoothie', 'price' => 30000],
                    ['name' => 'Dragon Fruit Smoothie', 'price' => 35000],
                ]
            ],
            [
                'name' => 'Drinks - Milkshakes',
                'description' => 'Creamy and delicious milkshakes',
                'sort_order' => 15,
                'items' => [
                    ['name' => 'Banana Milkshake', 'price' => 35000],
                    ['name' => 'Oreo Milkshake', 'price' => 35000],
                    ['name' => 'Strawberry Milkshake', 'price' => 35000],
                    ['name' => 'Chocolate Milkshake', 'price' => 35000],
                    ['name' => 'Vanilla Milkshake', 'price' => 35000],
                    ['name' => 'Nuttella Milkshake', 'price' => 40000],
                    ['name' => 'Green Tea Milkshake', 'price' => 38000],
                ]
            ],
            [
                'name' => 'Drinks - Special',
                'description' => 'Signature beverages',
                'sort_order' => 16,
                'items' => [
                    ['name' => 'Tamarin Healthy', 'price' => 35000],
                    ['name' => 'Virgin Mojito', 'price' => 35000],
                    ['name' => 'Strawberry Mojito', 'price' => 35000],
                    ['name' => 'Lychee Mojito', 'price' => 35000],
                    ['name' => 'Rootbeer Float', 'price' => 35000],
                ]
            ]
        ];

        // Create categories and menu items
        foreach ($categories as $categoryData) {
            $category = MenuCategory::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                    'sort_order' => $categoryData['sort_order'],
                    'is_active' => true
                ]
            );

            foreach ($categoryData['items'] as $item) {
                MenuItem::firstOrCreate(
                    [
                        'name' => $item['name'],
                        'menu_category_id' => $category->id
                    ],
                    [
                        'description' => $item['description'] ?? $item['name'],
                        'price' => $item['price'],
                        'type' => 'food',
                        'is_available' => true,
                        'sort_order' => 1
                    ]
                );
            }
        }

        $this->command->info('Additional menu items added successfully!');
    }
}
