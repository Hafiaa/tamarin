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
        'base_price',
        'type',
        'is_active',
        'image',
        'options',
        'min_quantity',
        'max_quantity',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'options' => 'array',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
    ];
    
    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($serviceItem) {
            if (empty($serviceItem->type)) {
                $serviceItem->type = 'service';
            }
            if (empty($serviceItem->min_quantity)) {
                $serviceItem->min_quantity = 1;
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
