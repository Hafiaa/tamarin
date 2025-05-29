<?php

namespace App\Filament\Admin\Resources\MenuCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'menuItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Menu')
                    ->required()
                    ->maxLength(255),
                    
                Select::make('type')
                    ->label('Tipe Menu')
                    ->options([
                        'food' => 'Makanan',
                        'beverage' => 'Minuman',
                        'both' => 'Keduanya',
                    ])
                    ->required()
                    ->default('both'),
                    
                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                    
                Textarea::make('description')
                    ->label('Deskripsi Menu')
                    ->rows(3)
                    ->columnSpanFull(),
                    
                Toggle::make('is_available')
                    ->label('Tersedia')
                    ->default(true),
                    
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                    
                FileUpload::make('images')
                    ->label('Foto Menu')
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->directory('menu-items')
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('images')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(function () {
                        return asset('images/default-menu.jpg');
                    }),
                    
                TextColumn::make('name')
                    ->label('Nama Menu')
                    ->searchable()
                    ->sortable(),
                    
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Menu'),
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
}
