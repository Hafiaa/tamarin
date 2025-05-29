<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PackageTemplateResource\Pages;
use App\Models\EventType;
use App\Models\PackageTemplate;
use App\Models\ServiceItem;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PackageTemplateResource extends Resource
{
    protected static ?string $model = PackageTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Manajemen Paket';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return 'Template Paket';
    }

    public static function getModelLabel(): string
    {
        return 'Template Paket';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Template Paket';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Paket')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255),

                        Select::make('event_type_id')
                            ->label('Jenis Acara')
                            ->options(EventType::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('base_price')
                            ->label('Harga Dasar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Item Layanan')
                    ->schema([
                        Repeater::make('serviceItems')
                            ->label('Item Layanan')
                            ->relationship()
                            ->schema([
                                Select::make('service_item_id')
                                    ->label('Item Layanan')
                                    ->options(ServiceItem::where('is_available', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $item = ServiceItem::find($state);
                                            if ($item) {
                                                $set('custom_price', $item->price);
                                            }
                                        }
                                    }),

                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),

                                TextInput::make('custom_price')
                                    ->label('Harga Kustom')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Textarea::make('notes')
                                    ->label('Catatan')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->itemLabel(function (array $state): ?string {
                                if (isset($state['service_item_id'])) {
                                    $item = ServiceItem::find($state['service_item_id']);
                                    return $item ? $item->name : null;
                                }
                                return null;
                            })
                            ->collapsible()
                            ->collapseAllAction(function (Forms\Components\Actions\Action $action) {
                                return $action->label('Tutup Semua');
                            })
                            ->expandAllAction(function (Forms\Components\Actions\Action $action) {
                                return $action->label('Buka Semua');
                            })
                            ->reorderableWithButtons()
                            ->defaultItems(0),
                    ]),

                Section::make('Galeri Foto')
                    ->schema([
                        FileUpload::make('gallery')
                            ->label('Foto Paket')
                            ->multiple()
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->directory('package-templates')
                            ->visibility('public')
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('eventType.name')
                    ->label('Jenis Acara')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('serviceItems.name')
                    ->label('Item Layanan')
                    ->badge()
                    ->color('primary')
                    ->limitList(2),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

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
                SelectFilter::make('event_type_id')
                    ->label('Jenis Acara')
                    ->options(EventType::where('is_active', true)->pluck('name', 'id')),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
