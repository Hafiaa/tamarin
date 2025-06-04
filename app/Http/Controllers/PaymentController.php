<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\PaymentProofUploaded;
use App\Notifications\PaymentRejected;
use App\Notifications\PaymentVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the form for creating a new payment.
     *
     * @param  int  $reservationId
     * @return \Illuminate\View\View
     */
    public function create($reservationId)
    {
        $user = Auth::user();
        
        $reservation = Reservation::where('user_id', $user->id)
            ->where('id', $reservationId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->firstOrFail();
            
        // Calculate remaining amount
        $paidAmount = $reservation->payments()
            ->where('status', 'approved')
            ->sum('amount');
            
        $remainingAmount = $reservation->total_price - $paidAmount;
        
        if ($remainingAmount <= 0) {
            return redirect()->route('customer.dashboard.reservations')
                ->with('info', 'This reservation has been fully paid.');
        }
        
        return view('payments.create', compact('reservation', 'remainingAmount'));
    }
    
    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Upload payment proof for a payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadProof(Request $request, $paymentId)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $user = Auth::user();
        
        // Find the payment with reservation and user check
        $payment = Payment::whereHas('reservation', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($paymentId);
        
        // Only allow uploading proof for pending payments
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot upload proof for a payment that is not pending.'
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                // Delete old payment proof if exists
                if ($payment->hasMedia('payment_proofs')) {
                    $payment->clearMediaCollection('payment_proofs');
                }
                
                // Add new payment proof
                $payment->addMediaFromRequest('payment_proof')
                    ->usingName(Str::slug('payment-proof-' . $payment->reservation->code . '-' . now()->format('YmdHis')))
                    ->toMediaCollection('payment_proofs');
                
                // Update payment status to under_review
                $payment->status = 'under_review';
                $payment->reviewed_at = null;
                $payment->reviewed_by = null;
                $payment->review_notes = null;
                $payment->save();
                
                // Notify admin about the new payment proof
                $admins = \App\Models\User::role('admin')->get();
                
                Notification::send($admins, new PaymentProofUploaded($payment));
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diunggah dan sedang dalam proses verifikasi.',
                    'payment' => $payment->load('reservation')
                ]);
            }
            
            throw new \Exception('Gagal mengunggah bukti pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error uploading payment proof: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.'
            ], 500);
        }
    }
    
    /**
     * Verify a payment (admin only)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, $paymentId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'required_if:status,rejected|nullable|string|max:500',
        ]);
        
        $admin = Auth::user();
        $status = $request->input('status');
        $notes = $request->input('notes');
        
        $payment = Payment::with('reservation.user')->findOrFail($paymentId);
        
        // Only allow verifying payments that are under review
        if ($payment->status !== 'under_review') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembayaran yang sedang dalam proses review yang dapat diverifikasi.'
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            $payment->status = $status;
            $payment->reviewed_at = now();
            $payment->reviewed_by = $admin->id;
            $payment->review_notes = $status === 'rejected' ? $notes : null;
            $payment->save();
            
            // If payment is approved, check if the reservation is fully paid
            if ($status === 'approved') {
                $reservation = $payment->reservation;
                $totalPaid = $reservation->payments()
                    ->where('status', 'approved')
                    ->sum('amount');
                
                // If fully paid, update reservation status
                if ($totalPaid >= $reservation->total_price) {
                    $reservation->status = 'confirmed';
                    $reservation->save();
                }
                
                // Notify user about approved payment
                $payment->reservation->user->notify(new PaymentVerified($payment));
            } else {
                // Notify user about rejected payment
                $payment->reservation->user->notify(new PaymentRejected($payment, $notes));
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi.',
                'payment' => $payment->load('reservation', 'reviewer')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error verifying payment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memverifikasi pembayaran. Silakan coba lagi.'
            ], 500);
        }
    }
    
    public function store(Request $request, $reservationId)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:bank_transfer,credit_card,cash',
            'payment_date' => 'required|date',
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $reservation = Reservation::where('user_id', $user->id)
            ->where('id', $reservationId)
            ->whereIn('status', ['awaiting_payment', 'pending', 'confirmed'])
            ->firstOrFail();
            
        // Calculate remaining amount
        $paidAmount = $reservation->payments()
            ->where('status', 'approved')
            ->sum('amount');
            
        $remainingAmount = $reservation->total_price - $paidAmount;
        
        if ($remainingAmount <= 0) {
            return redirect()->route('customer.dashboard.reservations')
                ->with('info', 'Reservasi ini sudah lunas.');
        }
        
        if ($validated['amount'] > $remainingAmount) {
            return back()->withErrors(['amount' => 'Jumlah pembayaran tidak boleh melebihi sisa tagihan sebesar Rp ' . number_format($remainingAmount, 0, ',', '.')])->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $payment = new Payment();
            $payment->reservation_id = $reservation->id;
            $payment->amount = $validated['amount'];
            $payment->payment_method = $validated['payment_method'];
            $payment->payment_date = $validated['payment_date'];
            $payment->notes = $validated['notes'] ?? null;
            $payment->status = 'pending';
            $payment->save();
            
            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $payment->addMediaFromRequest('payment_proof')
                    ->usingName(Str::slug('payment-proof-' . $reservation->code . '-' . now()->format('YmdHis')))
                    ->toMediaCollection('payment_proofs');
            }
            
            // Update payment status to under_review
            $payment->status = 'under_review';
            $payment->save();
            
            // Notify admin about the new payment
            $admins = \App\Models\User::role('admin')->get();
            Notification::send($admins, new PaymentProofUploaded($payment));
            
            // Update reservation status if this is the first payment
            if ($reservation->status === 'awaiting_payment') {
                $reservation->status = 'pending';
                $reservation->save();
            }
            
            DB::commit();
            
            return redirect()->route('customer.dashboard.payments.show', $payment->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang dalam proses verifikasi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error processing payment: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.')
                ->withInput();
        }
    }
}
