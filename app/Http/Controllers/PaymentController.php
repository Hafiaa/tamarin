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
     * Display the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Payment $payment)
    {
        try {
            $user = Auth::user();
            
            // Check if payment exists and belongs to the authenticated user
            if ($payment->reservation->user_id !== $user->id) {
                return redirect()->route('customer.dashboard.payments')
                    ->with('error', 'You are not authorized to view this payment.');
            }
            
            return view('customer.payments.show', compact('payment'));
            
        } catch (\Exception $e) {
            \Log::error('Error showing payment: ' . $e->getMessage());
            return redirect()->route('customer.dashboard.payments')
                ->with('error', 'An error occurred while retrieving the payment details.');
        }
    }
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
        try {
            $user = Auth::user();
            
            // First check if reservation exists
            $reservation = Reservation::find($reservationId);
            
            if (!$reservation) {
                return redirect()->route('customer.dashboard.reservations')
                    ->with('error', 'Reservation not found.');
            }
            
            // Check if reservation belongs to user
            if ($reservation->user_id !== $user->id) {
                return redirect()->route('customer.dashboard.reservations')
                    ->with('error', 'You are not authorized to view this reservation.');
            }
            
            // Check reservation status
            if (!in_array($reservation->status, ['pending', 'confirmed'])) {
                return redirect()->route('customer.dashboard.reservations.show', $reservation->id)
                    ->with('error', 'Payments can only be made for pending or confirmed reservations.');
            }
            
            // Calculate remaining amount
            $paidAmount = $reservation->payments()
                ->where('status', 'approved')
                ->sum('amount');
                
            $remainingAmount = $reservation->total_price - $paidAmount;
            
            if ($remainingAmount <= 0) {
                return redirect()->route('customer.dashboard.reservations.show', $reservation->id)
                    ->with('info', 'This reservation has been fully paid.');
            }
            
            return view('payments.create', compact('reservation', 'remainingAmount'));
            
        } catch (\Exception $e) {
            \Log::error('Error in PaymentController@create: ' . $e->getMessage());
            return redirect()->route('customer.dashboard.reservations')
                ->with('error', 'An error occurred while loading the payment page. Please try again.');
        }
    }
    
    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $reservationId)
    {
        \Log::info('Starting payment process', ['reservation_id' => $reservationId, 'user_id' => Auth::id()]);
        
        try {
            $user = Auth::user();
            \Log::debug('User authenticated', ['user_id' => $user->id, 'email' => $user->email]);
            
            // Log all request data except files
            $loggableRequest = $request->except(['payment_proof']);
            \Log::debug('Payment request data', $loggableRequest);
            
            // Validate the request
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|string|in:bca,bni,mandiri,e_wallet',
                'payment_date' => 'required|date',
                'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'notes' => 'nullable|string|max:500',
            ]);
            
            \Log::debug('Validation passed', $validated);
            
            // Find the reservation with detailed logging
            $reservation = Reservation::where('user_id', $user->id)
                ->where('id', $reservationId)
                ->whereIn('status', ['awaiting_payment', 'pending', 'confirmed'])
                ->first();
                
            if (!$reservation) {
                $status = Reservation::find($reservationId)->status ?? 'not_found';
                \Log::warning('Reservation not found or invalid status', [
                    'reservation_id' => $reservationId,
                    'user_id' => $user->id,
                    'status' => $status
                ]);
                return back()->with('error', 'Reservasi tidak ditemukan atau status tidak valid.');
            }
            
            \Log::debug('Reservation found', [
                'reservation_id' => $reservation->id,
                'status' => $reservation->status,
                'total_price' => $reservation->total_price
            ]);
                
            // Calculate remaining amount
            $paidAmount = $reservation->payments()
                ->where('status', 'approved')
                ->sum('amount');
                
            $remainingAmount = $reservation->total_price - $paidAmount;
            \Log::debug('Payment calculation', [
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'payment_amount' => $validated['amount']
            ]);
            
            if ($remainingAmount <= 0) {
                \Log::info('Reservation already paid', ['reservation_id' => $reservation->id]);
                return redirect()->route('customer.dashboard.reservations')
                    ->with('info', 'Reservasi ini sudah lunas.');
            }
            
            if ($validated['amount'] > $remainingAmount) {
                $errorMsg = 'Jumlah pembayaran tidak boleh melebihi sisa tagihan sebesar Rp ' . number_format($remainingAmount, 0, ',', '.');
                \Log::warning('Payment amount exceeds remaining amount', [
                    'amount' => $validated['amount'], 
                    'remaining' => $remainingAmount
                ]);
                return back()->withErrors(['amount' => $errorMsg])->withInput();
            }
            
            DB::beginTransaction();
            \Log::debug('Starting database transaction');
            
            try {
                // Prepare payment data
                $paymentData = [
                    'reservation_id' => $reservation->id,
                    'payment_type' => 'down_payment',
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'payment_date' => $validated['payment_date'],
                    'due_date' => now()->addDay(),
                    'status' => 'payment_pending_verification',
                    'notes' => $validated['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                \Log::debug('Creating payment record', $paymentData);
                
                // Create payment record
                $payment = new Payment($paymentData);
                
                if (!$payment->save()) {
                    $error = 'Failed to save payment record';
                    \Log::error($error, ['errors' => $payment->getErrors()]);
                    throw new \Exception($error);
                }
                
                \Log::info('Payment record created', [
                    'payment_id' => $payment->id,
                    'reservation_id' => $payment->reservation_id,
                    'amount' => $payment->amount
                ]);
                
                // Handle file upload
                if ($request->hasFile('payment_proof')) {
                    $file = $request->file('payment_proof');
                    \Log::debug('Processing payment proof upload', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'extension' => $file->getClientOriginalExtension()
                    ]);
                    
                    try {
                        // Check storage directory permissions
                        $storagePath = storage_path('app/public/payment_proofs');
                        if (!is_dir($storagePath)) {
                            \Log::info('Creating payment proof directory', ['path' => $storagePath]);
                            mkdir($storagePath, 0755, true);
                        }
                        
                        // Check if directory is writable
                        if (!is_writable($storagePath)) {
                            throw new \Exception('Direktori penyimpanan tidak dapat ditulisi. Silakan hubungi administrator.');
                        }
                        
                        // Upload file
                        $media = $payment->addMediaFromRequest('payment_proof')
                            ->usingName(Str::slug('payment-proof-' . $reservation->code . '-' . now()->format('YmdHis')))
                            ->toMediaCollection('payment_proof');
                            
                        if (!$media) {
                            throw new \Exception('Gagal mengunggah bukti pembayaran');
                        }
                        
                        \Log::info('Payment proof uploaded successfully', [
                            'media_id' => $media->id,
                            'file_name' => $media->file_name,
                            'disk' => $media->disk,
                            'path' => $media->getPath()
                        ]);
                    } catch (\Exception $e) {
                        throw $e;
                    }
                } else {
                    throw new \Exception('Bukti pembayaran tidak ditemukan');
                }
                
                // Notify admin about the new payment
                try {
                    $admins = \App\Models\User::role('admin')->get();
                    if ($admins->isNotEmpty()) {
                        \Log::debug('Sending notifications to admins', ['admin_count' => $admins->count()]);
                        Notification::send($admins, new PaymentProofUploaded($payment));
                        \Log::info('Admin notifications sent successfully');
                    }
                } catch (\Exception $e) {
                    // Don't fail the payment if notification fails
                    \Log::error('Error sending notification: ' . $e->getMessage());
                }
                
                // Update reservation status if this is the first payment
                if ($reservation->status === 'awaiting_payment') {
                    $reservation->status = 'pending';
                    if (!$reservation->save()) {
                        throw new \Exception('Gagal memperbarui status reservasi');
                    }
                    \Log::info('Reservation status updated to pending', ['reservation_id' => $reservation->id]);
                }
                
                DB::commit();
                \Log::info('Payment process completed successfully', ['payment_id' => $payment->id]);
                
                return redirect()->route('customer.dashboard.payments.show', $payment->id)
                    ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang dalam proses verifikasi.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Payment processing error: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                
                return back()
                    ->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage())
                    ->withInput($request->except('payment_proof', 'password'));
            }
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Let Laravel handle validation exceptions
            
        } catch (\Exception $e) {
            \Log::error('Unexpected error in payment process: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()
                ->with('error', 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi atau hubungi tim dukungan.')
                ->withInput($request->except('payment_proof', 'password'));
        }
    }
    
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
}
