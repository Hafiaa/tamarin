<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $request, $reservationId)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:bank_transfer,credit_card,cash',
            'payment_date' => 'required|date',
            'payment_proof' => 'required|image|max:2048',
            'notes' => 'nullable|string',
        ]);
        
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
        
        if ($validated['amount'] > $remainingAmount) {
            return back()->withErrors(['amount' => 'The payment amount cannot exceed the remaining balance of ' . $remainingAmount])->withInput();
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
            
            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $payment->addMediaFromRequest('payment_proof')
                    ->toMediaCollection('payment_proof');
            }
            
            DB::commit();
            
            return redirect()->route('customer.dashboard.payments')
                ->with('success', 'Your payment has been submitted and is pending approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while processing your payment. Please try again.'])->withInput();
        }
    }
}
