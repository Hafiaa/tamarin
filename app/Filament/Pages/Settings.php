<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $slug = 'settings';
    
    public static function getNavigationBadge(): ?string
    {
        return null;
    }
    
    protected static ?string $title = 'Settings';
    
    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1000;
    }
    
    public static function getRouteName(?string $panel = null): string
    {
        $panel = $panel ?? 'admin';
        return "filament.{$panel}.pages.settings";
    }
    
    public static function getRoutePath(): string
    {
        return 'settings';
    }
    
    public function mount(): void
    {
        parent::mount();
        $this->form->fill([
            // Initialize your form fields here if needed
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Name')
                            ->required(),
                        // Add more settings fields as needed
                    ]),
            ]);
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        // Save your settings here
        // For example:
        // foreach ($data as $key => $value) {
        //     setting([$key => $value])->save();
        // }
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
