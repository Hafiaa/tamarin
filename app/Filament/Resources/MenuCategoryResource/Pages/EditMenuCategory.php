<?php

namespace App\Filament\Resources\MenuCategoryResource\Pages;

use App\Filament\Resources\MenuCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuCategory extends EditRecord
{
    protected static string $resource = MenuCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function () {
                    // Prevent deletion if category has menu items
                    if ($this->record->menuItems()->count() > 0) {
                        $this->notify(
                            'danger',
                            'Tidak dapat menghapus kategori yang memiliki menu. Hapus atau pindahkan menu terlebih dahulu.'
                        );
                        $this->halt();
                    }
                }),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function afterSave(): void
    {
        // Clear menu cache if needed
        // Cache::tags(['menu_categories', 'menu_category_' . $this->record->id])->flush();
    }
}
