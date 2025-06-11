<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\ReservationRevision;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerDashboardController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the customer dashboard overview.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        $upcomingReservations = Reservation::where('user_id', $user->id)
            ->where('event_date', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date')
            ->take(3)
            ->get();
            
        $pendingPayments = Payment::whereHas('reservation', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', '!=', 'cancelled');
        })
        ->where('status', 'pending')
        ->count();
        
        return view('customer.dashboard', compact('user', 'upcomingReservations', 'pendingPayments'));
    }
    
    /**
     * Display the customer's reservations.
     *
     * @return \Illuminate\View\View
     */
    public function reservations()
    {
        $user = Auth::user();
        
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['eventType', 'payments', 'customPackageItems.serviceItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.reservations', compact('reservations'));
    }
    
    /**
     * Display a specific reservation.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showReservation($id)
    {
        $reservation = Reservation::with([
                'eventType', 
                'payments', 
                'revisions.user',
                'customPackageItems.serviceItem',
                'packageTemplate.serviceItems' => function($query) {
                    $query->withPivot('quantity');
                }
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        $revisions = $reservation->revisions()->with('user')->latest()->get();
            
        return view('customer.reservation-detail', compact('reservation', 'revisions'));
    }
    
    /**
     * Cancel a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelReservation(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->whereIn('status', ['pending_admin_review', 'confirmed', 'pending_payment'])
            ->findOrFail($id);
            
        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update reservation status
            $reservation->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now(),
            ]);
            
            // Add revision history
            $reservation->revisions()->create([
                'user_id' => Auth::id(),
                'title' => 'Reservation Cancelled',
                'description' => 'Reservation was cancelled by customer. Reason: ' . $request->cancellation_reason,
            ]);
            
            // TODO: Send notification to admin and customer
            
            DB::commit();
            
            return redirect()->route('customer.dashboard.reservations.show', $reservation->id)
                ->with('success', 'Reservation has been cancelled successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error cancelling reservation: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to cancel reservation. Please try again.');
        }
    }
    
    /**
     * Display the customer's payments.
     *
     * @return \Illuminate\View\View
     */
    public function payments()
    {
        $user = Auth::user();
        
        $payments = Payment::whereHas('reservation', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('reservation')
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('customer.payments', compact('payments'));
    }
    
    /**
     * Display the form to submit a testimonial.
     *
     * @param  int  $reservationId
     * @return \Illuminate\View\View
     */
    public function createTestimonial($reservationId)
    {
        $user = Auth::user();
        
        $reservation = Reservation::where('user_id', $user->id)
            ->where('id', $reservationId)
            ->where('status', 'completed')
            ->firstOrFail();
            
        // Check if testimonial already exists
        $existingTestimonial = Testimonial::where('user_id', $user->id)
            ->where('reservation_id', $reservationId)
            ->first();
            
        if ($existingTestimonial) {
            return redirect()->route('customer.dashboard.testimonials')
                ->with('info', 'You have already submitted a testimonial for this reservation.');
        }
        
        return view('customer.create-testimonial', compact('reservation'));
    }
    
    /**
     * Store a new testimonial.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTestimonial(Request $request, $reservationId)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10',
        ]);
        
        $reservation = Reservation::where('user_id', $user->id)
            ->where('id', $reservationId)
            ->where('status', 'completed')
            ->firstOrFail();
            
        // Check if testimonial already exists
        $existingTestimonial = Testimonial::where('user_id', $user->id)
            ->where('reservation_id', $reservationId)
            ->first();
            
        if ($existingTestimonial) {
            return redirect()->route('customer.dashboard.testimonials')
                ->with('info', 'You have already submitted a testimonial for this reservation.');
        }
        
        $testimonial = new Testimonial();
        $testimonial->user_id = $user->id;
        $testimonial->reservation_id = $reservation->id;
        $testimonial->rating = $validated['rating'];
        $testimonial->content = $validated['content'];
        $testimonial->status = 'pending'; // Require admin approval
        $testimonial->save();
        
        return redirect()->route('customer.dashboard.testimonials')
            ->with('success', 'Your testimonial has been submitted and is pending approval.');
    }
    
    /**
     * Display the customer's testimonials.
     *
     * @return \Illuminate\View\View
     */
    public function testimonials()
    {
        $user = Auth::user();
        
        $testimonials = Testimonial::where('user_id', $user->id)
            ->with('reservation')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.testimonials', compact('testimonials'));
    }
    
    /**
     * Display the profile edit form.
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('customer.edit-profile', compact('user'));
    }
    
    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->address = $validated['address'] ?? $user->address;
        $user->save();
        
        return redirect()->route('customer.dashboard.profile.edit')
            ->with('success', 'Your profile has been updated successfully.');
    }
    
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        
        // Update the password
        $user->update([
            'password' => bcrypt($validated['password']),
        ]);
        
        return redirect()->route('customer.dashboard.profile.edit')
            ->with('success', 'Your password has been updated successfully.');
    }
}
