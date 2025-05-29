<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use App\Models\User;
use App\Models\EventType;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationGroup = 'Manajemen';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $modelLabel = 'Reservasi';
    
    protected static ?string $pluralModelLabel = 'Reservasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Reservasi')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('event_type_id')
                            ->label('Jenis Acara')
                            ->relationship('eventType', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Jenis Acara')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi'),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ]),
                        Forms\Components\DatePicker::make('event_date')
                            ->label('Tanggal Acara')
                            ->required()
                            ->minDate(now()),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->required(),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->required(),
                        Forms\Components\TextInput::make('guest_count')
                            ->label('Jumlah Tamu')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Forms\Components\Textarea::make('special_requests')
                            ->label('Permintaan Khusus')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Konfirmasi',
                                'confirmed' => 'Dikonfirmasi',
                                'cancelled' => 'Dibatalkan',
                                'completed' => 'Selesai',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Disetujui')
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventType.name')
                    ->label('Jenis Acara')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Waktu')
                    ->time(),
                Tables\Columns\TextColumn::make('guest_count')
                    ->label('Tamu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                    ])
                    ->rules(['required']),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'confirmed' => 'Dikonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                    ]),
                Tables\Filters\Filter::make('event_date')
                    ->form([
                        Forms\Components\DatePicker::make('from_date'),
                        Forms\Components\DatePicker::make('until_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('event_date', '>=', $date),
                            )
                            ->when(
                                $data['until_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('event_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from_date'] ?? null) {
                            $indicators['from_date'] = 'Dari tanggal ' . Carbon::parse($data['from_date'])->toFormattedDateString();
                        }
                        if ($data['until_date'] ?? null) {
                            $indicators['until_date'] = 'Sampai tanggal ' . Carbon::parse($data['until_date'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
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
            ->defaultSort('event_date', 'desc');
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
