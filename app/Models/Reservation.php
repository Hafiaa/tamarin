<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Auth;

class Reservation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    // Status options for dropdowns
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DECLINED => 'Declined',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    // Status helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isDeclined(): bool
    {
        return $this->status === self::STATUS_DECLINED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Status change methods
    public function approve(int $userId): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'admin_notes' => ($this->admin_notes ?? '') . "\nApproved by admin"
        ]);
    }

    public function decline(int $userId, string $reason = ''): void
    {
        $this->update([
            'status' => self::STATUS_DECLINED,
            'admin_notes' => ($this->admin_notes ?? '') . "\nDeclined by admin. Reason: " . $reason
        ]);
    }

    public function cancel(int $userId, string $reason = ''): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'admin_notes' => ($this->admin_notes ?? '') . "\nCancelled by admin. Reason: " . $reason
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'admin_notes' => ($this->admin_notes ?? '') . "\nMarked as completed"
        ]);
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event_type_id',
        'package_template_id',
        'event_date',
        'event_time',
        'guest_count',
        'special_requests',
        'bride_name',
        'groom_name',
        'total_price',
        'status',
        'notes',
        'admin_notes',
        'estimated_revenue',
        'reference_files',
        'budget',
        'cancellation_reason',
        'cancelled_at',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['custom_package_total'];
    
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_price' => 'decimal:2',
        'estimated_revenue' => 'decimal:2',
        'guest_count' => 'integer',
        'reference_files' => 'array',
    ];
    
    protected static function booted()
    {
        static::creating(function ($reservation) {
            if (empty($reservation->status)) {
                $reservation->status = self::STATUS_PENDING;
            }
        });
    }
    
    /**
     * Get the user that owns the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the event type that owns the reservation.
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }
    
    /**
     * Get the package template that owns the reservation.
     */
    public function packageTemplate(): BelongsTo
    {
        return $this->belongsTo(PackageTemplate::class);
    }
    
    /**
     * Get the custom package items for the reservation.
     */
    public function customPackageItems()
    {
        return $this->hasMany(CustomPackageItem::class);
    }
    
    /**
     * Get the total price of all custom package items.
     */
    public function getCustomPackageTotalAttribute()
    {
        return $this->customPackageItems->sum('total_price');
    }
    
    /**
     * Get the payments for the reservation.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Get the revisions for the reservation.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ReservationRevision::class);
    }
    
    /**
     * Get the testimonials for the reservation.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
    
    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reference_files')
            ->useDisk('public');
    }
}
