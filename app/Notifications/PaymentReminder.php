<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PaymentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;
    public $payment;
    public $daysLeft;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation, Payment $payment)
    {
        $this->reservation = $reservation;
        $this->payment = $payment;
        $this->daysLeft = Carbon::now()->diffInDays($payment->due_date, false);
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
        $dueDate = $this->payment->due_date->format('d F Y');
        
        $message = (new MailMessage)
            ->subject("Pengingat Pembayaran - {$eventType}")
            ->greeting('Halo ' . $notifiable->name . ',');
            
        if ($this->daysLeft > 0) {
            $message->line("Ini adalah pengingat bahwa Anda memiliki pembayaran yang akan jatuh tempo dalam {$this->daysLeft} hari.");
        } elseif ($this->daysLeft == 0) {
            $message->line('Ini adalah pengingat bahwa pembayaran Anda jatuh tempo hari ini.');
        } else {
            $message->line('Pembayaran Anda sudah melewati batas waktu. Segera lakukan pembayaran untuk menghindari pembatalan reservasi.');
        }
        
        $message->line('')
            ->line('**Detail Pembayaran**')
            ->line("Jenis Acara: {$eventType}")
            ->line("Tanggal Acara: {$this->reservation->event_date->format('d F Y')}")
            ->line("Jumlah yang Harus Dibayar: Rp {$amount} ({$this->payment->notes})")
            ->line("Batas Waktu Pembayaran: {$dueDate}")
            ->line('')
            ->line('Silakan lakukan pembayaran ke rekening berikut:')
            ->line('Bank: BCA')
            ->line('Nomor Rekening: 1234567890')
            ->line('Atas Nama: Nama Perusahaan')
            ->line('')
            ->action('Lihat Detail Pembayaran', route('customer.dashboard.payments'))
            ->line('Terima kasih atas kerjasamanya!');
            
        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $message = $this->daysLeft > 0 
            ? "Pembayaran jatuh tempo dalam {$this->daysLeft} hari"
            : ($this->daysLeft == 0 
                ? 'Pembayaran jatuh tempo hari ini!' 
                : 'Pembayaran sudah melewati batas waktu!');
                
        return [
            'title' => 'Pengingat Pembayaran',
            'message' => $message,
            'url' => route('customer.dashboard.payments'),
            'type' => 'payment_reminder',
            'reservation_id' => $this->reservation->id
        ];
    }
}
