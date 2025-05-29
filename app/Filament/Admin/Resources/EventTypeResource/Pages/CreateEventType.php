<?php

namespace App\Filament\Admin\Resources\EventTypeResource\Pages;

use App\Filament\Admin\Resources\EventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventType extends CreateRecord
{
    protected static string $resource = EventTypeResource::class;
}
