<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the package templates for the event type.
     */
    public function packageTemplates()
    {
        return $this->hasMany(PackageTemplate::class);
    }
    
    /**
     * Get the reservations for the event type.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
