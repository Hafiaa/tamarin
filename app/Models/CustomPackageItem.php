<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomPackageItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'service_item_id',
        'quantity',
        'unit_price',
        'total_price',
        'options',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'options' => 'array',
    ];

    /**
     * Get the reservation that owns the custom package item.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the service item that owns the custom package item.
     */
    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::saving(function ($customPackageItem) {
            if (empty($customPackageItem->unit_price) && $customPackageItem->serviceItem) {
                $customPackageItem->unit_price = $customPackageItem->serviceItem->base_price;
            }
            
            if (empty($customPackageItem->total_price) && $customPackageItem->unit_price && $customPackageItem->quantity) {
                $customPackageItem->total_price = $customPackageItem->unit_price * $customPackageItem->quantity;
            }
        });
    }
}
