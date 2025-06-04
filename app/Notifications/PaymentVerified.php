<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerified extends Notification implements ShouldQueue
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
        $totalPaid = $reservation->payments()
            ->where('status', 'approved')
            ->sum('amount');
        $totalPaidFormatted = number_format($totalPaid, 0, ',', '.');
        $remainingAmount = $reservation->total_price - $totalPaid;
        
        $mail = (new MailMessage)
            ->subject('Pembayaran Diverifikasi - ' . $reservation->code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pembayaran Anda untuk reservasi berikut telah berhasil diverifikasi:')
            ->line('')
            ->line('**Detail Reservasi**')
            ->line("Kode Reservasi: {$reservation->code}")
            ->line("Jenis Acara: {$reservation->eventType->name}")
            ->line("Tanggal Acara: {$reservation->event_date->format('d F Y')}")
            ->line("Waktu: {$reservation->event_time} - {$reservation->end_time}")
            ->line('')
            ->line('**Detail Pembayaran**')
            ->line("Jumlah yang Diverifikasi: Rp {$amount}")
            ->line("Total yang Sudah Dibayar: Rp {$totalPaidFormatted}");
            
        // Check if payment is complete
        if ($remainingAmount <= 0) {
            $mail->line('')
                ->line('✅ **Pembayaran telah lunas!**')
                ->line('Reservasi Anda sekarang telah dikonfirmasi. Kami akan segera menghubungi Anda untuk konfirmasi lebih lanjut.');
        } else {
            $remainingFormatted = number_format($remainingAmount, 0, ',', '.');
            $mail->line("Sisa Pembayaran: Rp {$remainingFormatted}")
                ->line('')
                ->line('Silakan lanjutkan dengan pembayaran selanjutnya sesuai jadwal yang telah ditentukan.');
        }
        
        $mail->line('')
            ->action('Lihat Detail Reservasi', route('customer.dashboard.reservations.show', $reservation->id))
            ->line('Terima kasih telah menggunakan layanan kami!');
            
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $reservation = $this->payment->reservation;
        $totalPaid = $reservation->payments()
            ->where('status', 'approved')
            ->sum('amount');
        $isFullyPaid = ($totalPaid >= $reservation->total_price);
        
        return [
            'title' => 'Pembayaran Diverifikasi',
            'message' => $isFullyPaid 
                ? "Pembayaran untuk reservasi #{$reservation->code} telah diverifikasi dan lunas."
                : "Pembayaran sebesar Rp " . number_format($this->payment->amount, 0, ',', '.') . " untuk reservasi #{$reservation->code} telah diverifikasi.",
            'url' => route('customer.dashboard.reservations.show', $reservation->id),
            'type' => 'payment_verified',
            'payment_id' => $this->payment->id,
            'reservation_id' => $reservation->id,
            'is_fully_paid' => $isFullyPaid
        ];
    }
}
