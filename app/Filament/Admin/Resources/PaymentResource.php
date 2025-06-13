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
                    ->visible(fn (\Filament\Forms\Get $get): bool => in_array($get('status'), ['paid', 'confirmed', 'refunded'])),
                Forms\Components\Textarea::make('rejection_reason')
                    ->visible(fn (\Filament\Forms\Get $get): bool => $get('status') === 'declined')
                    ->required(fn (\Filament\Forms\Get $get): bool => $get('status') === 'declined')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation.id')
                    ->label('ID Reservasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation.code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Tipe Pembayaran')
                    ->formatStateUsing(function (string $state): string {
                        if ($state == 'dp1') return 'DP 1';
                        if ($state == 'dp2') return 'DP 2';
                        if ($state == 'down_payment') return 'Uang Muka';
                        if ($state == 'full_payment') return 'Pelunasan';
                        if ($state == 'revision') return 'Revisi';
                        return ucfirst(str_replace('_', ' ', $state));
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        switch ($state) {
                            case 'dp1':
                            case 'dp2':
                            case 'down_payment':
                                return 'info';
                            case 'full_payment':
                                return 'success';
                            case 'revision':
                                return 'warning';
                            default:
                                return 'secondary';
                        }
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->formatStateUsing(function (string $state): string {
                        if ($state == 'bca') return 'BCA';
                        if ($state == 'bni') return 'BNI';
                        if ($state == 'mandiri') return 'Mandiri';
                        if ($state == 'e_wallet') return 'E-Wallet';
                        return ucfirst(str_replace('_', ' ', $state));
                    })
                    ->badge()
                    ->color('info'),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Bayar')
                    ->getStateUsing(function (Payment $record) {
                        $media = $record->getFirstMedia('payment_proof');
                        return $media ? $media->getUrl() : null;
                    })
                    ->extraImgAttributes(function() { return ['class' => 'h-12 w-auto rounded']; })
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function (string $state): string {
                        if ($state == 'pending') return 'Menunggu';
                        if ($state == 'paid') return 'Dibayar';
                        if ($state == 'confirmed') return 'Dikonfirmasi';
                        if ($state == 'declined') return 'Ditolak';
                        if ($state == 'cancelled') return 'Dibatalkan';
                        if ($state == 'refunded') return 'Dikembalikan';
                        if ($state == 'payment_pending_verification') return 'Menunggu Verifikasi';
                        return ucfirst(str_replace('_', ' ', $state));
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        switch ($state) {
                            case 'pending':
                            case 'payment_pending_verification':
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
                    ->label('Jatuh Tempo')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->color(function (Payment $record) {
                        if (now() > $record->due_date && !in_array($record->status, ['paid', 'confirmed', 'refunded', 'approved'])) {
                            return 'danger';
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tgl Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
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
                Tables\Actions\Action::make('view_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->modalContent(function (Payment $record) {
                        $media = $record->getFirstMedia('payment_proof');
                        if (!$media) {
                            return 'Bukti pembayaran tidak tersedia';
                        }
                        
                        $url = $media->getUrl();
                        $mimeType = $media->mime_type;
                        
                        if (strpos($mimeType, 'image/') === 0) {
                            return "<img src='{$url}' class='w-full rounded' alt='Bukti Pembayaran'>";
                        } elseif ($mimeType === 'application/pdf') {
                            return "<embed src='{$url}' type='application/pdf' width='100%' height='600px'>";
                        }
                        
                        return 'Format file tidak didukung: ' . $mimeType;
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->visible(function (Payment $record) { 
                        return $record->getFirstMedia('payment_proof') !== null; 
                    })
                    ->modalWidth('4xl'),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->required(),
                    ])
                    ->action(function (array $data, Payment $record) {
                        $record->update([
                            'status' => 'approved',
                            'admin_notes' => $data['notes'],
                            'verified_at' => now(),
                        ]);
                        
                        // Kirim notifikasi ke user
                        $record->reservation->user->notify(new \App\Notifications\PaymentVerified($record));
                        
                        // Update status reservasi jika diperlukan
                        if (in_array($record->reservation->status, ['awaiting_payment', 'pending'])) {
                            $record->reservation->update(['status' => 'confirmed']);
                        }
                    })
                    ->visible(function (Payment $record) { 
                        return $record->status === 'payment_pending_verification'; 
                    })
                    ->color('success'),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (array $data, Payment $record) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        
                        // Kirim notifikasi ke user
                        $record->reservation->user->notify(new \App\Notifications\PaymentRejected($record));
                    })
                    ->visible(function (Payment $record) { 
                        return in_array($record->status, ['payment_pending_verification', 'pending']); 
                    })
                    ->color('danger'),
                Tables\Actions\EditAction::make()
                    ->visible(function (Payment $record) { 
                        return auth()->user()->can('update', $record); 
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsConfirmed')
                        ->label('Tandai sebagai Dikonfirmasi')
                        ->icon('heroicon-o-check')
                        ->action(function (array $records) {
                            $ids = array_map(function($record) {
                                return $record['id'];
                            }, $records);
                            
                            return Payment::whereIn('id', $ids)
                                ->update(['status' => 'confirmed']);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi')
                        ->modalDescription('Apakah Anda yakin ingin menandai pembayaran yang dipilih sebagai Dikonfirmasi?')
                        ->modalSubmitActionLabel('Ya, konfirmasi')
                        ->modalCancelActionLabel('Batal'),
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
