<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use App\Filament\Widgets\ReservationStatsWidget;
use App\Filament\Widgets\ReservationTrendsChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?int $navigationSort = 1;

    public function getColumns(): int
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            ReservationStatsWidget::class,
            ReservationTrendsChart::class,
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            ReservationStatsWidget::class,
        ];
    }
}
