<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceItem extends Model implements HasMedia
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
        'price',
        'category',
        'is_available',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];
    
    /**
     * Get the base price attribute (for backward compatibility)
     */
    public function getBasePriceAttribute()
    {
        return $this->attributes['price'];
    }
    
    /**
     * Set the base price attribute (for backward compatibility)
     */
    public function setBasePriceAttribute($value)
    {
        $this->attributes['price'] = $value;
    }
    
    /**
     * Get the type attribute (for backward compatibility)
     */
    public function getTypeAttribute()
    {
        return $this->attributes['category'];
    }
    
    /**
     * Set the type attribute (for backward compatibility)
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['category'] = $value;
    }
    
    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($serviceItem) {
            if (empty($serviceItem->category)) {
                $serviceItem->category = 'service';
            }
            // Set default availability if not set
            if (!isset($serviceItem->is_available)) {
                $serviceItem->is_available = true;
            }
        });
    }
    
    /**
     * The package templates that include this service item.
     */
    public function packageTemplates(): BelongsToMany
    {
        return $this->belongsToMany(PackageTemplate::class, 'package_template_service_item')
            ->withPivot('quantity', 'custom_price', 'notes')
            ->withTimestamps();
    }
    
    /**
     * The custom package items that use this service item.
     */
    public function customPackageItems()
    {
        return $this->hasMany(CustomPackageItem::class);
    }
    
    /**
     * Scope a query to only include active service items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include items of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    /**
     * Calculate the total price based on quantity.
     */
    public function calculateTotalPrice(int $quantity = 1): float
    {
        return $this->base_price * $quantity;
    }
    
    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public');
    }
}
