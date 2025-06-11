<?php

namespace Database\Seeders;

use App\Models\BlockedDate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IndonesianHolidaysSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;
        
        $holidays = $this->getIndonesianHolidays($currentYear);
        $nextYearHolidays = $this->getIndonesianHolidays($nextYear);
        
        // Merge current and next year holidays
        $allHolidays = array_merge($holidays, $nextYearHolidays);
        
        foreach ($allHolidays as $holiday) {
            BlockedDate::updateOrCreate(
                ['date' => $holiday['date']],
                [
                    'reason' => $holiday['reason'],
                    'is_recurring_yearly' => $holiday['is_recurring_yearly']
                ]
            );
        }
        
        $this->command->info('Indonesian holidays have been seeded successfully!');
    }
    
    private function getIndonesianHolidays(int $year): array
    {
        // Fixed date holidays
        $fixedHolidays = [
            // January
            ["{$year}-01-01", 'Tahun Baru Masehi', true],
            
            // February
            [Carbon::parse("first day of February {$year}")->next('Sunday')->format('Y-m-d'), 'Hari Minggu di Awal Februari (Libur Khusus)', true],
            
            // March
            [Carbon::parse("third Monday of March {$year}")->format('Y-m-d'), 'Hari Raya Nyepi (Perhitungan Caka)', false],
            
            // April
            ["{$year}-04-02", 'Wafat Yesus Kristus', true],
            ["{$year}-04-21", 'Hari Raya Idul Fitri 1 Syawal 1446 H', false],
            ["{$year}-04-22", 'Hari Raya Idul Fitri 2 Syawal 1446 H', false],
            ["{$year}-05-01", 'Hari Buruh Internasional', true],
            
            // May
            [Carbon::parse("second Sunday of May {$year}")->format('Y-m-d'), 'Hari Raya Waisak', false],
            ["{$year}-05-09", 'Kenaikan Yesus Kristus', false],
            ["{$year}-05-23", 'Hari Raya Idul Adha 1446 H', false],
            
            // June
            ["{$year}-06-01", 'Hari Lahir Pancasila', true],
            [Carbon::parse("third Sunday of June {$year}")->format('Y-m-d'), 'Hari Raya Waisak (untuk beberapa daerah)', false],
            
            // July
            ["{$year}-07-07", 'Tahun Baru Islam 1447 H', false],
            
            // August
            ["{$year}-08-17", 'Hari Kemerdekaan Republik Indonesia', true],
            
            // September
            ["{$year}-09-16", 'Maulid Nabi Muhammad SAW', false],
            
            // December
            ["{$year}-12-25", 'Hari Raya Natal', true],
        ];
        
        // Convert to proper format
        $formattedHolidays = [];
        foreach ($fixedHolidays as $holiday) {
            $formattedHolidays[] = [
                'date' => $holiday[0],
                'reason' => $holiday[1],
                'is_recurring_yearly' => $holiday[2]
            ];
        }
        
        // Add long weekends (weekend + holiday combinations)
        $this->addLongWeekends($formattedHolidays, $year);
        
        return $formattedHolidays;
    }
    
    private function addLongWeekends(array &$holidays, int $year): void
    {
        // Add day before/after holidays that fall on Tuesday/Thursday
        foreach ($holidays as $holiday) {
            $date = Carbon::parse($holiday['date']);
            
            // If holiday is on Tuesday, add Monday as a holiday
            if ($date->isTuesday()) {
                $holidays[] = [
                    'date' => $date->copy()->subDay()->format('Y-m-d'),
                    'reason' => 'Cuti Bersama ' . $holiday['reason'],
                    'is_recurring_yearly' => $holiday['is_recurring_yearly']
                ];
            }
            
            // If holiday is on Thursday, add Friday as a holiday
            if ($date->isThursday()) {
                $holidays[] = [
                    'date' => $date->copy()->addDay()->format('Y-m-d'),
                    'reason' => 'Cuti Bersama ' . $holiday['reason'],
                    'is_recurring_yearly' => $holiday['is_recurring_yearly']
                ];
            }
        }
        
        // Add special long weekends (like before/after Idul Fitri)
        $this->addIdulFitriHolidays($holidays, $year);
    }
    
    private function addIdulFitriHolidays(array &$holidays, int $year): void
    {
        // Add 1 day before and 1 day after Idul Fitri
        $found = false;
        foreach ($holidays as $holiday) {
            if (str_contains($holiday['reason'], 'Idul Fitri')) {
                $date = Carbon::parse($holiday['date']);
                
                // Add day before Idul Fitri
                $dayBefore = $date->copy()->subDay();
                if (!$this->dateExists($holidays, $dayBefore->format('Y-m-d'))) {
                    $holidays[] = [
                        'date' => $dayBefore->format('Y-m-d'),
                        'reason' => 'Hari Raya Idul Fitri (Cuti Bersama)',
                        'is_recurring_yearly' => false
                    ];
                }
                
                // Add day after Idul Fitri (if it's a working day)
                $dayAfter = $date->copy()->addDay();
                if (!$dayAfter->isWeekend() && !$this->dateExists($holidays, $dayAfter->format('Y-m-d'))) {
                    $holidays[] = [
                        'date' => $dayAfter->format('Y-m-d'),
                        'reason' => 'Hari Raya Idul Fitri (Cuti Bersama)',
                        'is_recurring_yearly' => false
                    ];
                }
                
                $found = true;
            }
        }
        
        // If Idul Fitri not found in fixed holidays (for next year), add it
        if (!$found && $year === now()->addYear()->year) {
            // Example: Add Idul Fitri for next year if not already in the list
            $idulFitri1 = "{$year}-04-21";
            $idulFitri2 = "{$year}-04-22";
            
            $holidays[] = [
                'date' => $idulFitri1,
                'reason' => 'Hari Raya Idul Fitri 1 Syawal 1446 H',
                'is_recurring_yearly' => false
            ];
            
            $holidays[] = [
                'date' => $idulFitri2,
                'reason' => 'Hari Raya Idul Fitri 2 Syawal 1446 H',
                'is_recurring_yearly' => false
            ];
            
            // Add cuti bersama
            $holidays[] = [
                'date' => Carbon::parse($idulFitri1)->subDay()->format('Y-m-d'),
                'reason' => 'Cuti Bersama Idul Fitri',
                'is_recurring_yearly' => false
            ];
        }
    }
    
    private function dateExists(array $holidays, string $date): bool
    {
        foreach ($holidays as $holiday) {
            if ($holiday['date'] === $date) {
                return true;
            }
        }
        return false;
    }
}
