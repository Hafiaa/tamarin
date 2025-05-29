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
}
