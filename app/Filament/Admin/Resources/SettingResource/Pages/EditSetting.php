<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    // Clear the cache when a setting is deleted
                    Cache::forget("setting.{$this->record->group}.{$this->record->name}");
                }),
        ];
    }
    
    protected function afterSave(): void
    {
        // Clear the cache when a setting is updated
        Cache::forget("setting.{$this->record->group}.{$this->record->name}");
    }
}
