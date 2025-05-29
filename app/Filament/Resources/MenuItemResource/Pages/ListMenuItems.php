<?php

namespace App\Filament\Resources\MenuItemResource\Pages;

use App\Filament\Resources\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Item Menu')
                ->icon('heroicon-o-plus'),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'available' => Tab::make('Tersedia')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_available', true)),
            'featured' => Tab::make('Unggulan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_featured', true)),
        ];
    }
    
    public function getDefaultActiveTab(): string
    {
        return 'available';
    }
}
