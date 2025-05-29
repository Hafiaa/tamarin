<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'payment_type',
        'amount',
        'status',
        'due_date',
        'payment_date',
        'rejection_reason',
        'notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];
    
    /**
     * Get the reservation that owns the payment.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
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
