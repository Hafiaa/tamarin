<?php

namespace App\Filament\Admin\Resources\BlockedDateResource\Pages;

use App\Filament\Admin\Resources\BlockedDateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedDate extends CreateRecord
{
    protected static string $resource = BlockedDateResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
