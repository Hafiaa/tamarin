<?php

namespace App\Filament\Resources\PackageTemplateResource\Pages;

use App\Filament\Resources\PackageTemplateResource;
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
}
