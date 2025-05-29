<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CustomPackage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'status',
        'customer_notes',
        'admin_notes',
        'total_price',
        'selected_items',
        'custom_items',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_price' => 'decimal:2',
        'selected_items' => 'array',
        'custom_items' => 'array',
    ];
    
    /**
     * Get the reservation that owns the custom package.
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
        $this->addMediaCollection('reference_files')
            ->useDisk('public');
    }
}
