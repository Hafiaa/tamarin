<?php

namespace Database\Seeders;

use App\Models\EventType;
use App\Models\PackageTemplate;
use App\Models\ServiceItem;
use Illuminate\Database\Seeder;

class PackageTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get event types
        $this->createEventTypes();
        
        // Create service items
        $this->createServiceItems();
        
        // Create package templates
        $this->createPackageTemplates();
    }
    
    private function createEventTypes()
    {
        // Create event types if they don't exist
        if (EventType::count() === 0) {
            $eventTypes = [
                [
                    'name' => 'Pernikahan',
                    'description' => 'Acara pernikahan dengan berbagai pilihan paket',
                    'is_active' => true,
                    'slug' => 'pernikahan',
                ],
                [
                    'name' => 'Ulang Tahun',
                    'description' => 'Acara ulang tahun dengan berbagai pilihan paket',
                    'is_active' => true,
                    'slug' => 'ulang-tahun',
                ],
                [
                    'name' => 'Gathering',
                    'description' => 'Acara gathering perusahaan atau komunitas',
                    'is_active' => true,
                    'slug' => 'gathering',
                ],
                [
                    'name' => 'Arisan',
                    'description' => 'Acara arisan keluarga atau teman',
                    'is_active' => true,
                    'slug' => 'arisan',
                ],
                [
                    'name' => 'Lamaran',
                    'description' => 'Acara lamaran atau pertunangan',
                    'is_active' => true,
                    'slug' => 'lamaran',
                ],
            ];
            
            foreach ($eventTypes as $eventType) {
                EventType::create($eventType);
            }
        }
    }
    
    private function createServiceItems()
    {
        // Create service items if they don't exist
        if (ServiceItem::count() === 0) {
            $serviceItems = [
                // Catering
                [
                    'name' => 'Catering Paket Silver',
                    'description' => 'Paket catering dasar untuk acara kecil (Nasi Putih, 1 Lauk Utama, 1 Sayur, 1 Snack, 1 Dessert, Minuman)',
                    'price' => 50000,
                    'category' => 'catering',
                    'is_available' => true,
                ],
                [
                    'name' => 'Catering Paket Gold',
                    'description' => 'Paket catering menengah (Nasi Putih, 2 Lauk Utama, 2 Sayur, 2 Snack, 2 Dessert, Minuman, Buah)',
                    'price' => 75000,
                    'category' => 'catering',
                    'is_available' => true,
                ],
                [
                    'name' => 'Catering Paket Platinum',
                    'description' => 'Paket catering premium (Nasi Putih, 3 Lauk Utama, 3 Sayur, 3 Snack, 3 Dessert, Minuman, Buah, Es Krim)',
                    'price' => 100000,
                    'category' => 'catering',
                    'is_available' => true,
                ],
                // Venue
                [
                    'name' => 'Venue Indoor (Kapasitas 50 orang)',
                    'description' => 'Sewa venue indoor dengan kapasitas hingga 50 orang',
                    'price' => 5000000,
                    'category' => 'venue',
                    'is_available' => true,
                ],
                [
                    'name' => 'Venue Outdoor (Kapasitas 100 orang)',
                    'description' => 'Sewa venue outdoor dengan kapasitas hingga 100 orang',
                    'price' => 8000000,
                    'category' => 'venue',
                    'is_available' => true,
                ],
                [
                    'name' => 'Venue VIP (Kapasitas 150 orang)',
                    'description' => 'Sewa venue VIP dengan kapasitas hingga 150 orang',
                    'price' => 12000000,
                    'category' => 'venue',
                    'is_available' => true,
                ],
                // Dekorasi
                [
                    'name' => 'Dekorasi Standar',
                    'description' => 'Dekorasi standar dengan tema sederhana',
                    'price' => 3000000,
                    'category' => 'decoration',
                    'is_available' => true,
                ],
                [
                    'name' => 'Dekorasi Premium',
                    'description' => 'Dekorasi premium dengan bunga segar dan aksesoris mewah',
                    'price' => 7000000,
                    'category' => 'decoration',
                    'is_available' => true,
                ],
                [
                    'name' => 'Dekorasi Mewah',
                    'description' => 'Dekorasi mewah dengan bunga impor dan aksesoris premium',
                    'price' => 12000000,
                    'category' => 'decoration',
                    'is_available' => true,
                ],
                // Hiburan
                [
                    'name' => 'Live Music (Akustik)',
                    'description' => 'Hiburan musik akustik (2 musisi, 2 set @ 45 menit)',
                    'price' => 2500000,
                    'category' => 'entertainment',
                    'is_available' => true,
                ],
                [
                    'name' => 'Live Music (Full Band)',
                    'description' => 'Hiburan musik full band (4-5 musisi, 2 set @ 45 menit)',
                    'price' => 5000000,
                    'category' => 'entertainment',
                    'is_available' => true,
                ],
                [
                    'name' => 'DJ Performance',
                    'description' => 'Hiburan DJ (3 jam)',
                    'price' => 3500000,
                    'category' => 'entertainment',
                    'is_available' => true,
                ],
                // Fotografi
                [
                    'name' => 'Fotografi Standar',
                    'description' => 'Layanan fotografi standar dengan 1 fotografer (4 jam)',
                    'price' => 1500000,
                    'category' => 'photography',
                    'is_available' => true,
                ],
                [
                    'name' => 'Fotografi Premium',
                    'description' => 'Layanan fotografi premium dengan 2 fotografer dan 1 videografer (8 jam)',
                    'price' => 5000000,
                    'category' => 'photography',
                    'is_available' => true,
                ],
                [
                    'name' => 'Fotografi Lengkap',
                    'description' => 'Layanan fotografi lengkap dengan 2 fotografer, 1 videografer, dan drone (Full day)',
                    'price' => 10000000,
                    'category' => 'photography',
                    'is_available' => true,
                ],
            ];
            
            foreach ($serviceItems as $item) {
                ServiceItem::create($item);
            }
        }
    }
    
    private function createPackageTemplates()
    {
        // Get event types
        $wedding = EventType::where('name', 'Pernikahan')->first();
        $birthday = EventType::where('name', 'Ulang Tahun')->first();
        $gathering = EventType::where('name', 'Gathering')->first();
        $arisan = EventType::where('name', 'Arisan')->first();
        $engagement = EventType::where('name', 'Lamaran')->first();
        
        // Get service items
        $cateringSilver = ServiceItem::where('name', 'Catering Paket Silver')->first();
        $cateringGold = ServiceItem::where('name', 'Catering Paket Gold')->first();
        $cateringPlatinum = ServiceItem::where('name', 'Catering Paket Platinum')->first();
        
        $venueSmall = ServiceItem::where('name', 'Venue Indoor (Kapasitas 50 orang)')->first();
        $venueMedium = ServiceItem::where('name', 'Venue Outdoor (Kapasitas 100 orang)')->first();
        $venueLarge = ServiceItem::where('name', 'Venue VIP (Kapasitas 150 orang)')->first();
        
        $decorStandard = ServiceItem::where('name', 'Dekorasi Standar')->first();
        $decorPremium = ServiceItem::where('name', 'Dekorasi Premium')->first();
        $decorLuxury = ServiceItem::where('name', 'Dekorasi Mewah')->first();
        
        $musicAcoustic = ServiceItem::where('name', 'Live Music (Akustik)')->first();
        $musicBand = ServiceItem::where('name', 'Live Music (Full Band)')->first();
        $dj = ServiceItem::where('name', 'DJ Performance')->first();
        
        $photoStandard = ServiceItem::where('name', 'Fotografi Standar')->first();
        $photoPremium = ServiceItem::where('name', 'Fotografi Premium')->first();
        $photoComplete = ServiceItem::where('name', 'Fotografi Lengkap')->first();
        
        // Create package templates
        $this->createWeddingPackages($wedding, $cateringSilver, $cateringGold, $cateringPlatinum, $venueSmall, $venueMedium, $venueLarge, $decorStandard, $decorPremium, $decorLuxury, $musicAcoustic, $musicBand, $photoStandard, $photoPremium, $photoComplete);
        
        $this->createBirthdayPackages($birthday, $cateringSilver, $cateringGold, $venueSmall, $venueMedium, $decorStandard, $decorPremium, $musicAcoustic, $dj, $photoStandard);
        
        $this->createGatheringPackages($gathering, $cateringSilver, $cateringGold, $cateringPlatinum, $venueSmall, $venueMedium, $venueLarge, $decorStandard, $musicAcoustic, $photoStandard);
        
        $this->createArisanPackages($arisan, $cateringSilver, $cateringGold, $venueSmall, $decorStandard, $musicAcoustic);
        
        $this->createEngagementPackages($engagement, $cateringGold, $cateringPlatinum, $venueSmall, $venueMedium, $decorStandard, $decorPremium, $musicAcoustic, $photoStandard, $photoPremium);
    }
    
    private function createWeddingPackages($eventType, $cateringSilver, $cateringGold, $cateringPlatinum, $venueSmall, $venueMedium, $venueLarge, $decorStandard, $decorPremium, $decorLuxury, $musicAcoustic, $musicBand, $photoStandard, $photoPremium, $photoComplete)
    {
        // Wedding Silver Package
        $silverPackage = PackageTemplate::create([
            'name' => 'Paket Pernikahan Silver',
            'slug' => 'paket-pernikahan-silver',
            'description' => 'Paket pernikahan dasar untuk acara intim dengan kapasitas hingga 50 orang',
            'base_price' => 10000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $silverPackage->serviceItems()->attach([
            $cateringGold->id => ['quantity' => 50, 'custom_price' => $cateringGold->price * 50, 'notes' => 'Catering untuk 50 orang'],
            $venueSmall->id => ['quantity' => 1, 'custom_price' => $venueSmall->price, 'notes' => 'Venue untuk 50 orang'],
            $decorStandard->id => ['quantity' => 1, 'custom_price' => $decorStandard->price, 'notes' => 'Dekorasi standar'],
            $musicAcoustic->id => ['quantity' => 1, 'custom_price' => $musicAcoustic->price, 'notes' => 'Live music akustik'],
            $photoStandard->id => ['quantity' => 1, 'custom_price' => $photoStandard->price, 'notes' => 'Fotografi standar'],
        ]);
        
        // Wedding Gold Package
        $goldPackage = PackageTemplate::create([
            'name' => 'Paket Pernikahan Gold',
            'slug' => 'paket-pernikahan-gold',
            'description' => 'Paket pernikahan menengah untuk acara dengan kapasitas hingga 100 orang',
            'base_price' => 20000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $goldPackage->serviceItems()->attach([
            $cateringGold->id => ['quantity' => 100, 'custom_price' => $cateringGold->price * 100, 'notes' => 'Catering untuk 100 orang'],
            $venueMedium->id => ['quantity' => 1, 'custom_price' => $venueMedium->price, 'notes' => 'Venue untuk 100 orang'],
            $decorPremium->id => ['quantity' => 1, 'custom_price' => $decorPremium->price, 'notes' => 'Dekorasi premium'],
            $musicBand->id => ['quantity' => 1, 'custom_price' => $musicBand->price, 'notes' => 'Live music full band'],
            $photoPremium->id => ['quantity' => 1, 'custom_price' => $photoPremium->price, 'notes' => 'Fotografi premium'],
        ]);
        
        // Wedding Platinum Package
        $platinumPackage = PackageTemplate::create([
            'name' => 'Paket Pernikahan Platinum',
            'slug' => 'paket-pernikahan-platinum',
            'description' => 'Paket pernikahan mewah untuk acara dengan kapasitas hingga 150 orang',
            'base_price' => 35000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $platinumPackage->serviceItems()->attach([
            $cateringPlatinum->id => ['quantity' => 150, 'custom_price' => $cateringPlatinum->price * 150, 'notes' => 'Catering untuk 150 orang'],
            $venueLarge->id => ['quantity' => 1, 'custom_price' => $venueLarge->price, 'notes' => 'Venue VIP untuk 150 orang'],
            $decorLuxury->id => ['quantity' => 1, 'custom_price' => $decorLuxury->price, 'notes' => 'Dekorasi mewah'],
            $musicBand->id => ['quantity' => 1, 'custom_price' => $musicBand->price, 'notes' => 'Live music full band'],
            $photoComplete->id => ['quantity' => 1, 'custom_price' => $photoComplete->price, 'notes' => 'Fotografi lengkap'],
        ]);
    }
    
    private function createBirthdayPackages($eventType, $cateringSilver, $cateringGold, $venueSmall, $venueMedium, $decorStandard, $decorPremium, $musicAcoustic, $dj, $photoStandard)
    {
        // Birthday Basic Package
        $basicPackage = PackageTemplate::create([
            'name' => 'Paket Ulang Tahun Basic',
            'slug' => 'paket-ulang-tahun-basic',
            'description' => 'Paket ulang tahun dasar untuk acara kecil dengan kapasitas hingga 30 orang',
            'base_price' => 5000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $basicPackage->serviceItems()->attach([
            $cateringSilver->id => ['quantity' => 30, 'custom_price' => $cateringSilver->price * 30, 'notes' => 'Catering untuk 30 orang'],
            $venueSmall->id => ['quantity' => 1, 'custom_price' => $venueSmall->price, 'notes' => 'Venue untuk 30 orang'],
            $decorStandard->id => ['quantity' => 1, 'custom_price' => $decorStandard->price, 'notes' => 'Dekorasi standar'],
        ]);
        
        // Birthday Premium Package
        $premiumPackage = PackageTemplate::create([
            'name' => 'Paket Ulang Tahun Premium',
            'slug' => 'paket-ulang-tahun-premium',
            'description' => 'Paket ulang tahun premium untuk acara dengan kapasitas hingga 50 orang',
            'base_price' => 10000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $premiumPackage->serviceItems()->attach([
            $cateringGold->id => ['quantity' => 50, 'custom_price' => $cateringGold->price * 50, 'notes' => 'Catering untuk 50 orang'],
            $venueMedium->id => ['quantity' => 1, 'custom_price' => $venueMedium->price, 'notes' => 'Venue untuk 50 orang'],
            $decorPremium->id => ['quantity' => 1, 'custom_price' => $decorPremium->price, 'notes' => 'Dekorasi premium'],
            $dj->id => ['quantity' => 1, 'custom_price' => $dj->price, 'notes' => 'DJ performance'],
            $photoStandard->id => ['quantity' => 1, 'custom_price' => $photoStandard->price, 'notes' => 'Fotografi standar'],
        ]);
    }
    
    private function createGatheringPackages($eventType, $cateringSilver, $cateringGold, $cateringPlatinum, $venueSmall, $venueMedium, $venueLarge, $decorStandard, $musicAcoustic, $photoStandard)
    {
        // Gathering Small Package
        $smallPackage = PackageTemplate::create([
            'name' => 'Paket Gathering Small',
            'slug' => 'paket-gathering-small',
            'description' => 'Paket gathering untuk acara kecil dengan kapasitas hingga 30 orang',
            'base_price' => 5000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $smallPackage->serviceItems()->attach([
            $cateringSilver->id => ['quantity' => 30, 'custom_price' => $cateringSilver->price * 30, 'notes' => 'Catering untuk 30 orang'],
            $venueSmall->id => ['quantity' => 1, 'custom_price' => $venueSmall->price, 'notes' => 'Venue untuk 30 orang'],
        ]);
        
        // Gathering Medium Package
        $mediumPackage = PackageTemplate::create([
            'name' => 'Paket Gathering Medium',
            'slug' => 'paket-gathering-medium',
            'description' => 'Paket gathering untuk acara sedang dengan kapasitas hingga 75 orang',
            'base_price' => 12000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $mediumPackage->serviceItems()->attach([
            $cateringGold->id => ['quantity' => 75, 'custom_price' => $cateringGold->price * 75, 'notes' => 'Catering untuk 75 orang'],
            $venueMedium->id => ['quantity' => 1, 'custom_price' => $venueMedium->price, 'notes' => 'Venue untuk 75 orang'],
            $decorStandard->id => ['quantity' => 1, 'custom_price' => $decorStandard->price, 'notes' => 'Dekorasi standar'],
            $musicAcoustic->id => ['quantity' => 1, 'custom_price' => $musicAcoustic->price, 'notes' => 'Live music akustik'],
        ]);
        
        // Gathering Large Package
        $largePackage = PackageTemplate::create([
            'name' => 'Paket Gathering Large',
            'slug' => 'paket-gathering-large',
            'description' => 'Paket gathering untuk acara besar dengan kapasitas hingga 150 orang',
            'base_price' => 25000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $largePackage->serviceItems()->attach([
            $cateringPlatinum->id => ['quantity' => 150, 'custom_price' => $cateringPlatinum->price * 150, 'notes' => 'Catering untuk 150 orang'],
            $venueLarge->id => ['quantity' => 1, 'custom_price' => $venueLarge->price, 'notes' => 'Venue untuk 150 orang'],
            $decorStandard->id => ['quantity' => 1, 'custom_price' => $decorStandard->price, 'notes' => 'Dekorasi standar'],
            $musicAcoustic->id => ['quantity' => 1, 'custom_price' => $musicAcoustic->price, 'notes' => 'Live music akustik'],
            $photoStandard->id => ['quantity' => 1, 'custom_price' => $photoStandard->price, 'notes' => 'Fotografi standar'],
        ]);
    }
    
    private function createArisanPackages($eventType, $cateringSilver, $cateringGold, $venueSmall, $decorStandard, $musicAcoustic)
    {
        // Arisan Basic Package
        $basicPackage = PackageTemplate::create([
            'name' => 'Paket Arisan Basic',
            'slug' => 'paket-arisan-basic',
            'description' => 'Paket arisan dasar untuk acara kecil dengan kapasitas hingga 20 orang',
            'base_price' => 3000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $basicPackage->serviceItems()->attach([
            $cateringSilver->id => ['quantity' => 20, 'custom_price' => $cateringSilver->price * 20, 'notes' => 'Catering untuk 20 orang'],
            $venueSmall->id => ['quantity' => 1, 'custom_price' => $venueSmall->price, 'notes' => 'Venue untuk 20 orang'],
        ]);
        
        // Arisan Premium Package
        $premiumPackage = PackageTemplate::create([
            'name' => 'Paket Arisan Premium',
            'slug' => 'paket-arisan-premium',
            'description' => 'Paket arisan premium untuk acara dengan kapasitas hingga 30 orang',
            'base_price' => 5000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $premiumPackage->serviceItems()->attach([
            $cateringGold->id => ['quantity' => 30, 'custom_price' => $cateringGold->price * 30, 'notes' => 'Catering untuk 30 orang'],
            $venueSmall->id => ['quantity' => 1, 'custom_price' => $venueSmall->price, 'notes' => 'Venue untuk 30 orang'],
            $decorStandard->id => ['quantity' => 1, 'custom_price' => $decorStandard->price, 'notes' => 'Dekorasi standar'],
            $musicAcoustic->id => ['quantity' => 1, 'custom_price' => $musicAcoustic->price, 'notes' => 'Live music akustik'],
        ]);
    }
    
    private function createEngagementPackages($eventType, $cateringGold, $cateringPlatinum, $venueSmall, $venueMedium, $decorStandard, $decorPremium, $musicAcoustic, $photoStandard, $photoPremium)
    {
        // Engagement Basic Package
        $basicPackage = PackageTemplate::create([
            'name' => 'Paket Lamaran Basic',
            'slug' => 'paket-lamaran-basic',
            'description' => 'Paket lamaran dasar untuk acara intim dengan kapasitas hingga 30 orang',
            'base_price' => 7000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $basicPackage->serviceItems()->attach([
            $cateringGold->id => ['quantity' => 30, 'custom_price' => $cateringGold->price * 30, 'notes' => 'Catering untuk 30 orang'],
            $venueSmall->id => ['quantity' => 1, 'custom_price' => $venueSmall->price, 'notes' => 'Venue untuk 30 orang'],
            $decorStandard->id => ['quantity' => 1, 'custom_price' => $decorStandard->price, 'notes' => 'Dekorasi standar'],
            $photoStandard->id => ['quantity' => 1, 'custom_price' => $photoStandard->price, 'notes' => 'Fotografi standar'],
        ]);
        
        // Engagement Premium Package
        $premiumPackage = PackageTemplate::create([
            'name' => 'Paket Lamaran Premium',
            'slug' => 'paket-lamaran-premium',
            'description' => 'Paket lamaran premium untuk acara dengan kapasitas hingga 50 orang',
            'base_price' => 12000000,
            'event_type_id' => $eventType->id,
            'is_active' => true,
        ]);
        
        $premiumPackage->serviceItems()->attach([
            $cateringPlatinum->id => ['quantity' => 50, 'custom_price' => $cateringPlatinum->price * 50, 'notes' => 'Catering untuk 50 orang'],
            $venueMedium->id => ['quantity' => 1, 'custom_price' => $venueMedium->price, 'notes' => 'Venue untuk 50 orang'],
            $decorPremium->id => ['quantity' => 1, 'custom_price' => $decorPremium->price, 'notes' => 'Dekorasi premium'],
            $musicAcoustic->id => ['quantity' => 1, 'custom_price' => $musicAcoustic->price, 'notes' => 'Live music akustik'],
            $photoPremium->id => ['quantity' => 1, 'custom_price' => $photoPremium->price, 'notes' => 'Fotografi premium'],
        ]);
    }
}
