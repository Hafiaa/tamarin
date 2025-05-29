<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PackageTemplate extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'event_type_id',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the event type that owns the package template.
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }
    
    /**
     * The service items that belong to the package template.
     */
    public function serviceItems(): BelongsToMany
    {
        return $this->belongsToMany(ServiceItem::class, 'package_template_service_item')
            ->withPivot('quantity', 'custom_price', 'notes')
            ->withTimestamps();
    }
    
    /**
     * Get the reservations for the package template.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    
    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk('public');
    }
}
