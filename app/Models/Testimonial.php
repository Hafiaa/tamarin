<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Testimonial extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reservation_id',
        'content',
        'rating',
        'status',
        'is_featured',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending_admin_approval',
        'is_featured' => false,
        'rating' => 5,
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($testimonial) {
            // Ensure user_id is set
            if (empty($testimonial->user_id) && Auth::check()) {
                $testimonial->user_id = Auth::id();
            }

            // Ensure content is set
            if (empty($testimonial->content)) {
                $testimonial->content = 'No content provided';
            }

            // Ensure rating is valid
            $testimonial->rating = max(1, min(5, (int)$testimonial->rating));
        });
    }
    
    /**
     * Get the user that owns the testimonial.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the reservation that owns the testimonial.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
