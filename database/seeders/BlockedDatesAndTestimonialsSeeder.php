<?php

namespace Database\Seeders;

use App\Models\BlockedDate;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BlockedDatesAndTestimonialsSeeder extends Seeder
{
    public function run(): void
    {
        // Create fake blocked dates
        $this->createBlockedDates();
        
        // Create fake testimonials
        $this->createTestimonials();
    }
    
    private function createBlockedDates(): void
    {
        $reasons = [
            'Libur Nasional',
            'Perbaikan Fasilitas',
            'Acara Khusus',
            'Libur Tahunan',
            'Perawatan Rutin'
        ];
        
        $dates = [
            [
                'date' => now()->addDays(5)->format('Y-m-d'),
                'reason' => $reasons[array_rand($reasons)],
                'is_recurring_yearly' => false,
            ],
            [
                'date' => now()->addDays(15)->format('Y-m-d'),
                'reason' => $reasons[array_rand($reasons)],
                'is_recurring_yearly' => false,
            ],
            [
                'date' => now()->addDays(30)->format('Y-m-d'),
                'reason' => $reasons[array_rand($reasons)],
                'is_recurring_yearly' => true,
            ],
            [
                'date' => now()->addDays(45)->format('Y-m-d'),
                'reason' => $reasons[array_rand($reasons)],
                'is_recurring_yearly' => false,
            ],
        ];
        
        foreach ($dates as $date) {
            BlockedDate::create($date);
        }
    }
    
    private function createTestimonials(): void
    {
        $contents = [
            'Tempatnya nyaman banget, pelayanannya ramah dan makanan enak!',
            'Suasana kafe yang cozy, cocok untuk nongkrong santai.',
            'Menu minumannya unik-unik dan rasanya enak!',
            'Pelayanan cepat dan ramah, pasti balik lagi kesini.',
            'Tempat yang cocok untuk berkumpul dengan teman-teman.',
            'Makanannya enak dan harga terjangkau.',
            'Suasana romantis, cocok untuk kencan malam.',
            'Kopi-nya nikmat, tempatnya juga instagramable!',
            'Pilihan menu lengkap, dari makanan berat sampai camilan ringan.',
            'Tempat favorit saya untuk meeting santai.'
        ];
        
        $users = User::take(5)->get();
        
        if ($users->isEmpty()) {
            // If no users exist, create one
            $user = User::factory()->create([
                'name' => 'Guest User',
                'email' => 'guest@example.com',
                'password' => bcrypt('password')
            ]);
            $users = collect([$user]);
        }
        
        foreach (range(1, 10) as $i) {
            Testimonial::create([
                'user_id' => $users->random()->id,
                'content' => $contents[array_rand($contents)],
                'rating' => rand(4, 5),
                'status' => 'approved',
                'is_featured' => rand(0, 1),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
