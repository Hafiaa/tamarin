<?php

namespace Database\Seeders;

use App\Models\EventType;
use App\Models\PackageTemplate;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Wedding Event Type
        $wedding = EventType::create([
            'name' => 'Pernikahan',
            'slug' => 'pernikahan',
            'description' => 'Pernikahan istimewa dengan suasana romantis dan elegan',
            'is_active' => true,
        ]);

        // Wedding Packages
        PackageTemplate::create([
            'name' => 'Paket Pernikahan Silver',
            'slug' => 'paket-pernikahan-silver',
            'description' => 'Paket pernikahan sederhana untuk acara intim',
            'base_price' => 25000000,
            'event_type_id' => $wedding->id,
            'is_active' => true,
        ]);

        PackageTemplate::create([
            'name' => 'Paket Pernikahan Gold',
            'slug' => 'paket-pernikahan-gold',
            'description' => 'Paket pernikahan lengkap dengan dekorasi premium',
            'base_price' => 50000000,
            'event_type_id' => $wedding->id,
            'is_active' => true,
        ]);

        // Engagement Event Type
        $engagement = EventType::create([
            'name' => 'Lamaran',
            'slug' => 'lamaran',
            'description' => 'Momen spesial lamaran dengan dekorasi romantis',
            'is_active' => true,
        ]);

        // Engagement Packages
        PackageTemplate::create([
            'name' => 'Paket Lamaran Basic',
            'slug' => 'paket-lamaran-basic',
            'description' => 'Paket lamaran sederhana namun berkesan',
            'base_price' => 15000000,
            'event_type_id' => $engagement->id,
            'is_active' => true,
        ]);

        // Reunion Event Type
        $reunion = EventType::create([
            'name' => 'Reuni',
            'slug' => 'reuni',
            'description' => 'Acara reuni sekolah atau kampus yang berkesan',
            'is_active' => true,
        ]);

        // Reunion Packages
        PackageTemplate::create([
            'name' => 'Paket Reuni Akbar',
            'slug' => 'paket-reuni-akbar',
            'description' => 'Paket lengkap untuk acara reuni besar',
            'base_price' => 30000000,
            'event_type_id' => $reunion->id,
            'is_active' => true,
        ]);

        // Family Gathering Event Type
        $familyGathering = EventType::create([
            'name' => 'Family Gathering',
            'slug' => 'family-gathering',
            'description' => 'Acara kebersamaan keluarga yang menyenangkan',
            'is_active' => true,
        ]);

        // Family Gathering Packages
        PackageTemplate::create([
            'name' => 'Paket Family Gathering Reguler',
            'slug' => 'paket-family-gathering-reguler',
            'description' => 'Paket family gathering dengan fasilitas standar',
            'base_price' => 20000000,
            'event_type_id' => $familyGathering->id,
            'is_active' => true,
        ]);

        // Birthday Event Type
        $birthday = EventType::create([
            'name' => 'Ulang Tahun',
            'slug' => 'ulang-tahun',
            'description' => 'Perayaan ulang tahun yang spesial dan berkesan',
            'is_active' => true,
        ]);

        // Birthday Packages
        PackageTemplate::create([
            'name' => 'Paket Ulang Tahun Anak',
            'slug' => 'paket-ulang-tahun-anak',
            'description' => 'Paket ulang tahun anak dengan dekorasi karakter favorit',
            'base_price' => 15000000,
            'event_type_id' => $birthday->id,
            'is_active' => true,
        ]);

        PackageTemplate::create([
            'name' => 'Paket Ulang Tahun Dewasa',
            'slug' => 'paket-ulang-tahun-dewasa',
            'description' => 'Paket ulang tahun dewasa dengan konsep elegan',
            'base_price' => 25000000,
            'event_type_id' => $birthday->id,
            'is_active' => true,
        ]);
    }
}
