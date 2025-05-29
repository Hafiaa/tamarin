<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuItem extends Model implements HasMedia
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
        'menu_category_id',
        'type',
        'is_available',
        'sort_order',
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
     * Get the menu category that owns the menu item.
     */
    public function menuCategory(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }
    
    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->singleFile();
    }
}
