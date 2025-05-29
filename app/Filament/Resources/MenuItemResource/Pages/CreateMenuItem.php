<?php

namespace App\Filament\Resources\MenuItemResource\Pages;

use App\Filament\Resources\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function afterCreate(): void
    {
        // Clear menu cache if needed
        Cache::tags(['menu_items'])->flush();
    }
}
