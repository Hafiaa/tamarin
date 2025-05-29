<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Reservation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
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
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'total_price' => 'decimal:2',
        'estimated_revenue' => 'decimal:2',
    ];
    
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
     * Get the custom package associated with the reservation.
     */
    public function customPackage(): HasOne
    {
        return $this->hasOne(CustomPackage::class);
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
