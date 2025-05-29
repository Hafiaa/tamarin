<?php

namespace App\Filament\Resources\MenuCategoryResource\Pages;

use App\Filament\Resources\MenuCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMenuCategory extends ViewRecord
{
    protected static string $resource = MenuCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
