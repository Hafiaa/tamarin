<?php

namespace App\Filament\Resources\EventTypeResource\Pages;

use App\Filament\Resources\EventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateEventType extends CreateRecord
{
    protected static string $resource = EventTypeResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Clear cache when a new event type is created
        Cache::tags(['event-types'])->flush();
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jenis acara berhasil ditambahkan';
    }
}
