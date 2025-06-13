<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    
    protected static ?string $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('group')
                            ->label('Grup')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('contoh: general, payment, theme'),
                            
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Setting')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('contoh: site_title, primary_color'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Nilai Setting')
                    ->schema([
                        Forms\Components\Select::make('value_type')
                            ->label('Tipe Nilai')
                            ->options([
                                'text' => 'Teks',
                                'number' => 'Angka',
                                'boolean' => 'Ya/Tidak',
                                'select' => 'Pilihan Dropdown',
                            ])
                            ->default('text')
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('payload', [])),
                            
                        Forms\Components\Textarea::make('payload')
                            ->label('Nilai')
                            ->json()
                            ->columnSpanFull()
                            ->helperText('Untuk tipe "Pilihan Dropdown", gunakan format JSON: {"option1":"Label 1","option2":"Label 2"}'),
                            
                        Forms\Components\ViewField::make('preview')
                            ->view('filament.forms.components.setting-preview')
                            ->hidden(fn ($get) => $get('value_type') !== 'select'),
                    ]),
                    
                Forms\Components\Toggle::make('is_recurring_yearly')
                    ->label('Berulang Setiap Tahun')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payload')
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                    ->wrap()
                    ->html(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Setting $record) {
                        // Clear the cache when a setting is deleted
                        Cache::forget("setting.{$record->group}.{$record->name}");
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }    
}
