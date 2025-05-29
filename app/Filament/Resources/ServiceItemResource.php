<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceItemResource\Pages;
use App\Models\ServiceItem;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceItemResource extends Resource
{
    protected static ?string $model = ServiceItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Service Item';
    protected static ?string $navigationLabel = 'Service Items';
    protected static ?string $slug = 'service-items';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
                
            Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
                
            TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('Rp')
                ->default(0),
                
            TextInput::make('category')
                ->maxLength(255),
                
            Toggle::make('is_available')
                ->default(true)
                ->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                    
                IconColumn::make('is_available')
                    ->boolean()
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceItems::route('/'),
            'create' => Pages\CreateServiceItem::route('/create'),
            'edit' => Pages\EditServiceItem::route('/{record}/edit'),
        ];
    }
}
