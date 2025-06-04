<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentExpired extends Notification implements ShouldQueue
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
        
        return (new MailMessage)
            ->subject("Pembayaran Dibatalkan - {$eventType}")
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kami menyesal menginformasikan bahwa reservasi Anda telah dibatalkan karena melewati batas waktu pembayaran.')
            ->line('')
            ->line('**Detail Reservasi yang Dibatalkan**')
            ->line("Jenis Acara: {$eventType}")
            ->line("Tanggal Acara: {$eventDate}")
            ->line("Waktu: {$this->reservation->event_time} - {$this->reservation->end_time}")
            ->line('')
            ->line('Jika Anda masih tertarik untuk memesan, silakan buat reservasi baru melalui website kami.')
            ->line('')
            ->action('Buat Reservasi Baru', route('home'))
            ->line('Terima kasih atas pengertiannya.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran Dibatalkan',
            'message' => 'Reservasi Anda telah dibatalkan karena melewati batas waktu pembayaran.',
            'url' => route('home'),
            'type' => 'payment_expired',
            'reservation_id' => $this->reservation->id
        ];
    }
}
