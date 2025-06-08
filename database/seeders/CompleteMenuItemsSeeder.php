<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class CompleteMenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Appetizing starters to begin your meal',
                'sort_order' => 1,
                'items' => [
                    ['name' => 'Bakwan Jagung', 'price' => 15000, 'description' => 'Crispy corn fritters', 'is_available' => true],
                    ['name' => 'Calamary Dory', 'price' => 25000, 'description' => 'Crispy dory calamari', 'is_available' => true],
                    ['name' => 'Calamary Ring', 'price' => 20000, 'description' => 'Crispy calamari rings', 'is_available' => true],
                    ['name' => 'Cheese Roll', 'price' => 22000, 'description' => 'Crispy cheese rolls', 'is_available' => true],
                    ['name' => 'Chicken Wings', 'price' => 35000, 'description' => 'Crispy fried chicken wings', 'is_available' => true],
                    ['name' => 'Fish & Fries', 'price' => 30000, 'description' => 'Crispy fish and fries', 'is_available' => true],
                    ['name' => 'French Fries', 'price' => 18000, 'description' => 'Crispy golden fries', 'is_available' => true],
                    ['name' => 'Mix Platter', 'price' => 45000, 'description' => 'Assorted appetizer platter', 'is_available' => true],
                    ['name' => 'Onion Ring', 'price' => 20000, 'description' => 'Crispy onion rings', 'is_available' => true],
                    ['name' => 'Seblak', 'price' => 25000, 'description' => 'Spicy Indonesian crackers', 'is_available' => true],
                    ['name' => 'Tempe Mendoan', 'price' => 15000, 'description' => 'Lightly fried tempeh', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Hearty and satisfying main dishes',
                'sort_order' => 2,
                'items' => [
                    ['name' => 'Ayam Bakar 3 Sambal', 'price' => 35000, 'description' => 'Grilled chicken with 3 types of sambal', 'is_available' => true],
                    ['name' => 'Ayam Penyet', 'price' => 30000, 'description' => 'Smashed fried chicken with sambal', 'is_available' => true],
                    ['name' => 'Ayam Sambal Matah', 'price' => 32000, 'description' => 'Chicken with Balinese sambal matah', 'is_available' => true],
                    ['name' => 'Carbonara Katsu', 'price' => 40000, 'description' => 'Chicken katsu with carbonara sauce', 'is_available' => true],
                    ['name' => 'Cheese Omelette', 'price' => 25000, 'description' => 'Fluffy cheese omelette', 'is_available' => true],
                    ['name' => 'Dory Sambal Matah', 'price' => 38000, 'description' => 'Dory fish with Balinese sambal matah', 'is_available' => true],
                    ['name' => 'Ikan Nila Asam Manis', 'price' => 35000, 'description' => 'Sweet and sour tilapia', 'is_available' => true],
                    ['name' => 'Ikan Nila Bakar', 'price' => 40000, 'description' => 'Grilled tilapia with sambal', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Fried Rice & Noodles',
                'description' => 'Flavorful rice and noodle dishes',
                'sort_order' => 3,
                'items' => [
                    ['name' => 'Nasi Capcay', 'price' => 28000, 'description' => 'Stir-fried rice with mixed vegetables', 'is_available' => true],
                    ['name' => 'Nasi Goreng Filipina', 'price' => 32000, 'description' => 'Filipino-style fried rice', 'is_available' => true],
                    ['name' => 'Nasi Goreng Hongkong', 'price' => 30000, 'description' => 'Hong Kong-style fried rice', 'is_available' => true],
                    ['name' => 'Nasi Goreng Jepang', 'price' => 35000, 'description' => 'Japanese-style fried rice', 'is_available' => true],
                    ['name' => 'Nasi Goreng Kampung', 'price' => 25000, 'description' => 'Village-style fried rice', 'is_available' => true],
                    ['name' => 'Nasi Goreng Seafood', 'price' => 38000, 'description' => 'Seafood fried rice', 'is_available' => true],
                    ['name' => 'Mie Goreng Jawa', 'price' => 28000, 'description' => 'Javanese fried noodles', 'is_available' => true],
                    ['name' => 'Mie Kari', 'price' => 30000, 'description' => 'Curry noodles', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Pasta',
                'description' => 'Delicious pasta dishes',
                'sort_order' => 4,
                'items' => [
                    ['name' => 'Aglio e Olio', 'price' => 35000, 'description' => 'Spaghetti with garlic and chili', 'is_available' => true],
                    ['name' => 'Bolognesse Carbonara', 'price' => 40000, 'description' => 'Creamy bolognese carbonara', 'is_available' => true],
                    ['name' => 'Dory Alfredo', 'price' => 45000, 'description' => 'Creamy dory alfredo pasta', 'is_available' => true],
                    ['name' => 'Udang Matah', 'price' => 48000, 'description' => 'Prawn pasta with Balinese sambal matah', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Soups',
                'description' => 'Warm and comforting soups',
                'sort_order' => 5,
                'items' => [
                    ['name' => 'Bakso Tahu', 'price' => 25000, 'description' => 'Meatball and tofu soup', 'is_available' => true],
                    ['name' => 'Cream Corn Soup', 'price' => 22000, 'description' => 'Creamy corn soup', 'is_available' => true],
                    ['name' => 'Sop Buntut Kuah/Bakar', 'price' => 45000, 'description' => 'Oxtail soup, choose between soup or grilled', 'is_available' => true],
                    ['name' => 'Sop Iga Kuah/Bakar', 'price' => 42000, 'description' => 'Beef ribs soup, choose between soup or grilled', 'is_available' => true],
                    ['name' => 'Soto Ayam', 'price' => 28000, 'description' => 'Chicken soto soup', 'is_available' => true],
                    ['name' => 'Soto Betawi', 'price' => 30000, 'description' => 'Jakarta-style beef soto', 'is_available' => true],
                    ['name' => 'Chicken Ramen', 'price' => 35000, 'description' => 'Japanese chicken noodle soup', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Steaks & Grills',
                'description' => 'Juicy grilled specialties',
                'sort_order' => 6,
                'items' => [
                    ['name' => 'Steak Sirloin', 'price' => 75000, 'description' => 'Grilled sirloin steak', 'is_available' => true],
                    ['name' => 'Steak Tenderloin', 'price' => 85000, 'description' => 'Grilled tenderloin steak', 'is_available' => true],
                    ['name' => 'Grilled Chicken', 'price' => 45000, 'description' => 'Grilled chicken breast', 'is_available' => true],
                    ['name' => 'Chicken Mozarella', 'price' => 50000, 'description' => 'Grilled chicken with mozzarella', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Rice Bowls',
                'description' => 'Hearty rice bowl meals',
                'sort_order' => 7,
                'items' => [
                    ['name' => 'Beef Barbeque Rice Bowl', 'price' => 45000, 'description' => 'Grilled beef with barbeque sauce', 'is_available' => true],
                    ['name' => 'Beef Lada Hitam Rice Bowl', 'price' => 45000, 'description' => 'Beef with black pepper sauce', 'is_available' => true],
                    ['name' => 'Beef Oseng Mercon Rice Bowl', 'price' => 45000, 'description' => 'Spicy stir-fried beef', 'is_available' => true],
                    ['name' => 'Beef Teriyaki Rice Bowl', 'price' => 45000, 'description' => 'Beef with teriyaki sauce', 'is_available' => true],
                    ['name' => 'Ayam Geprek Mercon Rice Bowl', 'price' => 35000, 'description' => 'Smashed fried chicken with spicy sambal', 'is_available' => true],
                    ['name' => 'Ayam Goreng Mentega Rice Bowl', 'price' => 35000, 'description' => 'Butter fried chicken', 'is_available' => true],
                    ['name' => 'Ayam Suir Sambal Matah Rice Bowl', 'price' => 35000, 'description' => 'Shredded chicken with Balinese sambal', 'is_available' => true],
                    ['name' => 'Barbeque Katsu Rice Bowl', 'price' => 40000, 'description' => 'Chicken katsu with barbeque sauce', 'is_available' => true],
                    ['name' => 'Chicken Teriyaki Rice Bowl', 'price' => 38000, 'description' => 'Chicken with teriyaki sauce', 'is_available' => true],
                    ['name' => 'Cakalang S. Kemangi Rice Bowl', 'price' => 42000, 'description' => 'Smoked skipjack tuna with basil', 'is_available' => true],
                    ['name' => 'Dory Asam Manis Rice Bowl', 'price' => 40000, 'description' => 'Dory fish with sweet and sour sauce', 'is_available' => true],
                    ['name' => 'Dory Sambal Matah Rice Bowl', 'price' => 40000, 'description' => 'Dory fish with Balinese sambal', 'is_available' => true],
                    ['name' => 'Dory Saus Padang Rice Bowl', 'price' => 40000, 'description' => 'Dory fish with Padang sauce', 'is_available' => true],
                    ['name' => 'Cumi Cabe Hijau Rice Bowl', 'price' => 45000, 'description' => 'Squid with green chili', 'is_available' => true],
                    ['name' => 'Cumi Rica-Rica Rice Bowl', 'price' => 45000, 'description' => 'Squid with Manadonese spicy sauce', 'is_available' => true],
                    ['name' => 'Cumi Sambal Kemangi Rice Bowl', 'price' => 45000, 'description' => 'Squid with chili and basil', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Drinks',
                'description' => 'Refreshing beverages',
                'sort_order' => 8,
                'items' => [
                    // Special Drinks
                    ['name' => 'Jahe Merah', 'price' => 20000, 'description' => 'Red ginger drink', 'is_available' => true],
                    ['name' => 'Tamarin Lemonade', 'price' => 22000, 'description' => 'Tamarind lemonade', 'is_available' => true],
                    ['name' => 'Fresh Mint Lemonade', 'price' => 22000, 'description' => 'Refreshing mint lemonade', 'is_available' => true],
                    ['name' => 'Lemon Squash', 'price' => 20000, 'description' => 'Fresh lemon squash', 'is_available' => true],
                    ['name' => 'Rootbeer Float', 'price' => 25000, 'description' => 'Classic rootbeer float', 'is_available' => true],
                    ['name' => 'Lemongrass Squash', 'price' => 20000, 'description' => 'Refreshing lemongrass drink', 'is_available' => true],
                    
                    // Smoothies
                    ['name' => 'Yakult Smoothie', 'price' => 25000, 'description' => 'Yakult blended smoothie', 'is_available' => true],
                    ['name' => 'Lychee Smoothie', 'price' => 25000, 'description' => 'Refreshing lychee smoothie', 'is_available' => true],
                    ['name' => 'Strawberry Smoothie', 'price' => 25000, 'description' => 'Fresh strawberry smoothie', 'is_available' => true],
                    ['name' => 'Mango Smoothie', 'price' => 25000, 'description' => 'Sweet mango smoothie', 'is_available' => true],
                    ['name' => 'Banana Smoothie', 'price' => 22000, 'description' => 'Creamy banana smoothie', 'is_available' => true],
                    ['name' => 'Dragon Fruit Smoothie', 'price' => 28000, 'description' => 'Healthy dragon fruit smoothie', 'is_available' => true],
                    
                    // Milkshakes
                    ['name' => 'Banana Milkshake', 'price' => 28000, 'description' => 'Creamy banana milkshake', 'is_available' => true],
                    ['name' => 'Oreo Milkshake', 'price' => 30000, 'description' => 'Oreo cookie milkshake', 'is_available' => true],
                    ['name' => 'Strawberry Milkshake', 'price' => 30000, 'description' => 'Sweet strawberry milkshake', 'is_available' => true],
                    ['name' => 'Chocolate Milkshake', 'price' => 28000, 'description' => 'Rich chocolate milkshake', 'is_available' => true],
                    ['name' => 'Vanilla Milkshake', 'price' => 25000, 'description' => 'Classic vanilla milkshake', 'is_available' => true],
                    ['name' => 'Nuttella Milkshake', 'price' => 35000, 'description' => 'Creamy Nuttella milkshake', 'is_available' => true],
                    ['name' => 'Green Tea Milkshake', 'price' => 30000, 'description' => 'Refreshing green tea milkshake', 'is_available' => true],
                    
                    // Coffee & Tea
                    ['name' => 'Americano', 'price' => 20000, 'description' => 'Classic black coffee', 'is_available' => true],
                    ['name' => 'Cappuccino', 'price' => 25000, 'description' => 'Espresso with steamed milk foam', 'is_available' => true],
                    ['name' => 'Cafe Latte', 'price' => 25000, 'description' => 'Espresso with steamed milk', 'is_available' => true],
                    ['name' => 'Mochaccino', 'price' => 28000, 'description' => 'Chocolate cappuccino', 'is_available' => true],
                    ['name' => 'Flavoured Latte', 'price' => 30000, 'description' => 'Latte with flavor of your choice', 'is_available' => true],
                    ['name' => 'Kopi Susu Aren', 'price' => 25000, 'description' => 'Coffee with coconut sugar and milk', 'is_available' => true],
                    ['name' => 'Jasmine Tea', 'price' => 15000, 'description' => 'Fragrant jasmine tea', 'is_available' => true],
                    ['name' => 'Lemon Tea', 'price' => 15000, 'description' => 'Refreshing lemon tea', 'is_available' => true],
                    ['name' => 'Lychee Tea', 'price' => 18000, 'description' => 'Sweet lychee flavored tea', 'is_available' => true],
                    ['name' => 'Honey Lemon Milk Tea', 'price' => 22000, 'description' => 'Milk tea with honey and lemon', 'is_available' => true],
                    
                    // Juices
                    ['name' => 'Jus Alpukat', 'price' => 25000, 'description' => 'Avocado juice', 'is_available' => true],
                    ['name' => 'Jus Buah Naga', 'price' => 20000, 'description' => 'Dragon fruit juice', 'is_available' => true],
                    ['name' => 'Jus Strawberry', 'price' => 22000, 'description' => 'Fresh strawberry juice', 'is_available' => true],
                    ['name' => 'Jus Jambu', 'price' => 18000, 'description' => 'Guava juice', 'is_available' => true],
                    ['name' => 'Jus Jeruk', 'price' => 18000, 'description' => 'Orange juice', 'is_available' => true],
                    ['name' => 'Jus Mangga', 'price' => 20000, 'description' => 'Mango juice', 'is_available' => true],
                    ['name' => 'Jus Melon', 'price' => 18000, 'description' => 'Melon juice', 'is_available' => true],
                    ['name' => 'Jus Semangka', 'price' => 15000, 'description' => 'Watermelon juice', 'is_available' => true],
                    ['name' => 'Jus Nanas', 'price' => 18000, 'description' => 'Pineapple juice', 'is_available' => true],
                    ['name' => 'Jus Tomat', 'price' => 15000, 'description' => 'Tomato juice', 'is_available' => true],
                    ['name' => 'Jus Timun', 'price' => 15000, 'description' => 'Cucumber juice', 'is_available' => true],
                    ['name' => 'Tamarin Healthy', 'price' => 20000, 'description' => 'Healthy tamarind drink', 'is_available' => true],
                    ['name' => 'Mix Juice', 'price' => 22000, 'description' => 'Mixed fruit juice', 'is_available' => true],
                    
                    // Mojitos
                    ['name' => 'Virgin Mojito', 'price' => 25000, 'description' => 'Classic virgin mojito', 'is_available' => true],
                    ['name' => 'Strawberry Mojito', 'price' => 28000, 'description' => 'Strawberry flavored mojito', 'is_available' => true],
                    ['name' => 'Lychee Mojito', 'price' => 28000, 'description' => 'Lychee flavored mojito', 'is_available' => true],
                ]
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to end your meal',
                'sort_order' => 9,
                'items' => [
                    // Pancake/Waffle
                    ['name' => 'Pancake/Waffle Green Tea', 'price' => 35000, 'description' => 'Green tea flavored pancake or waffle', 'is_available' => true],
                    ['name' => 'Pancake/Waffle Oreo', 'price' => 35000, 'description' => 'Oreo cookie pancake or waffle', 'is_available' => true],
                    ['name' => 'Pancake/Waffle Peanut Butter', 'price' => 35000, 'description' => 'Peanut butter pancake or waffle', 'is_available' => true],
                    ['name' => 'Pancake/Waffle Vanilla', 'price' => 30000, 'description' => 'Classic vanilla pancake or waffle', 'is_available' => true],
                    ['name' => 'Pancake/Waffle Strawberry', 'price' => 35000, 'description' => 'Strawberry pancake or waffle', 'is_available' => true],
                    ['name' => 'Pancake/Waffle Chocolate', 'price' => 35000, 'description' => 'Chocolate pancake or waffle', 'is_available' => true],
                    
                    // Toast
                    ['name' => 'Toast Nuttela', 'price' => 25000, 'description' => 'Toasted bread with Nuttela', 'is_available' => true],
                    ['name' => 'Toast Strawberry', 'price' => 25000, 'description' => 'Toasted bread with strawberry jam', 'is_available' => true],
                    ['name' => 'Toast Choco Cheese', 'price' => 28000, 'description' => 'Toasted bread with chocolate and cheese', 'is_available' => true],
                    ['name' => 'Toast Choco Peanut', 'price' => 28000, 'description' => 'Toasted bread with chocolate and peanut butter', 'is_available' => true],
                    
                    // Other Desserts
                    ['name' => 'Pisang Goreng', 'price' => 20000, 'description' => 'Fried banana fritters', 'is_available' => true],
                    ['name' => 'Pisang Bakar', 'price' => 25000, 'description' => 'Grilled banana with toppings', 'is_available' => true],
                    ['name' => 'Kue Cubit', 'price' => 15000, 'description' => 'Traditional Indonesian pancake balls', 'is_available' => true],
                    ['name' => 'Poffertjess', 'price' => 25000, 'description' => 'Dutch mini pancakes', 'is_available' => true],
                    ['name' => 'Eskrim Goreng', 'price' => 25000, 'description' => 'Fried ice cream', 'is_available' => true],
                    ['name' => 'Banana Split', 'price' => 35000, 'description' => 'Classic banana split dessert', 'is_available' => true],
                    ['name' => 'Banana Nugget', 'price' => 25000, 'description' => 'Breaded and fried banana', 'is_available' => true],
                    ['name' => 'Ice Cream (Scoop)', 'price' => 15000, 'description' => 'Single scoop of ice cream', 'is_available' => true],
                    ['name' => 'Es Salju Buah', 'price' => 30000, 'description' => 'Mixed fruit shaved ice', 'is_available' => true],
                    ['name' => 'Es Salju Oreo', 'price' => 30000, 'description' => 'Oreo cookie shaved ice', 'is_available' => true],
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = MenuCategory::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                    'sort_order' => $categoryData['sort_order'],
                    'is_active' => true
                ]
            );

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
                        'is_available' => $itemData['is_available'],
                        'sort_order' => 1
                    ]
                );
            }
        }

        $this->command->info('Complete menu items added successfully!');
    }
}
