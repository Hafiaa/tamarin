<?php

namespace App\Filament\Admin\Resources\SettingsResource\Pages;

use App\Filament\Admin\Resources\SettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingsResource::class;
    
    protected function afterCreate(): void
    {
        // Clear the cache when a new setting is created
        Cache::forget("setting.{$this->record->group}.{$this->record->name}");
    }
}
