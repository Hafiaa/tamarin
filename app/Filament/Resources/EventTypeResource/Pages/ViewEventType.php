<?php

namespace App\Filament\Resources\EventTypeResource\Pages;

use App\Filament\Resources\EventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEventType extends ViewRecord
{
    protected static string $resource = EventTypeResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->icon('heroicon-o-pencil'),
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->icon('heroicon-o-trash')
                ->modalHeading('Hapus Jenis Acara')
                ->modalDescription('Apakah Anda yakin ingin menghapus jenis acara ini? Tindakan ini tidak dapat dibatalkan.')
                ->successNotificationTitle('Jenis acara berhasil dihapus'),
            Actions\Action::make('kembali')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
    
    // Widgets sementara dinonaktifkan karena masalah kompatibilitas
    protected function getHeaderWidgets(): array
    {
        return [];
    }
    
    protected function getFooterWidgets(): array
    {
        return [];
    }
}
