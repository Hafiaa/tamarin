<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationTrendsChart extends ChartWidget
{
    protected static ?string $heading = 'Reservation Trends';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $reservations = Reservation::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->limit(30)
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Daily Reservations',
                    'data' => $reservations->pluck('count')->toArray(),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
            ],
            'labels' => $reservations->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
