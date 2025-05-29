<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsResource extends Resource
{
    protected static ?string $model = null;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Situs';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationParentItem = 'Pengaturan';
    protected static ?string $modelLabel = 'Pengaturan';
    protected static ?string $pluralModelLabel = 'Pengaturan Situs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Pengaturan')
                    ->tabs([
                        Tab::make('Umum')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Section::make('Informasi Dasar')
                                    ->schema([
                                        FileUpload::make('site_logo')
                                            ->label('Logo Situs')
                                            ->image()
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imageResizeMode('force')
                                            ->imageResizeTargetWidth('200')
                                            ->imageResizeTargetHeight('200')
                                            ->columnSpanFull(),
                                            
                                        TextInput::make('site_name')
                                            ->label('Nama Situs')
                                            ->required()
                                            ->maxLength(255),
                                            
                                        TextInput::make('site_tagline')
                                            ->label('Tagline')
                                            ->maxLength(255)
                                            ->columnSpan(2),
                                            
                                        Textarea::make('site_description')
                                            ->label('Deskripsi Situs')
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                            
                                        FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imageResizeMode('force')
                                            ->imageResizeTargetWidth('64')
                                            ->imageResizeTargetHeight('64')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                            
                        Tab::make('Kontak')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Informasi Kontak')
                                    ->schema([
                                        TextInput::make('email')
                                            ->label('Email Utama')
                                            ->email()
                                            ->required()
                                            ->columnSpan(2),
                                            
                                        TextInput::make('phone')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->columnSpan(2),
                                            
                                        TextInput::make('whatsapp')
                                            ->label('Nomor WhatsApp')
                                            ->tel()
                                            ->helperText('Format: 6281234567890')
                                            ->columnSpan(2),
                                            
                                        Textarea::make('address')
                                            ->label('Alamat')
                                            ->required()
                                            ->columnSpanFull(),
                                            
                                        TextInput::make('working_hours')
                                            ->label('Jam Operasional')
                                            ->required()
                                            ->columnSpanFull(),
                                            
                                        TextInput::make('google_maps')
                                            ->label('Link Google Maps')
                                            ->url()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                            
                        Tab::make('Media Sosial')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Tautan Media Sosial')
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->prefix('facebook.com/')
                                            ->columnSpan(2),
                                            
                                        TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->prefix('instagram.com/')
                                            ->columnSpan(2),
                                            
                                        TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url()
                                            ->prefix('youtube.com/')
                                            ->columnSpan(2),
                                            
                                        TextInput::make('tiktok_url')
                                            ->label('TikTok')
                                            ->url()
                                            ->prefix('tiktok.com/')
                                            ->columnSpan(2),
                                    ])
                                    ->columns(2),
                            ]),
                            
                        Tab::make('Tampilan')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('Tema & Tampilan')
                                    ->schema([
                                        ColorPicker::make('primary_color')
                                            ->label('Warna Utama')
                                            ->default('#3b82f6'),
                                            
                                        ColorPicker::make('secondary_color')
                                            ->label('Warna Sekunder')
                                            ->default('#6b7280'),
                                            
                                        FileUpload::make('hero_image')
                                            ->label('Gambar Hero')
                                            ->image()
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                            
                        Tab::make('Lainnya')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Section::make('Pengaturan Tambahan')
                                    ->schema([
                                        Textarea::make('google_analytics')
                                            ->label('Kode Google Analytics')
                                            ->helperText('Masukkan kode tracking Google Analytics')
                                            ->columnSpanFull(),
                                            
                                        Textarea::make('google_maps_embed')
                                            ->label('Kode Embed Google Maps')
                                            ->helperText('Masukkan kode iframe dari Google Maps')
                                            ->columnSpanFull(),
                                            
                                        Toggle::make('maintenance_mode')
                                            ->label('Mode Maintenance')
                                            ->helperText('Aktifkan untuk mengaktifkan mode maintenance')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengaturan'),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                // Disable bulk actions
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditSettings::route('/'),
        ];
    }
    
    public static function getModel(): string
    {
        return \App\Models\Setting::class;
    }
    
    public static function getNavigationUrl(): string
    {
        // Redirect to the Settings page instead of the resource index
        return route('filament.admin.pages.settings');
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
    
    public static function canDeleteAny(): bool
    {
        return false;
    }
}
