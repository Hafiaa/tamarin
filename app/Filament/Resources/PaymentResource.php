<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationGroup = 'Manajemen';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $modelLabel = 'Pembayaran';
    
    protected static ?string $pluralModelLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('reservation_id')
                            ->label('Reservasi')
                            ->relationship('reservation', 'id')
                            ->getOptionLabelFromRecordUsing(fn (Reservation $record) => "Reservasi #{$record->id} - " . ($record->eventType ? $record->eventType->name : '') . ' - ' . $record->event_date?->format('d M Y'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
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
                                    ->required(),
                                Forms\Components\DatePicker::make('event_date')
                                    ->label('Tanggal Acara')
                                    ->required()
                                    ->minDate(now()),
                                Forms\Components\TextInput::make('guest_count')
                                    ->label('Jumlah Tamu')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'bank_transfer' => 'Transfer Bank',
                                'credit_card' => 'Kartu Kredit',
                                'cash' => 'Tunai',
                                'other' => 'Lainnya',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('ID Transaksi')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Status Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Konfirmasi',
                                'paid' => 'Dibayar',
                                'failed' => 'Gagal',
                                'refunded' => 'Dikembalikan',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Waktu Pembayaran')
                            ->displayFormat('d F Y H:i')
                            ->timezone('Asia/Jakarta'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation.code')
                    ->label('Kode Reservasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->formatStateUsing(function ($record, $state) {
                        $methods = array(
                            'bank_transfer' => 'Transfer Bank',
                            'credit_card' => 'Kartu Kredit',
                            'cash' => 'Tunai',
                            'other' => 'Lainnya'
                        );
                        return isset($methods[$state]) ? $methods[$state] : 'Lainnya';
                    }),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'paid' => 'Dibayar',
                        'failed' => 'Gagal',
                        'refunded' => 'Dikembalikan',
                    ])
                    ->rules(['required']),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true, true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'paid' => 'Dibayar',
                        'failed' => 'Gagal',
                        'refunded' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'cash' => 'Tunai',
                        'other' => 'Lainnya',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from_date'),
                        Forms\Components\DatePicker::make('until_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
