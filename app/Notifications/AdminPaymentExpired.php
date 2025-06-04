<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPaymentExpired extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
        $eventType = $this->reservation->eventType->name;
        $eventDate = $this->reservation->event_date->format('d F Y');
        $customerName = $this->reservation->user->name;
        
        return (new MailMessage)
            ->subject("Pembayaran Kadaluarsa - Reservasi {$eventType} Dibatalkan")
            ->greeting('Halo Admin,')
            ->line('Sebuah reservasi telah dibatalkan karena melewati batas waktu pembayaran.')
            ->line('')
            ->line('**Detail Reservasi yang Dibatalkan**')
            ->line("Nama Pelanggan: {$customerName}")
            ->line("Jenis Acara: {$eventType}")
            ->line("Tanggal Acara: {$eventDate}")
            ->line("Waktu: {$this->reservation->event_time} - {$this->reservation->end_time}")
            ->line('')
            ->line('Slot tanggal ini sekarang tersedia kembali untuk reservasi lain.')
            ->action('Lihat Kalender', route('filament.admin.resources.reservations.index'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran Kadaluarsa - Reservasi Dibatalkan',
            'message' => "Reservasi {$this->reservation->eventType->name} atas nama {$this->reservation->user->name} telah dibatalkan karena melewati batas waktu pembayaran.",
            'url' => route('filament.admin.resources.reservations.edit', $this->reservation->id),
            'type' => 'admin_payment_expired',
            'reservation_id' => $this->reservation->id
        ];
    }
}
