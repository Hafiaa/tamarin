<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\Payment;
use App\Notifications\ReservationApproved;
use App\Notifications\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class ReservationObserver
{
    // Payment types
    public const PAYMENT_TYPE_DP1 = 'dp1';
    public const PAYMENT_TYPE_DEPOSIT = 'deposit';
    
    // Statuses
    public const STATUS_AWAITING_PAYMENT = 'awaiting_first_payment';
    public const STATUS_PAYMENT_PENDING_VERIFICATION = 'payment_pending_verification';
    public const STATUS_CANCELLED_PAYMENT_EXPIRED = 'cancelled_payment_expired';
    
    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        // Check if status was changed to 'awaiting_first_payment'
        if ($reservation->isDirty('status') && $reservation->status === self::STATUS_AWAITING_PAYMENT) {
            $this->handleAwaitingPayment($reservation);
        }
        
        // Check if status was changed to 'cancelled_payment_expired'
        if ($reservation->isDirty('status') && $reservation->status === self::STATUS_CANCELLED_PAYMENT_EXPIRED) {
            $this->handleCancelledPaymentExpired($reservation);
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "restored" event.
     */
    public function restored(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "force deleted" event.
     */
    public function forceDeleted(Reservation $reservation): void
    {
        //
    }
    
    /**
     * Handle the 'awaiting_first_payment' status
     */
    protected function handleAwaitingPayment(Reservation $reservation): void
    {
        // Check if this is a wedding event
        $isWedding = $this->isWeddingEvent($reservation);
        
        // Calculate payment amount (50% for non-wedding, 30% for wedding as DP1)
        $amount = $isWedding 
            ? $reservation->total_price * 0.3 // 30% for wedding
            : $reservation->total_price * 0.5; // 50% for non-wedding
        
        // Create the payment record
        $payment = new Payment([
            'payment_type' => $isWedding ? self::PAYMENT_TYPE_DP1 : self::PAYMENT_TYPE_DEPOSIT,
            'amount' => $amount,
            'status' => 'pending',
            'due_date' => now()->addWeek(),
            'notes' => $isWedding ? 'Pembayaran DP1 (30%)' : 'Uang Muka (50%)',
        ]);
        
        $reservation->payments()->save($payment);
        
        // Send notification to customer
        $reservation->user->notify(new ReservationApproved($reservation, $payment));
        
        // Schedule payment reminders
        $this->schedulePaymentReminders($reservation, $payment);
    }
    
    /**
     * Handle the 'cancelled_payment_expired' status
     */
    protected function handleCancelledPaymentExpired(Reservation $reservation): void
    {
        // Send notification to customer about cancellation
        $reservation->user->notify(new PaymentExpired($reservation));
        
        // Send notification to admin
        Notification::route('mail', config('mail.admin_email'))
            ->notify(new AdminPaymentExpired($reservation));
    }
    
    /**
     * Check if the reservation is for a wedding event
     */
    protected function isWeddingEvent(Reservation $reservation): bool
    {
        // Check if the event type name contains wedding-related keywords
        $eventTypeName = strtolower($reservation->eventType->name ?? '');
        return str_contains($eventTypeName, 'wedding') || 
               str_contains($eventTypeName, 'pernikahan') ||
               str_contains($eventTypeName, 'lamaran');
    }
    
    /**
     * Schedule payment reminder notifications
     */
    protected function schedulePaymentReminders(Reservation $reservation, Payment $payment): void
    {
        // Schedule reminders at different intervals
        $reminderIntervals = [
            // 3 days before due date
            now()->parse($payment->due_date)->subDays(3),
            // On due date (morning)
            now()->parse($payment->due_date)->startOfDay()->addHours(9), // 9 AM
            // 1 day after due date (if not paid)
            now()->parse($payment->due_date)->addDay()
        ];
        
        foreach ($reminderIntervals as $reminderDate) {
            $reservation->user->notify(
                (new PaymentReminder($reservation, $payment))->delay($reminderDate)
            );
        }
    }
}
