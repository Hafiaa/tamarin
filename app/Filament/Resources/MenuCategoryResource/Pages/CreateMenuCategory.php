<?php

namespace App\Filament\Resources\MenuCategoryResource\Pages;

use App\Filament\Resources\MenuCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuCategory extends CreateRecord
{
    protected static string $resource = MenuCategoryResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function afterCreate(): void
    {
        // Clear menu cache if needed
        // Cache::tags(['menu_categories'])->flush();
    }
}
