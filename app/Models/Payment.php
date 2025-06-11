<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Carbon\Carbon;
use App\Services\PaymentStatusService;

class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    // Payment Types
    const TYPE_DP1 = 'dp1';
    const TYPE_DP2 = 'dp2';
    const TYPE_DOWN_PAYMENT = 'down_payment';
    const TYPE_FULL_PAYMENT = 'full_payment';
    const TYPE_REVISION = 'revision';
    
    // Payment Statuses
    const STATUS_AWAITING_FIRST_PAYMENT = 'awaiting_first_payment';
    const STATUS_PAYMENT_PENDING_VERIFICATION = 'payment_pending_verification';
    const STATUS_DP1_VERIFIED = 'dp1_verified';
    const STATUS_DEPOSIT_VERIFIED = 'deposit_verified';
    const STATUS_AWAITING_DP2 = 'awaiting_dp2';
    const STATUS_DP2_VERIFIED = 'dp2_verified';
    const STATUS_PENDING_REVISION_REVIEW = 'pending_revision_review';
    const STATUS_AWAITING_FINAL_PAYMENT = 'awaiting_final_payment';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'type',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_reference',
        'notes',
        'admin_notes',
        'rejection_reason',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'due_date',
        'paid_at',
        'created_at',
        'updated_at',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status_label', 'is_overdue'];
    
    /**
     * Get the reservation that owns the payment.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    
    /**
     * Process a payment action using the PaymentStatusService.
     *
     * @param string $action The action to perform (mark_as_paid, verify, reject, etc.)
     * @param array $data Additional data needed for the action
     * @return void
     */
    public function processAction(string $action, array $data = []): void
    {
        app(PaymentStatusService::class)->processAction($this, $action, $data);
    }
    
    /**
     * Mark payment as paid.
     * @deprecated Use processAction('mark_as_paid', $data) instead
     */
    public function markAsPaid(string $paymentMethod, string $reference, ?string $notes = null): void
    {
        $this->processAction('mark_as_paid', [
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference,
            'notes' => $notes,
        ]);
    }
    
    /**
     * Verify the payment.
     * @deprecated Use processAction('verify', $data) instead
     */
    public function verify(?string $adminNotes = null): void
    {
        $this->processAction('verify', ['admin_notes' => $adminNotes]);
    }
    
    /**
     * Reject the payment.
     * @deprecated Use processAction('reject', $data) instead
     */
    public function reject(string $reason): void
    {
        $this->processAction('reject', ['rejection_reason' => $reason]);
    }
    
    /**
     * Request a revision for this payment.
     */
    public function requestRevision(string $notes): void
    {
        $this->processAction('request_revision', ['notes' => $notes]);
    }
    
    /**
     * Cancel this payment.
     */
    public function cancel(string $reason): void
    {
        $this->processAction('cancel', ['reason' => $reason]);
    }
    
    /**
     * Get available transitions for this payment.
     */
    public function getAvailableTransitions(): array
    {
        return app(PaymentStatusService::class)->getAvailableTransitions($this);
    }
    
    /**
     * Check if payment is for a wedding event.
     */
    public function isForWedding(): bool
    {
        return $this->reservation && 
               $this->reservation->eventType && 
               $this->reservation->eventType->name === 'Wedding';
    }
    
    /**
     * Check if payment is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !in_array($this->status, [
                   self::STATUS_COMPLETED, 
                   self::STATUS_CANCELLED, 
                   self::STATUS_REJECTED
               ]);
    }
    
    /**
     * Get the payment status as a human-readable string.
     */
    public function getStatusLabelAttribute(): string
    {
        return app(PaymentStatusService::class)->getStatusLabel($this->status);
    }
    
    /**
     * Scope a query to only include payments that are pending action.
     */
    public function scopePendingAction($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PAYMENT_PENDING_VERIFICATION,
            self::STATUS_PENDING_REVISION_REVIEW,
        ]);
    }
    
    /**
     * Scope a query to only include overdue payments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', [
                        self::STATUS_COMPLETED,
                        self::STATUS_CANCELLED,
                        self::STATUS_REJECTED
                    ]);
    }
    
    /**
     * Scope a query to only include upcoming payments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', now())
                    ->whereNotIn('status', [
                        self::STATUS_COMPLETED,
                        self::STATUS_CANCELLED,
                        self::STATUS_REJECTED
                    ]);
    }
    
    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment_proof')
            ->useDisk('public')
            ->singleFile();
    }
}
