<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders for upcoming due payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending payment reminders...');
        
        // Find payments that are due in 3 days, today, or 1 day overdue
        $reminderDates = [
            now()->addDays(3)->format('Y-m-d'),  // 3 days before due date
            now()->format('Y-m-d'),               // On due date
            now()->subDay()->format('Y-m-d')      // 1 day after due date
        ];
        
        // Get pending payments that match our reminder dates
        $payments = Payment::query()
            ->where('status', 'pending')
            ->whereHas('reservation', function($query) {
                $query->where('status', 'awaiting_first_payment');
            })
            ->whereDate('due_date', $reminderDates)
            ->with(['reservation', 'reservation.user'])
            ->get();
            
        if ($payments->isEmpty()) {
            $this->info('No payment reminders to send.');
            return 0;
        }
        
        $this->info("Found {$payments->count()} payments requiring reminders.");
        
        $bar = $this->output->createProgressBar($payments->count());
        $bar->start();
        
        $sentCount = 0;
        
        foreach ($payments as $payment) {
            try {
                // The PaymentReminder notification will handle the actual reminder message
                // based on how many days are left until the due date
                $payment->reservation->user->notify(
                    new \App\Notifications\PaymentReminder(
                        $payment->reservation,
                        $payment
                    )
                );
                
                $sentCount++;
                $this->line("\nSent reminder for payment #{$payment->id} (Due: {$payment->due_date->format('Y-m-d')})");
            } catch (\Exception $e) {
                Log::error("Failed to send payment reminder for payment #{$payment->id}: " . $e->getMessage());
                $this->error("Error sending reminder for payment #{$payment->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Successfully sent {$sentCount} payment reminders.");
        return 0;
    }
}
