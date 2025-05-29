<?php

namespace App\Filament\Admin\Resources\PackageTemplateResource\Pages;

use App\Filament\Admin\Resources\PackageTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageTemplates extends ListRecords
{
    protected static string $resource = PackageTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Template Paket'),
        ];
    }
}
