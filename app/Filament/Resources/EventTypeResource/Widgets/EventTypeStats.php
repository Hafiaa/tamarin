<?php

namespace App\Filament\Resources\EventTypeResource\Widgets;

use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventTypeStats extends BaseWidget
{
    protected function getStats(): array
    {
        $eventType = $this->getRecord();
        
        return [
            Stat::make('Total Reservasi', 
                Reservation::where('event_type_id', $eventType->id)->count()
            )
            ->description('Total pemesanan untuk jenis acara ini')
            ->descriptionIcon('heroicon-o-calendar')
            ->color('primary'),
            
            Stat::make('Pendapatan', 
                'Rp ' . number_format(
                    Reservation::where('event_type_id', $eventType->id)
                        ->where('status', 'completed')
                        ->sum('total_price'),
                    0, ',', '.'
                )
            )
            ->description('Total pendapatan dari jenis acara ini')
            ->descriptionIcon('heroicon-o-currency-dollar')
            ->color('success'),
            
            Stat::make('Rata-rata Peserta', 
                number_format(
                    Reservation::where('event_type_id', $eventType->id)
                        ->avg('number_of_people'),
                    0, ',', '.'
                ) . ' orang'
            )
            ->description('Rata-rata jumlah peserta per acara')
            ->descriptionIcon('heroicon-o-user-group')
            ->color('info'),
        ];
    }
}
