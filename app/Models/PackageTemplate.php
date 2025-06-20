<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
        // Collection untuk galeri foto
        $this->addMediaCollection('gallery')
            ->useDisk('public')
            ->useFallbackUrl(asset('images/placeholder.jpg'))
            ->useFallbackPath(public_path('images/placeholder.jpg'))
            ->withResponsiveImages()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
            ])
            ->registerMediaConversions(function (Media $media = null) {
                $this->addMediaConversion('thumb')
                    ->width(300)
                    ->height(200)
                    ->sharpen(10);
                    
                $this->addMediaConversion('preview')
                    ->width(800)
                    ->height(600)
                    ->sharpen(10);
            });
            
        // Collection untuk foto utama
        $this->addMediaCollection('featured_image')
            ->useDisk('public')
            ->useFallbackUrl(asset('images/placeholder.jpg'))
            ->useFallbackPath(public_path('images/placeholder.jpg'))
            ->singleFile()
            ->withResponsiveImages()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
            ])
            ->registerMediaConversions(function (Media $media = null) {
                $this->addMediaConversion('thumb')
                    ->width(300)
                    ->height(200)
                    ->sharpen(10);
                    
                $this->addMediaConversion('preview')
                    ->width(1200)
                    ->height(800)
                    ->sharpen(10);
            });
    }
    
    /**
     * Get the URL of the featured image.
     *
     * @return string
     */
    public function getFeaturedImageUrlAttribute()
    {
        return $this->hasMedia('featured_image') 
            ? $this->getFirstMedia('featured_image')->getUrl('preview')
            : asset('images/placeholder.jpg');
    }
    
    /**
     * Get the URL of the first gallery image.
     *
     * @return string
     */
    public function getFirstGalleryImageUrlAttribute()
    {
        return $this->hasMedia('gallery')
            ? $this->getFirstMedia('gallery')->getUrl('preview')
            : asset('images/placeholder.jpg');
    }
    
    /**
     * Get the thumbnail URL of the featured image.
     *
     * @return string
     */
    public function getFeaturedImageThumbnailUrlAttribute()
    {
        return $this->hasMedia('featured_image')
            ? $this->getFirstMedia('featured_image')->getUrl('thumb')
            : asset('images/placeholder.jpg');
    }
    
    /**
     * Get the thumbnail URL of the first gallery image.
     *
     * @return string
     */
    public function getFirstGalleryImageThumbnailUrlAttribute()
    {
        return $this->hasMedia('gallery')
            ? $this->getFirstMedia('gallery')->getUrl('thumb')
            : asset('images/placeholder.jpg');
    }
}
