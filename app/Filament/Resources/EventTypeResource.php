<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventTypeResource\Pages;
use App\Models\EventType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class EventTypeResource extends Resource
{
    protected static ?string $model = EventType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Manajemen';
    
    protected static ?int $navigationSort = 4;
    
    protected static ?string $modelLabel = 'Jenis Acara';
    
    protected static ?string $pluralModelLabel = 'Jenis Acara';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jenis Acara')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Jenis Acara')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state) {
                                request()->merge(['slug' => Str::slug($state)]);
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique('event_types', 'slug', null, 'id'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->directory('event-types')
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675'),
                        Forms\Components\TextInput::make('base_price')
                            ->label('Harga Dasar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\TextInput::make('min_capacity')
                            ->label('Kapasitas Minimum')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('max_capacity')
                            ->label('Kapasitas Maksimum')
                            ->numeric()
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_capacity')
                    ->label('Min. Kapasitas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_capacity')
                    ->label('Maks. Kapasitas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true, true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status Aktif')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
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
            ->defaultSort('name', 'asc');
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
            'index' => Pages\ListEventTypes::route('/'),
            'create' => Pages\CreateEventType::route('/create'),
            'view' => Pages\ViewEventType::route('/{record}'),
            'edit' => Pages\EditEventType::route('/{record}/edit'),
        ];
    }
}
