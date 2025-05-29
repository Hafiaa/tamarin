<?php

namespace App\Filament\Resources\EventTypeResource\Pages;

use App\Filament\Resources\EventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditEventType extends EditRecord
{
    protected static string $resource = EventTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Lihat')
                ->color('gray'),
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus Jenis Acara')
                ->modalDescription('Apakah Anda yakin ingin menghapus jenis acara ini? Tindakan ini tidak dapat dibatalkan.')
                ->successNotificationTitle('Jenis acara berhasil dihapus'),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();
        return $data;
    }
    
    protected function afterSave(): void
    {
        // Clear cache when an event type is updated
        Cache::tags(['event-types', 'event-type-' . $this->record->id])->flush();
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Jenis acara berhasil diperbarui';
    }
}
