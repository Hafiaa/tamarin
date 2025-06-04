<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run the payment expiration check daily at 1:00 AM
        $schedule->command('payments:check-expired')
                 ->dailyAt('01:00')
                 ->timezone('Asia/Jakarta')
                 ->onOneServer();
                 
        // Send payment reminders daily at 10:00 AM
        $schedule->command('payments:send-reminders')
                 ->dailyAt('10:00')
                 ->timezone('Asia/Jakarta')
                 ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
