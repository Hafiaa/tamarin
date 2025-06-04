<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;
    public $payment;
    public $dueDate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation, Payment $payment)
    {
        $this->reservation = $reservation;
        $this->payment = $payment;
        $this->dueDate = $payment->due_date->format('d F Y');
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
        $amount = number_format($this->payment->amount, 0, ',', '.');
        $total = number_format($this->reservation->total_price, 0, ',', '.');
        $dueDate = $this->payment->due_date->format('d F Y');
        
        return (new MailMessage)
            ->subject("Reservasi {$eventType} Anda Disetujui - Mohon Lakukan Pembayaran")
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line("Kami senang menginformasikan bahwa reservasi {$eventType} Anda telah disetujui oleh admin.")
            ->line('')
            ->line('**Detail Reservasi**')
            ->line("Jenis Acara: {$eventType}")
            ->line("Tanggal: {$this->reservation->event_date->format('d F Y')}")
            ->line("Waktu: {$this->reservation->event_time} - {$this->reservation->end_time}")
            ->line("Jumlah Tamu: {$this->reservation->guest_count} orang")
            ->line('')
            ->line('**Pembayaran**')
            ->line("Total Biaya: Rp {$total}")
            ->line("Jumlah yang Harus Dibayar: Rp {$amount} ({$this->payment->notes})")
            ->line("Batas Waktu Pembayaran: {$dueDate}")
            ->line('')
            ->line('Silakan lakukan pembayaran ke rekening berikut:')
            ->line('Bank: BCA')
            ->line('Nomor Rekening: 1234567890')
            ->line('Atas Nama: Nama Perusahaan')
            ->line('')
            ->action('Lihat Detail Pembayaran', route('customer.dashboard.payments'))
            ->line('Terima kasih telah mempercayakan acara Anda kepada kami!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reservasi Disetujui',
            'message' => "Reservasi {$this->reservation->eventType->name} Anda telah disetujui. Segera lakukan pembayaran sebelum {$this->dueDate}.",
            'url' => route('customer.dashboard.payments'),
            'type' => 'reservation_approved'
        ];
    }
}
