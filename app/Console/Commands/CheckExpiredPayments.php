<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired payments and cancel reservations if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired payments...');
        
        // Find all pending payments that are past their due date
        $expiredPayments = Payment::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '<', now())
            ->whereHas('reservation', function($query) {
                $query->where('status', 'awaiting_first_payment');
            })
            ->with('reservation')
            ->get();
            
        if ($expiredPayments->isEmpty()) {
            $this->info('No expired payments found.');
            return 0;
        }
        
        $this->info("Found {$expiredPayments->count()} expired payments to process.");
        
        $bar = $this->output->createProgressBar($expiredPayments->count());
        $bar->start();
        
        $updatedCount = 0;
        
        foreach ($expiredPayments as $payment) {
            try {
                DB::beginTransaction();
                
                // Update payment status
                $payment->update([
                    'status' => 'expired',
                    'notes' => 'Pembayaran kadaluarsa - dibatalkan secara otomatis oleh sistem.'
                ]);
                
                // Update reservation status
                $payment->reservation->update([
                    'status' => 'cancelled_payment_expired',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Pembayaran tidak diterima sebelum batas waktu yang ditentukan.'
                ]);
                
                // The notification will be sent by the ReservationObserver
                
                DB::commit();
                $updatedCount++;
                
                $this->line("\nCancelled reservation #{$payment->reservation_id} - Payment #{$payment->id} expired on {$payment->due_date}");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process expired payment #{$payment->id}: " . $e->getMessage());
                $this->error("Error processing payment #{$payment->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Successfully processed {$updatedCount} expired payments.");
        return 0;
    }
}
