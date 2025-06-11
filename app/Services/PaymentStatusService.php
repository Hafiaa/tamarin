<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentStatusService
{
    // Payment type constants
    public const TYPE_DP1 = 'dp1';
    public const TYPE_DP2 = 'dp2';
    public const TYPE_DOWN_PAYMENT = 'down_payment';
    public const TYPE_FULL_PAYMENT = 'full_payment';
    public const TYPE_REVISION = 'revision';

    // Payment status constants
    public const STATUS_AWAITING_FIRST_PAYMENT = 'awaiting_first_payment';
    public const STATUS_PAYMENT_PENDING_VERIFICATION = 'payment_pending_verification';
    public const STATUS_DP1_VERIFIED = 'dp1_verified';
    public const STATUS_DEPOSIT_VERIFIED = 'deposit_verified';
    public const STATUS_AWAITING_DP2 = 'awaiting_dp2';
    public const STATUS_DP2_VERIFIED = 'dp2_verified';
    public const STATUS_PENDING_REVISION_REVIEW = 'pending_revision_review';
    public const STATUS_AWAITING_FINAL_PAYMENT = 'awaiting_final_payment';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Process a payment action
     */
    public function processAction(Payment $payment, string $action, array $data = []): void
    {
        DB::beginTransaction();

        try {
            switch ($action) {
                case 'mark_as_paid':
                    $this->markAsPaid($payment, $data);
                    break;
                
                case 'verify':
                    $this->verifyPayment($payment, $data['admin_notes'] ?? null);
                    break;
                
                case 'reject':
                    $this->rejectPayment($payment, $data['rejection_reason'] ?? null);
                    break;
                
                case 'request_revision':
                    $this->requestRevision($payment, $data['notes'] ?? null);
                    break;
                
                case 'cancel':
                    $this->cancelPayment($payment, $data['reason'] ?? null);
                    break;
                
                default:
                    throw new \InvalidArgumentException('Invalid action');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mark a payment as paid by customer
     */
    protected function markAsPaid(Payment $payment, array $data): void
    {
        if (!in_array($payment->status, [
            self::STATUS_AWAITING_FIRST_PAYMENT,
            self::STATUS_AWAITING_DP2,
            self::STATUS_AWAITING_FINAL_PAYMENT,
        ])) {
            throw new \InvalidArgumentException('Payment cannot be marked as paid in its current status.');
        }

        $payment->update([
            'status' => self::STATUS_PAYMENT_PENDING_VERIFICATION,
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'],
            'paid_at' => now(),
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Verify a payment (admin action)
     */
    protected function verifyPayment(Payment $payment, ?string $adminNotes): void
    {
        if ($payment->status !== self::STATUS_PAYMENT_PENDING_VERIFICATION) {
            throw new \InvalidArgumentException('Only payments pending verification can be verified.');
        }

        $newStatus = $this->getVerifiedStatus($payment);
        $payment->update([
            'status' => $newStatus,
            'admin_notes' => $adminNotes,
        ]);

        // Create next payment if needed
        $this->createNextPaymentIfNeeded($payment, $newStatus);
    }

    /**
     * Reject a payment (admin action)
     */
    protected function rejectPayment(Payment $payment, ?string $reason): void
    {
        if ($payment->status !== self::STATUS_PAYMENT_PENDING_VERIFICATION) {
            throw new \InvalidArgumentException('Only payments pending verification can be rejected.');
        }

        $payment->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Request a revision for a payment
     */
    protected function requestRevision(Payment $payment, ?string $notes): void
    {
        $payment->update([
            'status' => self::STATUS_PENDING_REVISION_REVIEW,
            'notes' => $notes,
        ]);
    }

    /**
     * Cancel a payment
     */
    protected function cancelPayment(Payment $payment, ?string $reason): void
    {
        $payment->update([
            'status' => self::STATUS_CANCELLED,
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Get the appropriate status after verification
     */
    protected function getVerifiedStatus(Payment $payment): string
    {
        return match($payment->type) {
            self::TYPE_DP1 => self::STATUS_DP1_VERIFIED,
            self::TYPE_DOWN_PAYMENT => self::STATUS_DEPOSIT_VERIFIED,
            self::TYPE_DP2 => self::STATUS_DP2_VERIFIED,
            self::TYPE_FULL_PAYMENT => self::STATUS_COMPLETED,
            default => $payment->status,
        };
    }

    /**
     * Create the next payment in the flow if needed
     */
    protected function createNextPaymentIfNeeded(Payment $payment, string $newStatus): void
    {
        $reservation = $payment->reservation;
        
        if (!$reservation) {
            return;
        }

        $isWedding = $reservation->eventType->name === 'Wedding';
        $eventDate = $reservation->event_date;
        
        // If this is a wedding and DP1 was just verified, create DP2
        if ($isWedding && $newStatus === self::STATUS_DP1_VERIFIED) {
            $this->createPayment(
                $reservation,
                self::TYPE_DP2,
                $reservation->total_price * 0.3, // 30% for DP2
                $eventDate->copy()->subDays(45), // Due 45 days before event
                self::STATUS_AWAITING_DP2
            );
        } 
        // If this is a non-wedding and deposit was verified, create final payment
        elseif (!$isWedding && $newStatus === self::STATUS_DEPOSIT_VERIFIED) {
            $this->createFinalPayment($reservation);
        }
        // If this is a wedding and DP2 was just verified, create final payment
        elseif ($isWedding && $newStatus === self::STATUS_DP2_VERIFIED) {
            $this->createFinalPayment($reservation);
        }
    }

    /**
     * Create a final payment for a reservation
     */
    protected function createFinalPayment(Reservation $reservation): void
    {
        $paidAmount = $reservation->payments()
            ->whereIn('status', [
                self::STATUS_DP1_VERIFIED,
                self::STATUS_DP2_VERIFIED,
                self::STATUS_DEPOSIT_VERIFIED,
                self::STATUS_COMPLETED
            ])
            ->sum('amount');
            
        $remainingAmount = $reservation->total_price - $paidAmount;
        
        if ($remainingAmount > 0) {
            $this->createPayment(
                $reservation,
                self::TYPE_FULL_PAYMENT,
                $remainingAmount,
                $reservation->event_date->copy()->subDays(7), // Due 1 week before event
                self::STATUS_AWAITING_FINAL_PAYMENT
            );
        }
    }

    /**
     * Create a new payment
     */
    protected function createPayment(Reservation $reservation, string $type, float $amount, $dueDate, string $status): void
    {
        $reservation->payments()->create([
            'type' => $type,
            'amount' => $amount,
            'status' => $status,
            'due_date' => $dueDate,
        ]);
    }

    /**
     * Get available transitions for a payment
     */
    public function getAvailableTransitions(Payment $payment): array
    {
        $transitions = [];
        
        switch ($payment->status) {
            case self::STATUS_AWAITING_FIRST_PAYMENT:
            case self::STATUS_AWAITING_DP2:
            case self::STATUS_AWAITING_FINAL_PAYMENT:
                $transitions['mark_as_paid'] = 'Mark as Paid';
                break;
                
            case self::STATUS_PAYMENT_PENDING_VERIFICATION:
                $transitions['verify'] = 'Verify Payment';
                $transitions['reject'] = 'Reject Payment';
                break;
                
            case self::STATUS_PENDING_REVISION_REVIEW:
                $transitions['verify'] = 'Approve Revision';
                $transitions['reject'] = 'Reject Revision';
                break;
        }
        
        // Add cancel transition if not already in a terminal state
        if (!in_array($payment->status, [
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_REJECTED
        ])) {
            $transitions['cancel'] = 'Cancel Payment';
        }
        
        return $transitions;
    }

    /**
     * Get payment status label
     */
    public function getStatusLabel(string $status): string
    {
        return match($status) {
            self::STATUS_AWAITING_FIRST_PAYMENT => 'Menunggu Pembayaran DP1/Uang Muka',
            self::STATUS_PAYMENT_PENDING_VERIFICATION => 'Menunggu Verifikasi Pembayaran',
            self::STATUS_DP1_VERIFIED => 'DP1 Terverifikasi',
            self::STATUS_DEPOSIT_VERIFIED => 'Uang Muka Terverifikasi',
            self::STATUS_AWAITING_DP2 => 'Menunggu Pembayaran DP2',
            self::STATUS_DP2_VERIFIED => 'DP2 Terverifikasi',
            self::STATUS_PENDING_REVISION_REVIEW => 'Menunggu Review Revisi',
            self::STATUS_AWAITING_FINAL_PAYMENT => 'Menunggu Pelunasan',
            self::STATUS_COMPLETED => 'Lunas',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_REJECTED => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
