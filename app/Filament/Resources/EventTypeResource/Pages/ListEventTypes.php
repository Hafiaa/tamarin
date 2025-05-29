<?php

namespace App\Filament\Resources\EventTypeResource\Pages;

use App\Filament\Resources\EventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEventTypes extends ListRecords
{
    protected static string $resource = EventTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jenis Acara')
                ->icon('heroicon-o-plus'),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'active' => Tab::make('Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make('Tidak Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
        ];
    }
    
    public function getDefaultActiveTab(): string
    {
        return 'active';
    }
}
