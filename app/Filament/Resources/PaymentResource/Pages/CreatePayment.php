<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Update status reservasi jika pembayaran berhasil
        if ($this->record->status === 'paid') {
            $reservation = $this->record->reservation;
            if ($reservation) {
                $reservation->update(['status' => 'confirmed']);
            }
        }
    }
}
