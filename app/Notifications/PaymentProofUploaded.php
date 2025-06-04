<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentProofUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reservation = $this->payment->reservation;
        $amount = number_format($this->payment->amount, 0, ',', '.');
        $total = number_format($reservation->total_price, 0, ',', '.');
        
        return (new MailMessage)
            ->subject('Bukti Pembayaran Baru - ' . $reservation->code)
            ->greeting('Halo Admin,')
            ->line('Sebuah bukti pembayaran baru telah diunggah dan memerlukan verifikasi.')
            ->line('')
            ->line('**Detail Pembayaran**')
            ->line("Kode Reservasi: {$reservation->code}")
            ->line("Nama Pelanggan: {$reservation->user->name}")
            ->line("Jenis Acara: {$reservation->eventType->name}")
            ->line("Tanggal Acara: {$reservation->event_date->format('d F Y')}")
            ->line("Jumlah Pembayaran: Rp {$amount}")
            ->line("Total Tagihan: Rp {$total}")
            ->line('')
            ->action('Verifikasi Pembayaran', route('filament.admin.resources.payments.edit', $this->payment->id))
            ->line('Segera verifikasi pembayaran ini untuk melanjutkan proses reservasi.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Bukti Pembayaran Baru',
            'message' => "Bukti pembayaran untuk reservasi #{$this->payment->reservation->code} telah diunggah dan menunggu verifikasi.",
            'url' => route('filament.admin.resources.payments.edit', $this->payment->id),
            'type' => 'payment_proof_uploaded',
            'payment_id' => $this->payment->id,
            'reservation_id' => $this->payment->reservation_id
        ];
    }
}
