<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageTemplateResource\Pages;
use App\Filament\Resources\PackageTemplateResource\RelationManagers;
use App\Models\PackageTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageTemplateResource extends Resource
{
    protected static ?string $model = PackageTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Paket';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $modelLabel = 'Template Paket';
    
    protected static ?string $pluralModelLabel = 'Template Paket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Paket')
                    ->schema([
                        // Field untuk upload featured image
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Foto Utama')
                            ->image()
                            ->directory('package-featured')
                            ->visibility('public')
                            ->imagePreviewHeight('250')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                            
                        // Field untuk upload gallery images
                        Forms\Components\FileUpload::make('gallery')
                            ->label('Galeri Foto')
                            ->multiple()
                            ->image()
                            ->directory('package-gallery')
                            ->visibility('public')
                            ->imagePreviewHeight('250')
                            ->openable()
                            ->downloadable()
                            ->enableReordering()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('base_price')
                            ->label('Harga Dasar')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        Forms\Components\Select::make('event_type_id')
                            ->label('Jenis Acara')
                            ->relationship('eventType', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventType.name')
                    ->label('Jenis Acara')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackageTemplates::route('/'),
            'create' => Pages\CreatePackageTemplate::route('/create'),
            'edit' => Pages\EditPackageTemplate::route('/{record}/edit'),
        ];
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }
    
    protected function getHeaderActions(): array
    {
        return [
            // Hapus tombol upload_images karena sudah tidak diperlukan
        ];
    }
    
    public static function afterCreate(PackageTemplate $record, array $data): void
    {
        // Handle featured image upload
        if (request()->hasFile('featured_image')) {
            $record->clearMediaCollection('featured_image');
            $record->addMediaFromRequest('featured_image')
                 ->usingName($record->name . '-featured')
                 ->usingFileName(uniqid() . '.' . request()->file('featured_image')->getClientOriginalExtension())
                 ->toMediaCollection('featured_image', 'public');
        }
        
        // Handle gallery images upload
        if (request()->hasFile('gallery')) {
            foreach (request()->file('gallery') as $image) {
                $record->addMedia($image->getRealPath())
                     ->usingName($record->name . '-gallery-' . uniqid())
                     ->usingFileName(uniqid() . '.' . $image->getClientOriginalExtension())
                     ->toMediaCollection('gallery', 'public');
            }
        }
    }
    
    public static function afterUpdate(PackageTemplate $record, array $data): void
    {
        // Handle featured image update
        if (request()->hasFile('featured_image')) {
            $record->clearMediaCollection('featured_image');
            $record->addMediaFromRequest('featured_image')
                 ->usingName($record->name . '-featured')
                 ->usingFileName(uniqid() . '.' . request()->file('featured_image')->getClientOriginalExtension())
                 ->toMediaCollection('featured_image', 'public');
        }
        
        // Handle gallery images addition (append, not replace)
        if (request()->hasFile('gallery')) {
            foreach (request()->file('gallery') as $image) {
                $record->addMedia($image->getRealPath())
                     ->usingName($record->name . '-gallery-' . uniqid())
                     ->usingFileName(uniqid() . '.' . $image->getClientOriginalExtension())
                     ->toMediaCollection('gallery', 'public');
            }
        }
    }
}
