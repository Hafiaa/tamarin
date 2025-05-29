<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    
    protected static ?string $navigationGroup = 'Menu';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $modelLabel = 'Item Menu';
    
    protected static ?string $pluralModelLabel = 'Item Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Item Menu')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Item')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state) {
                                request()->merge(['slug' => Str::slug($state)]);
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique('menu_items', 'slug', true),
                        Forms\Components\Select::make('menu_category_id')
                            ->label('Kategori')
                            ->relationship('menuCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi'),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->directory('menu-items')
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('600'),
                        Forms\Components\Toggle::make('is_available')
                            ->label('Tersedia')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->default(false)
                            ->onColor('primary')
                            ->offColor('gray')
                            ->inline(false),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('menuCategory.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true, true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu_category_id')
                    ->relationship('menuCategory', 'name')
                    ->label('Kategori')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('is_available')
                    ->label('Tersedia')
                    ->query(fn (Builder $query): Builder => $query->where('is_available', true)),
                Tables\Filters\Filter::make('is_featured')
                    ->label('Unggulan')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'view' => Pages\ViewMenuItem::route('/{record}'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
