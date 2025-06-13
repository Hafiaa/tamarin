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
                    ->extraImgAttributes(function() { 
                        return ['class' => 'h-12 w-auto rounded cursor-pointer hover:opacity-75 transition-opacity'];
                    })
                    ->url(function (Payment $record) {
                        return $record->getFirstMediaUrl('payment_proof');
                    })
                    ->openUrlInNewTab()
                    ->tooltip('Klik untuk melihat lebih besar'),
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
                    ->label('Lihat Bukti Detail')
                    ->icon('heroicon-o-photo')
                    ->modalHeading(function (Payment $record) {
                        return 'Bukti Pembayaran - ' . ($record->reservation ? $record->reservation->code : '');
                    })
                    ->modalContent(function (Payment $record) {
                        $media = $record->getFirstMedia('payment_proof');
                        
                        if (!$media) {
                            return view('filament.components.no-payment-proof');
                        }
                        
                        $mimeType = $media->mime_type;
                        $isImage = strpos($mimeType, 'image/') === 0;
                        $isPdf = $mimeType === 'application/pdf';
                        
                        // Dapatkan path relatif file
                        $relativePath = 'payment_proofs/' . $media->file_name;
                        
                        // Buat URL lengkap
                        $url = asset('storage/' . $relativePath);
                        
                        // Tambahkan timestamp untuk menghindari cache
                        $url .= '?t=' . now()->timestamp;
                        
                        // Log informasi untuk debugging
                        \Log::info('Payment proof media info:', [
                            'id' => $media->id,
                            'file_name' => $media->file_name,
                            'mime_type' => $mimeType,
                            'size' => $media->size,
                            'disk' => $media->disk,
                            'url' => $url,
                            'relative_path' => $relativePath,
                            'storage_path' => storage_path('app/public/' . $relativePath),
                            'public_path' => public_path('storage/' . $relativePath),
                            'file_exists' => file_exists(storage_path('app/public/' . $relativePath))
                        ]);
                        
                        // Jika file tidak ada di lokasi yang diharapkan, coba pindahkan
                        if (!file_exists(storage_path('app/public/' . $relativePath))) {
                            $oldPath = storage_path('app/public/' . $media->getPathRelativeToRoot());
                            if (file_exists($oldPath)) {
                                // Pastikan direktori tujuan ada
                                if (!file_exists(dirname(storage_path('app/public/' . $relativePath)))) {
                                    mkdir(dirname(storage_path('app/public/' . $relativePath)), 0777, true);
                                }
                                rename($oldPath, storage_path('app/public/' . $relativePath));
                            }
                        }
                        
                        return view('filament.components.payment-proof-modal', [
                            'url' => $url,
                            'mimeType' => $mimeType,
                            'isImage' => $isImage,
                            'isPdf' => $isPdf,
                            'filename' => $media->file_name,
                            'fileSize' => static::formatFileSize($media->size),
                            'uploadedAt' => $media->created_at->format('d M Y H:i'),
                        ]);
                    })
                    ->modalSubmitActionLabel(false)
                    ->modalCancelActionLabel('Tutup')
                    ->visible(function ($record) {
                        if (!$record) return false;
                        return $record->getFirstMedia('payment_proof') !== null;
                    })
                    ->modalWidth('4xl')
                    ->slideOver(),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->required(),
                    ])
                    ->action(function ($data, $record) {
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
                    ->visible(function ($record) { 
                        return $record->status === 'payment_pending_verification'; 
                    })
                    ->color('success'),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($record, $data) {
                        $record->status = 'rejected';
                        $record->rejection_reason = $data['reason'];
                        $record->save();
                        
                        // Update status reservasi
                        if ($record->reservation) {
                            $record->reservation->status = 'payment_rejected';
                            $record->reservation->save();
                        }
                        
                        // Send notification
                        Notification::make()
                            ->title('Pembayaran Ditolak')
                            ->danger()
                            ->send();
                    })
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Penolakan')
                            ->required()
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('confirm')
                    ->label('Konfirmasi Pembayaran')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->status = 'confirmed';
                            $record->save();
                            
                            if ($record->reservation) {
                                $record->reservation->status = 'confirmed';
                                $record->reservation->save();
                            }
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran berhasil dikonfirmasi')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin mengonfirmasi pembayaran yang dipilih? Status akan diubah menjadi Dikonfirmasi.')
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->deselectRecordsAfterCompletion()
                    ->visible(function () {
                        return auth()->user()->can('update', \App\Models\Payment::class);
                    }),
                Tables\Actions\DeleteBulkAction::make(),
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

    /**
     * Format file size to human readable format
     */
    private static function formatFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
