<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group',
        'name',
        'payload',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
    ];
    
    /**
     * Get a setting value by group and name, or by a single key.
     *
     * @param string $groupOrKey
     * @param string|mixed $nameOrDefault
     * @param mixed $default
     * @return mixed
     */
    public static function get($groupOrKey, $nameOrDefault = null, $default = null)
    {
        // Handle the case where only a single key is provided (for backward compatibility)
        if ($nameOrDefault === null) {
            return Cache::rememberForever('setting.' . $groupOrKey, function () use ($groupOrKey, $default) {
                $setting = self::where('name', $groupOrKey)->first();
                return $setting ? $setting->payload : $default;
            });
        }
        
        // Handle the case where both group and name are provided
        $group = $groupOrKey;
        $name = $nameOrDefault;
        $default = func_num_args() === 3 ? $default : $nameOrDefault;
        
        $cacheKey = "setting.{$group}.{$name}";
        
        return Cache::rememberForever($cacheKey, function () use ($group, $name, $default) {
            $setting = self::where('group', $group)
                ->where('name', $name)
                ->first();
                
            return $setting ? $setting->payload : $default;
        });
    }
    
    /**
     * Set a setting value by group and name.
     *
     * @param string $group
     * @param string $name
     * @param mixed $value
     * @return Setting
     */
    public static function set(string $group, string $name, $value)
    {
        $cacheKey = "setting.{$group}.{$name}";
        Cache::forget($cacheKey);
        
        $setting = self::updateOrCreate(
            ['group' => $group, 'name' => $name],
            ['payload' => $value]
        );
        
        return $setting;
    }
    
    /**
     * Get all settings, optionally filtered by group.
     *
     * @param string|null $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAll(?string $group = null)
    {
        $cacheKey = $group ? "settings.group.{$group}" : 'settings.all';
        
        return Cache::rememberForever($cacheKey, function () use ($group) {
            $query = self::query();
            
            if ($group) {
                $query->where('group', $group);
            }
            
            return $query->get();
        });
    }
    
    /**
     * Delete a setting by group and name.
     *
     * @param string $group
     * @param string $name
     * @return bool
     */
    public static function deleteSetting(string $group, string $name)
    {
        $cacheKey = "setting.{$group}.{$name}";
        Cache::forget($cacheKey);
        
        // Also clear the group cache if it exists
        if (Cache::has("settings.group.{$group}")) {
            Cache::forget("settings.group.{$group}");
        }
        
        // Clear the all settings cache if it exists
        if (Cache::has('settings.all')) {
            Cache::forget('settings.all');
        }
        
        return self::where('group', $group)
            ->where('name', $name)
            ->delete() > 0;
    }
}
