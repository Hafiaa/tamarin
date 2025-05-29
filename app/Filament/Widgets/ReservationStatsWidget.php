<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ReservationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        
        return [
            Stat::make('Total Reservations', Reservation::count())
                ->description('All-time reservations')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            
            Stat::make('Today\'s Reservations', 
                Reservation::whereDate('created_at', $today)->count())
                ->description('Reservations made today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
            
            Stat::make('Pending Reservations', 
                Reservation::where('status', 'pending')->count())
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
