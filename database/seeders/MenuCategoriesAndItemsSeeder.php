<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuCategoriesAndItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Menu Categories
        $mainCourse = MenuCategory::create([
            'name' => 'Main Course (Makanan Utama)',
            'description' => 'Makanan utama yang terdiri dari nasi, lauk utama, sayur, dan pelengkap',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $appetizer = MenuCategory::create([
            'name' => 'Appetizer (Makanan Pembuka)',
            'description' => 'Makanan pembuka yang biasanya dihidangkan dalam bentuk buffet/snack',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $dessert = MenuCategory::create([
            'name' => 'Dessert (Makanan Penutup)',
            'description' => 'Menu manis yang menyegarkan',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $drink = MenuCategory::create([
            'name' => 'Drink (Minuman)',
            'description' => 'Berbagai jenis minuman',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Create Main Course Menu Items
        $this->createMainCourseItems($mainCourse->id);
        
        // Create Appetizer Menu Items
        $this->createAppetizerItems($appetizer->id);
        
        // Create Dessert Menu Items
        $this->createDessertItems($dessert->id);
        
        // Create Drink Menu Items
        $this->createDrinkItems($drink->id);
    }

    private function createMainCourseItems($categoryId)
    {
        // Nasi
        MenuItem::create([
            'name' => 'Nasi Putih',
            'description' => 'Nasi putih pulen',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'name' => 'Nasi Liwet',
            'description' => 'Nasi yang dimasak dengan santan, daun salam, dan serai',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'name' => 'Nasi Uduk',
            'description' => 'Nasi yang dimasak dengan santan dan rempah-rempah',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 3,
        ]);

        MenuItem::create([
            'name' => 'Nasi Kuning',
            'description' => 'Nasi kuning dengan aroma kunyit dan santan',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 4,
        ]);

        // Ayam
        MenuItem::create([
            'name' => 'Ayam Bakar',
            'description' => 'Ayam yang dibakar dengan bumbu khas',
            'price' => 25000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 5,
        ]);

        MenuItem::create([
            'name' => 'Ayam Goreng Kremes',
            'description' => 'Ayam goreng dengan taburan kremes renyah',
            'price' => 25000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 6,
        ]);

        MenuItem::create([
            'name' => 'Ayam Rica',
            'description' => 'Ayam dengan bumbu rica-rica pedas',
            'price' => 28000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 7,
        ]);

        MenuItem::create([
            'name' => 'Ayam Teriyaki',
            'description' => 'Ayam dengan saus teriyaki ala Jepang',
            'price' => 28000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 8,
        ]);

        // Daging Sapi
        MenuItem::create([
            'name' => 'Rendang',
            'description' => 'Daging sapi yang dimasak dengan rempah-rempah khas Padang',
            'price' => 35000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 9,
        ]);

        MenuItem::create([
            'name' => 'Semur Daging',
            'description' => 'Daging sapi yang dimasak dengan kecap dan rempah-rempah',
            'price' => 32000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 10,
        ]);

        MenuItem::create([
            'name' => 'Daging Lada Hitam',
            'description' => 'Daging sapi dengan saus lada hitam',
            'price' => 35000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 11,
        ]);

        MenuItem::create([
            'name' => 'Empal',
            'description' => 'Daging sapi yang dimasak dengan bumbu manis dan gurih',
            'price' => 32000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 12,
        ]);

        // Ikan
        MenuItem::create([
            'name' => 'Gurame Bakar',
            'description' => 'Ikan gurame yang dibakar dengan bumbu khas',
            'price' => 45000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 13,
        ]);

        MenuItem::create([
            'name' => 'Ikan Fillet Asam Manis',
            'description' => 'Ikan fillet dengan saus asam manis',
            'price' => 35000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 14,
        ]);

        // Pelengkap
        MenuItem::create([
            'name' => 'Perkedel',
            'description' => 'Perkedel kentang dengan daging cincang',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 15,
        ]);

        MenuItem::create([
            'name' => 'Tahu Bacem',
            'description' => 'Tahu yang dimasak dengan bumbu bacem manis',
            'price' => 3000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 16,
        ]);

        MenuItem::create([
            'name' => 'Tempe Mendoan',
            'description' => 'Tempe tipis yang digoreng dengan tepung',
            'price' => 3000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 17,
        ]);

        // Sayuran
        MenuItem::create([
            'name' => 'Capcay',
            'description' => 'Tumis sayuran campur',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 18,
        ]);

        MenuItem::create([
            'name' => 'Tumis Buncis',
            'description' => 'Tumis buncis dengan bumbu bawang',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 19,
        ]);

        MenuItem::create([
            'name' => 'Urap',
            'description' => 'Sayuran rebus dengan bumbu kelapa',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 20,
        ]);

        MenuItem::create([
            'name' => 'Sayur Lodeh',
            'description' => 'Sayuran yang dimasak dengan santan',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 21,
        ]);
    }

    private function createAppetizerItems($categoryId)
    {
        MenuItem::create([
            'name' => 'Salad Buah',
            'description' => 'Campuran buah segar dengan mayones dan keju',
            'price' => 18000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'name' => 'Salad Sayur',
            'description' => 'Campuran sayuran segar dengan dressing',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'name' => 'Roti Isi Smoked Beef',
            'description' => 'Roti dengan isian daging asap',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 3,
        ]);

        MenuItem::create([
            'name' => 'Roti Isi Tuna',
            'description' => 'Roti dengan isian tuna',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 4,
        ]);

        MenuItem::create([
            'name' => 'Roti Isi Egg Mayo',
            'description' => 'Roti dengan isian telur dan mayones',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 5,
        ]);

        MenuItem::create([
            'name' => 'Mini Sandwich',
            'description' => 'Sandwich mini dengan berbagai isian',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 6,
        ]);

        MenuItem::create([
            'name' => 'Martabak Mini',
            'description' => 'Martabak mini dengan isian daging dan telur',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 7,
        ]);

        MenuItem::create([
            'name' => 'Risoles',
            'description' => 'Risoles dengan isian ragout ayam',
            'price' => 8000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 8,
        ]);

        MenuItem::create([
            'name' => 'Lumpia',
            'description' => 'Lumpia dengan isian rebung dan ayam',
            'price' => 8000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 9,
        ]);

        MenuItem::create([
            'name' => 'Tahu Isi',
            'description' => 'Tahu yang diisi dengan sayuran dan digoreng',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 10,
        ]);
    }

    private function createDessertItems($categoryId)
    {
        MenuItem::create([
            'name' => 'Pudding Coklat',
            'description' => 'Pudding dengan rasa coklat',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'name' => 'Pudding Mangga',
            'description' => 'Pudding dengan rasa mangga',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'name' => 'Pudding Vanilla',
            'description' => 'Pudding dengan rasa vanilla',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 3,
        ]);

        MenuItem::create([
            'name' => 'Pudding Caramel',
            'description' => 'Pudding dengan saus caramel',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 4,
        ]);

        MenuItem::create([
            'name' => 'Es Buah',
            'description' => 'Campuran buah dengan sirup dan susu',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'both',
            'is_available' => true,
            'sort_order' => 5,
        ]);

        MenuItem::create([
            'name' => 'Es Campur',
            'description' => 'Campuran buah dan cincau dengan sirup dan susu',
            'price' => 15000,
            'menu_category_id' => $categoryId,
            'type' => 'both',
            'is_available' => true,
            'sort_order' => 6,
        ]);

        MenuItem::create([
            'name' => 'Buah Segar',
            'description' => 'Potongan buah segar (semangka, melon, nanas)',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 7,
        ]);

        MenuItem::create([
            'name' => 'Klepon',
            'description' => 'Kue tradisional dengan isian gula merah',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 8,
        ]);

        MenuItem::create([
            'name' => 'Nagasari',
            'description' => 'Kue tradisional dengan isian pisang',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 9,
        ]);

        MenuItem::create([
            'name' => 'Kue Lapis',
            'description' => 'Kue tradisional berlapis-lapis',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 10,
        ]);

        MenuItem::create([
            'name' => 'Brownies',
            'description' => 'Brownies coklat yang lembut',
            'price' => 8000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 11,
        ]);

        MenuItem::create([
            'name' => 'Mini Cake',
            'description' => 'Kue mini dengan berbagai rasa',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'food',
            'is_available' => true,
            'sort_order' => 12,
        ]);
    }

    private function createDrinkItems($categoryId)
    {
        MenuItem::create([
            'name' => 'Es Teh Manis',
            'description' => 'Teh manis dingin',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'name' => 'Teh Hangat',
            'description' => 'Teh hangat dengan atau tanpa gula',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'name' => 'Air Mineral',
            'description' => 'Air mineral dalam kemasan',
            'price' => 5000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 3,
        ]);

        MenuItem::create([
            'name' => 'Es Jeruk',
            'description' => 'Jus jeruk segar dengan es',
            'price' => 8000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 4,
        ]);

        MenuItem::create([
            'name' => 'Jus Buah',
            'description' => 'Jus buah segar (pilihan: jeruk, mangga, alpukat)',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 5,
        ]);

        MenuItem::create([
            'name' => 'Infused Water',
            'description' => 'Air dengan irisan buah segar',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 6,
        ]);

        MenuItem::create([
            'name' => 'Kopi',
            'description' => 'Kopi hitam atau kopi susu',
            'price' => 10000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 7,
        ]);

        MenuItem::create([
            'name' => 'Teh Tarik',
            'description' => 'Teh susu khas Malaysia',
            'price' => 12000,
            'menu_category_id' => $categoryId,
            'type' => 'beverage',
            'is_available' => true,
            'sort_order' => 8,
        ]);
    }
}
