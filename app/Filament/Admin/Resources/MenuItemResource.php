<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuItemResource\Pages;
use App\Filament\Admin\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';
    
    protected static ?string $navigationGroup = 'Manajemen Menu';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $recordTitleAttribute = 'name';
    
    public static function getNavigationLabel(): string
    {
        return 'Item Menu';
    }
    
    public static function getModelLabel(): string
    {
        return 'Item Menu';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Item Menu';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Menu')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Menu')
                            ->required()
                            ->maxLength(255),
                            
                        Select::make('menu_category_id')
                            ->label('Kategori Menu')
                            ->options(MenuCategory::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                            
                        TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                            
                        Select::make('type')
                            ->label('Tipe Menu')
                            ->options([
                                'food' => 'Makanan',
                                'beverage' => 'Minuman',
                                'both' => 'Keduanya',
                            ])
                            ->required()
                            ->default('both'),
                            
                        Toggle::make('is_available')
                            ->label('Tersedia')
                            ->default(true),
                            
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
                    
                Section::make('Deskripsi')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi Menu')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Foto Menu')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Foto Menu')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->directory('menu-items')
                            ->visibility('public'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn () => asset('images/default-menu.jpg'))
                    ->toggleable(),
                    
                TextColumn::make('name')
                    ->label('Nama Menu')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('menuCategory.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'food' => 'Makanan',
                            'beverage' => 'Minuman',
                            'both' => 'Keduanya',
                            default => $state,
                        };
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'food' => 'warning',
                            'beverage' => 'info',
                            'both' => 'success',
                            default => 'gray',
                        };
                    }),
                    
                IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('menu_category_id')
                    ->label('Kategori Menu')
                    ->relationship('menuCategory', 'name'),
                    
                SelectFilter::make('type')
                    ->label('Tipe Menu')
                    ->options([
                        'food' => 'Makanan',
                        'beverage' => 'Minuman',
                        'both' => 'Keduanya',
                    ]),
                    
                TernaryFilter::make('is_available')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang dipilih'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tidak ada relasi yang ditampilkan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
