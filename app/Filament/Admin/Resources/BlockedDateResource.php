<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BlockedDateResource\Pages;
use App\Models\BlockedDate;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class BlockedDateResource extends Resource
{
    protected static ?string $model = BlockedDate::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Tanggal Diblokir';
    protected static ?string $pluralModelLabel = 'Daftar Tanggal Diblokir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pemblokiran')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->closeOnDateSelection()
                            ->minDate(now())
                            ->rules(['required', 'date'])
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                            
                        Forms\Components\DatePicker::make('blocked_until')
                            ->label('Tanggal Akhir (Opsional)')
                            ->helperText('Biarkan kosong jika hanya memblokir satu hari')
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->closeOnDateSelection()
                            ->after('date')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Pemblokiran')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                            
                        Forms\Components\Toggle::make('is_recurring_yearly')
                            ->label('Berulang Setiap Tahun')
                            ->helperText('Jika diaktifkan, tanggal ini akan diblokir setiap tahun')
                            ->default(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Mulai')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('blocked_until')
                    ->label('Tanggal Akhir')
                    ->date('d F Y')
                    ->sortable()
                    ->placeholder('Satu hari'),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_recurring_yearly')
                    ->boolean()
                    ->label('Berulang Tahunan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_recurring_yearly')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ])
                    ->label('Berulang Tahunan'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlockedDates::route('/'),
            'create' => Pages\CreateBlockedDate::route('/create'),
            'edit' => Pages\EditBlockedDate::route('/{record}/edit'),
        ];
    }
}
