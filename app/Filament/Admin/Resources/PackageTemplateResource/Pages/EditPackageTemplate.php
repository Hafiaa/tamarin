<?php

namespace App\Filament\Admin\Resources\PackageTemplateResource\Pages;

use App\Filament\Admin\Resources\PackageTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackageTemplate extends EditRecord
{
    protected static string $resource = PackageTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
