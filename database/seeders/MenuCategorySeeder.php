<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Coffee',
                'description' => 'Freshly brewed coffee selections',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Non-Coffee',
                'description' => 'Refreshing non-coffee beverages',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Food',
                'description' => 'Delicious meals and snacks',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to satisfy your cravings',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Seasonal Specials',
                'description' => 'Limited time offers',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            MenuCategory::create($category);
        }
    }
}
