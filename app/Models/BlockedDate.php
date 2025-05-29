<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BlockedDate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'reason',
        'is_recurring_yearly',
        'blocked_by',
        'blocked_until',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_recurring_yearly' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
        'blocked_until',
        'created_at',
        'updated_at',
    ];
    
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_recurring_yearly' => false,
    ];
    
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Set default date if empty
            if (empty($model->date)) {
                $model->date = now()->toDateString();
            }
            // Ensure date is in Y-m-d format
            if ($model->date instanceof Carbon) {
                $model->date = $model->date->format('Y-m-d');
            } elseif (is_string($model->date)) {
                $model->date = Carbon::parse($model->date)->format('Y-m-d');
            }
            
            // Ensure blocked_until is properly formatted if set
            if ($model->blocked_until && $model->blocked_until instanceof Carbon) {
                $model->blocked_until = $model->blocked_until->format('Y-m-d');
            }
        });
    }
    
    /**
     * Check if a specific date is blocked.
     *
     * @param  string|\Carbon\Carbon  $date
     * @return bool
     */
    public static function isDateBlocked($date): bool
    {
        $dateObj = $date instanceof Carbon ? $date : Carbon::parse($date);
        $formattedDate = $dateObj->format('Y-m-d');
        
        return static::query()
            ->where(function ($query) use ($formattedDate, $dateObj) {
                // Check for exact date match
                $query->where('date', $formattedDate);
                
                // Check for date range (blocked_until)
                $query->orWhere(function ($q) use ($formattedDate) {
                    $q->where('date', '<=', $formattedDate)
                      ->where('blocked_until', '>=', $formattedDate);
                });
                
                // Check for recurring yearly dates
                $query->orWhere(function ($q) use ($dateObj) {
                    $q->where('is_recurring_yearly', true)
                      ->whereMonth('date', $dateObj->month)
                      ->whereDay('date', $dateObj->day);
                });
            })
            ->exists();
    }
    
    /**
     * Get all blocked dates within a date range.
     *
     * @param  string|\Carbon\Carbon  $startDate
     * @param  string|\Carbon\Carbon  $endDate
     * @return \Illuminate\Support\Collection
     */
    public static function getBlockedDatesInRange($startDate, $endDate)
    {
        $start = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);
        $end = $endDate instanceof Carbon ? $endDate : Carbon::parse($endDate);
        $startFormatted = $start->format('Y-m-d');
        $endFormatted = $end->format('Y-m-d');
        
        return static::query()
            ->where(function ($query) use ($startFormatted, $endFormatted) {
                // Get dates that start within the range
                $query->whereBetween('date', [$startFormatted, $endFormatted]);
                
                // Get dates that end within the range
                $query->orWhere(function ($q) use ($startFormatted, $endFormatted) {
                    $q->where('blocked_until', '>=', $startFormatted)
                      ->where('date', '<=', $endFormatted);
                });
                
                // Get dates that span the entire range
                $query->orWhere(function ($q) use ($startFormatted, $endFormatted) {
                    $q->where('date', '<=', $startFormatted)
                      ->where('blocked_until', '>=', $endFormatted);
                });
                
                // Get recurring yearly dates that fall within the range
                $query->orWhere('is_recurring_yearly', true);
            })
            ->get();
    }
}
