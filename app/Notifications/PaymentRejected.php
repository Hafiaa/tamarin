<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PaymentRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    public $rejectionReason;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Payment  $payment
     * @param  string|null  $rejectionReason
     * @return void
     */
    public function __construct(Payment $payment, ?string $rejectionReason = null)
    {
        $this->payment = $payment;
        $this->rejectionReason = $rejectionReason ?? 'Alasan penolakan tidak disertakan.';
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
        $paymentCode = Str::upper(Str::random(8));
        
        $mail = (new MailMessage)
            ->subject('Pembayaran Ditolak - ' . $reservation->code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kami ingin memberitahukan bahwa bukti pembayaran Anda untuk reservasi berikut telah ditolak:')
            ->line('')
            ->line('**Detail Reservasi**')
            ->line("Kode Reservasi: {$reservation->code}")
            ->line("Jenis Acara: {$reservation->eventType->name}")
            ->line("Tanggal Acara: {$reservation->event_date->format('d F Y')}")
            ->line("Waktu: {$reservation->event_time} - {$reservation->end_time}")
            ->line('')
            ->line('**Detail Pembayaran**')
            ->line("Jumlah Pembayaran: Rp {$amount}")
            ->line("Kode Referensi: {$paymentCode}")
            ->line('')
            ->line('**Alasan Penolakan**')
            ->line($this->rejectionReason)
            ->line('')
            ->line('**Langkah Selanjutnya**')
            ->line('1. Periksa kembali bukti pembayaran yang Anda unggah')
            ->line('2. Pastikan bukti pembayaran jelas terbaca')
            ->line('3. Pastikan jumlah pembayaran sesuai')
            ->line('4. Unggah ulang bukti pembayaran yang benar melalui tautan di bawah')
            ->line('')
            ->action('Unggah Ulang Bukti Pembayaran', route('customer.dashboard.payments.upload', $this->payment->id))
            ->line('')
            ->line('Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi tim dukungan kami.')
            ->line('')
            ->line('Terima kasih atas pengertian dan kerjasamanya.');
            
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $reservation = $this->payment->reservation;
        
        return [
            'title' => 'Pembayaran Ditolak',
            'message' => "Bukti pembayaran untuk reservasi #{$reservation->code} ditolak. " . 
                        ($this->rejectionReason ? "Alasan: {$this->rejectionReason}" : ''),
            'url' => route('customer.dashboard.payments.upload', $this->payment->id),
            'type' => 'payment_rejected',
            'payment_id' => $this->payment->id,
            'reservation_id' => $reservation->id,
            'rejection_reason' => $this->rejectionReason
        ];
    }
}
