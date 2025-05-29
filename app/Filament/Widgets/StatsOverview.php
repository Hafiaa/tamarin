<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\Payment;
use App\Models\User;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $monthlyRevenue = Payment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
            
        $pendingPayments = Payment::where('status', 'pending')->count();
        $activeReservations = Reservation::whereIn('status', ['confirmed', 'in_progress'])->count();
        $newUsers = User::whereDate('created_at', '>=', now()->subDays(7))->count();

        return [
            Stat::make('Total Reservasi', Reservation::count())
                ->description($activeReservations . ' aktif')
                ->descriptionIcon('heroicon-o-calendar')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
                
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(($monthlyRevenue > 0 ? '↑ ' : '') . number_format($monthlyRevenue, 0, ',', '.'))
                ->descriptionIcon($monthlyRevenue > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($monthlyRevenue > 0 ? 'success' : 'danger')
                ->color('success'),
                
            Stat::make('Total Pengguna', User::count())
                ->description($newUsers . ' pengguna baru')
                ->descriptionIcon('heroicon-o-users')
                ->chart([5, 7, 10, 12, 15, 17, 20])
                ->color('primary'),
                
            Stat::make('Pembayaran Tertunda', $pendingPayments)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pendingPayments > 0 ? 'warning' : 'success'),
                
            Stat::make('Testimoni', Testimonial::count())
                ->description('Ulasan pelanggan')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color('warning'),
                
            Stat::make('Rating Rata-rata', number_format(Testimonial::avg('rating') ?? 0, 1))
                ->description('Dari 5 bintang')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
        ];
    }
}
