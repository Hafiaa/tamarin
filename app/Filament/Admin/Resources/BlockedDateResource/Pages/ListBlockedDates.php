<?php

namespace App\Filament\Admin\Resources\BlockedDateResource\Pages;

use App\Filament\Admin\Resources\BlockedDateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlockedDates extends ListRecords
{
    protected static string $resource = BlockedDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
