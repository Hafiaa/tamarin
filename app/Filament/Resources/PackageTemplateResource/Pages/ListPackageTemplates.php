<?php

namespace App\Filament\Resources\PackageTemplateResource\Pages;

use App\Filament\Resources\PackageTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageTemplates extends ListRecords
{
    protected static string $resource = PackageTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
