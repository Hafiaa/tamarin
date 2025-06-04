<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->required(),
                Forms\Components\TextInput::make('payment_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'confirmed' => 'Confirmed',
                        'declined' => 'Declined',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->default('pending')
                    ->reactive(),
                Forms\Components\DatePicker::make('due_date')
                    ->required(),
                Forms\Components\DatePicker::make('payment_date')
                    ->visible(function (\Closure $get) {
                        return in_array($get('status'), ['paid', 'confirmed', 'refunded']);
                    }),
                Forms\Components\Textarea::make('rejection_reason')
                    ->visible(function (\Closure $get) {
                        return $get('status') === 'declined';
                    })
                    ->required(function (\Closure $get) {
                        return $get('status') === 'declined';
                    })
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation.id')
                    ->label('Reservation ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->searchable()
                    ->badge()
                    ->color(function (string $state): string {
                        switch ($state) {
                            case 'deposit':
                                return 'info';
                            case 'full_payment':
                                return 'primary';
                            case 'installment':
                                return 'warning';
                            case 'refund':
                                return 'danger';
                            default:
                                return 'secondary';
                        }
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (string $state): string {
                        switch ($state) {
                            case 'pending':
                                return 'warning';
                            case 'paid':
                                return 'info';
                            case 'confirmed':
                                return 'success';
                            case 'declined':
                            case 'cancelled':
                                return 'danger';
                            case 'refunded':
                                return 'secondary';
                            default:
                                return 'gray';
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(function (Payment $record) {
                        return now() > $record->due_date && !in_array($record->status, ['paid', 'confirmed', 'refunded']) ? 'danger' : null;
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable()
                    ->toggleable(true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'confirmed' => 'Confirmed',
                        'declined' => 'Declined',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\Filter::make('overdue')
                    ->query(function (Builder $query) {
                        return $query->where('due_date', '<', now())
                            ->whereNotIn('status', ['paid', 'confirmed', 'refunded']);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->icon('heroicon-o-check')
                    ->action(function (Payment $record) {
                        return $record->update(['status' => 'confirmed']);
                    })
                    ->visible(function (Payment $record) {
                        return in_array($record->status, ['pending', 'paid']);
                    })
                    ->color('success'),
                Tables\Actions\Action::make('decline')
                    ->icon('heroicon-o-x-mark')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (array $data, Payment $record) {
                        $record->update([
                            'status' => 'declined',
                            'rejection_reason' => $data['rejection_reason']
                        ]);
                    })
                    ->visible(function (Payment $record) {
                        return in_array($record->status, ['pending', 'paid']);
                    })
                    ->color('danger'),
                Tables\Actions\Action::make('markAsPaid')
                    ->icon('heroicon-o-banknotes')
                    ->action(function (Payment $record) {
                        return $record->update([
                            'status' => 'paid',
                            'payment_date' => now()
                        ]);
                    })
                    ->visible(function (Payment $record) {
                        return $record->status === 'pending';
                    })
                    ->color('info'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsConfirmed')
                        ->icon('heroicon-o-check')
                        ->action(function (array $records) {
                        return Payment::whereIn('id', collect($records)->pluck('id'))
                            ->update(['status' => 'confirmed']);
                    })
                        ->deselectRecordsAfterCompletion()
                        ->color('success'),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
