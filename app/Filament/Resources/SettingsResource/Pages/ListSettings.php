<?php

namespace App\Filament\Resources\SettingsResource\Pages;

use App\Filament\Resources\SettingsResource;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class ListSettings extends Page
{
    protected static string $resource = SettingsResource::class;
    protected static string $view = 'filament.resources.settings-resource.pages.list-settings';
    
    public function mount(): void
    {
        // Redirect to edit page
        $this->redirect(EditSettings::getUrl());
    }
}
