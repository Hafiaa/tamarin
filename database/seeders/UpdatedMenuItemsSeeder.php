<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class UpdatedMenuItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing menu items and categories
        MenuItem::truncate();
        MenuCategory::truncate();
        
        // Aktifkan kembali foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Buat direktori untuk gambar default jika belum ada
        $defaultImagePath = storage_path('app/public/default-menu-images');
        if (!file_exists($defaultImagePath)) {
            mkdir($defaultImagePath, 0755, true);
        }

        // Create Menu Categories
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Hidangan pembuka yang lezat',
                'sort_order' => 1,
                'items' => [
                    'Bakwan Jagung' => 25000,
                    'Calamary Dory' => 35000,
                    'Calamary Ring' => 30000,
                    'Cheese Roll' => 28000,
                    'Chicken Wings' => 40000,
                    'Fish & Fries' => 35000,
                    'French Fries' => 25000,
                    'Mix Platter' => 55000,
                    'Onion Ring' => 25000,
                    'Seblak' => 25000,
                    'Tempe Mendoan' => 20000,
                ]
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Hidangan utama yang mengenyangkan',
                'sort_order' => 2,
                'items' => [
                    'Ayam Bakar 3 Sambal' => 45000,
                    'Ayam Penyet' => 40000,
                    'Ayam Sambal Matah' => 42000,
                    'Carbonara Katsu' => 48000,
                    'Cheese Omelette' => 38000,
                    'Dory Sambal Matah' => 48000,
                    'Ikan Nila Asam Manis' => 50000,
                    'Ikan Nila Bakar' => 50000,
                    'Nasi Capcay' => 38000,
                    'Nasi Goreng Filipina' => 40000,
                    'Nasi Goreng Hongkong' => 40000,
                    'Nasi Goreng Jepang' => 42000,
                    'Nasi Goreng Kampung' => 38000,
                    'Nasi Goreng Seafood' => 45000,
                    'Aglio e Olio' => 45000,
                    'Bolognese Carbonara' => 48000,
                    'Dory Alfredo' => 50000,
                    'Udang Matah' => 55000,
                    'Bakso Tahu' => 35000,
                    'Cream Corn Soup' => 30000,
                    'Sop Buntut Kuah/Bakar' => 55000,
                    'Sop Iga Kuah/Bakar' => 60000,
                    'Soto Ayam' => 35000,
                    'Soto Betawi' => 40000,
                    'Chicken Ramen' => 45000,
                    'Mie Goreng Jawa' => 38000,
                    'Mie Kari' => 40000,
                    'Steak Sirloin' => 85000,
                    'Steak Tenderloin' => 95000,
                    'Grilled Chicken' => 50000,
                    'Chicken Mozzarella' => 55000,
                ]
            ],
            [
                'name' => 'Rice Bowls',
                'description' => 'Nasi dengan berbagai macam lauk pilihan',
                'sort_order' => 3,
                'items' => [
                    'Beef Barbeque Rice Bowl' => 45000,
                    'Beef Lada Hitam Rice Bowl' => 45000,
                    'Beef Oseng Mercon Rice Bowl' => 48000,
                    'Beef Teriyaki Rice Bowl' => 45000,
                    'Ayam Geprek Mercon Rice Bowl' => 40000,
                    'Ayam Goreng Mentega Rice Bowl' => 40000,
                    'Ayam Suir Sambal Matah Rice Bowl' => 40000,
                    'Barbeque Katsu Rice Bowl' => 45000,
                    'Chicken Teriyaki Rice Bowl' => 42000,
                    'Cakalang S. Kemangi Rice Bowl' => 45000,
                    'Dory Asam Manis Rice Bowl' => 45000,
                    'Dory Sambal Matah Rice Bowl' => 45000,
                    'Dory Saus Padang Rice Bowl' => 45000,
                    'Cumi Cabe Hijau Rice Bowl' => 48000,
                    'Cumi Rica-Rica Rice Bowl' => 48000,
                    'Cumi Sambal Kemangi Rice Bowl' => 48000,
                ]
            ],
            [
                'name' => 'Beverages',
                'description' => 'Minuman segar dan nikmat',
                'sort_order' => 4,
                'items' => [
                    'Jahe Merah' => 20000,
                    'Tamarin Lemonade' => 25000,
                    'Fresh Mint Lemonade' => 25000,
                    'Lemon Squash Rootbeer' => 25000,
                    'Lemongrass Squash Float' => 25000,
                    'Yakult Smoothie' => 30000,
                    'Lychee Smoothie' => 30000,
                    'Strawberry Smoothie' => 30000,
                    'Mango Smoothie' => 30000,
                    'Banana Smoothie' => 28000,
                    'Dragon Fruit Smoothie' => 32000,
                    'Banana Milkshake' => 32000,
                    'Oreo Milkshake' => 32000,
                    'Strawberry Milkshake' => 32000,
                    'Chocolate Milkshake' => 32000,
                    'Vanilla Milkshake' => 30000,
                    'Nuttella Milkshake' => 35000,
                    'Green Tea Milkshake' => 32000,
                    'Chocolate' => 25000,
                    'Virgin Mojito' => 28000,
                    'Strawberry Mojito' => 30000,
                    'Lychee Mojito' => 30000,
                    'Americano' => 25000,
                    'Cappuccino' => 28000,
                    'Cafe Latte' => 28000,
                    'Mochaccino' => 30000,
                    'Flavoured Latte' => 32000,
                    'Kopi Susu Aren' => 25000,
                    'Jasmine Tea' => 20000,
                    'Lemon Tea' => 20000,
                    'Lychee Tea' => 22000,
                    'Honey Lemon Milk Tea' => 25000,
                    'Jus Alpukat' => 25000,
                    'Jus Buah Naga' => 25000,
                    'Jus Strawberry' => 25000,
                    'Jus Jambu' => 20000,
                    'Jus Jeruk' => 20000,
                    'Jus Mangga' => 25000,
                    'Jus Melon' => 20000,
                    'Jus Semangka' => 20000,
                    'Jus Nanas' => 20000,
                    'Jus Tomat' => 20000,
                    'Jus Timun' => 20000,
                    'Tamarin Healthy' => 25000,
                    'Mix Juice' => 25000,
                ]
            ],
            [
                'name' => 'Desserts',
                'description' => 'Hidangan penutup yang manis',
                'sort_order' => 5,
                'items' => [
                    'Pancake/Waffle Green Tea' => 35000,
                    'Pancake/Waffle Oreo' => 35000,
                    'Pancake/Waffle Peanut Butter' => 35000,
                    'Pancake/Waffle Vanilla' => 32000,
                    'Pancake/Waffle Strawberry' => 32000,
                    'Pancake/Waffle Chocolate' => 32000,
                    'Toast Nuttela' => 30000,
                    'Toast Strawberry' => 30000,
                    'Toast Choco Cheese' => 30000,
                    'Toast Choco Peanut' => 30000,
                    'Pisang Goreng' => 25000,
                    'Pisang Bakar' => 25000,
                    'Kue Cubit' => 20000,
                    'Poffertjess' => 30000,
                    'Eskrim Goreng' => 35000,
                    'Banana Split' => 35000,
                    'Banana Nugget' => 30000,
                    'Ice Cream (Scoop)' => 15000,
                    'Es Salju Buah' => 35000,
                    'Es Salju Oreo' => 35000,
                ]
            ]
        ];

        // Daftar gambar default berdasarkan kategori
        $defaultImages = [
            'appetizers' => 'appetizer.jpg',
            'main' => 'main-course.jpg',
            'desserts' => 'dessert.jpg',
            'drinks' => 'drink.jpg',
            'default' => 'default.jpg'
        ];
        
        // Buat gambar default jika belum ada
        foreach ($defaultImages as $type => $filename) {
            $filePath = storage_path("app/public/default-menu-images/{$filename}");
            if (!file_exists($filePath)) {
                // Buat gambar kosong dengan warna berbeda untuk setiap tipe
                $im = imagecreatetruecolor(800, 600);
                $colors = [
                    'appetizers' => [255, 200, 150], // Oranye muda
                    'main' => [200, 230, 200], // Hijau muda
                    'desserts' => [255, 200, 200], // Merah muda
                    'drinks' => [200, 200, 255], // Biru muda
                    'default' => [230, 230, 230] // Abu-abu
                ];
                $color = $colors[$type] ?? $colors['default'];
                $bgColor = imagecolorallocate($im, $color[0], $color[1], $color[2]);
                $textColor = imagecolorallocate($im, 0, 0, 0);
                
                imagefill($im, 0, 0, $bgColor);
                
                // Tambahkan teks
                $text = strtoupper(str_replace('-', ' ', $type));
                $font = 5; // Ukuran font built-in (1-5)
                $textWidth = imagefontwidth($font) * strlen($text);
                $textX = (800 - $textWidth) / 2;
                $textY = 300 - (imagefontheight($font) / 2);
                
                imagestring($im, $font, $textX, $textY, $text, $textColor);
                
                // Simpan gambar
                imagejpeg($im, $filePath, 90);
                imagedestroy($im);
            }
        }
        
        // Create categories and menu items
        foreach ($categories as $categoryData) {
            $category = MenuCategory::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
            ]);

            $sortOrder = 1;
            foreach ($categoryData['items'] as $itemName => $price) {
                // Create menu item
                $menuItem = MenuItem::create([
                    'name' => $itemName,
                    'description' => 'Nikmati ' . $itemName . ' yang lezat dan menggugah selera',
                    'price' => $price,
                    'menu_category_id' => $category->id,
                    'type' => 'food',
                    'is_available' => true,
                    'sort_order' => $sortOrder++,
                ]);
                
                // Tentukan tipe gambar berdasarkan kategori
                $categoryType = strtolower(explode(' ', $category->name)[0]);
                $imageTypes = ['appetizers', 'main', 'desserts', 'drinks'];
                $imageType = in_array($categoryType, $imageTypes) ? $categoryType : 'default';
                $defaultImage = "default-menu-images/{$defaultImages[$imageType]}";
                
                // Tambahkan gambar default ke menu item
                try {
                    $menuItem
                        ->addMedia(storage_path("app/public/{$defaultImage}"))
                        ->preservingOriginal()
                        ->withResponsiveImages()
                        ->toMediaCollection('menu_items');
                } catch (\Exception $e) {
                    // Log error tapi lanjutkan proses
                    if (app()->environment('local')) {
                        echo "[WARNING] Gagal menambahkan gambar default untuk {$itemName}: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
    }
}
