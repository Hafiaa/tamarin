<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use App\Models\CustomPackage;
use App\Models\EventType;
use App\Models\PackageTemplate;
use App\Models\Reservation;
use App\Models\ServiceItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReservationController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['startReservation', 'create', 'checkAvailability', 'getBlockedDates']);
    }
    
    /**
     * Get all blocked dates for the date picker
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBlockedDates()
    {
        try {
            // Log that the method was called
            \Log::info('Fetching blocked dates...');
            
            // Get all blocked dates that are not expired
            $blockedDates = BlockedDate::where(function($query) {
                $query->where('date', '>=', now()->toDateString())
                      ->orWhere('is_recurring_yearly', true);
            })->get()
            ->map(function($blockedDate) {
                if ($blockedDate->is_recurring_yearly) {
                    // For recurring dates, we need to check the next 2 years
                    $currentYear = now()->year;
                    $dates = [];
                    
                    for ($i = 0; $i < 2; $i++) {
                        try {
                            $date = Carbon::create($currentYear + $i, $blockedDate->date->month, $blockedDate->date->day);
                            if ($date >= now()->startOfDay()) {
                                $dates[] = $date->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error processing recurring date:', [
                                'blocked_date_id' => $blockedDate->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                    
                    return $dates;
                }
                
                return $blockedDate->date->format('Y-m-d');
            })
            ->flatten()
            ->filter() // Remove any null/empty values
            ->unique()
            ->sort()
            ->values()
            ->toArray();
            
            \Log::info('Blocked dates found:', $blockedDates);
            
            return Response::json([
                'success' => true,
                'dates' => $blockedDates
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getBlockedDates:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Response::json([
                'success' => false,
                'message' => 'Failed to load blocked dates',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Start the reservation process (public entry point)
     * Redirects to login if not authenticated, otherwise to reservation form
     *
     * @param  int  $packageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startReservation($packageId = null)
    {
        // If user is not authenticated, redirect to login with intended URL
        if (!auth()->check()) {
            return redirect()->route('login', [
                'intended' => route('reservations.create', ['packageId' => $packageId])
            ]);
        }
        
        // If authenticated, redirect to the reservation form
        return redirect()->route('reservations.create', ['packageId' => $packageId]);
    }
    
    /**
     * Show the form for creating a new reservation.
     *
     * @param  int  $packageId
     * @return \Illuminate\View\View
     */
    public function create($packageId = null)
    {
        $package = null;
        $eventTypes = EventType::where('is_active', true)->get();
        
        if ($packageId) {
            $package = PackageTemplate::where('is_active', true)
                ->with(['eventType', 'serviceItems'])
                ->findOrFail($packageId);
        }
        
        return view('reservations.create', compact('package', 'eventTypes'));
    }
    
    /**
     * Check date availability via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');
        
        if (!$date) {
            return response()->json(['available' => false, 'message' => 'Date is required']);
        }
        
        $dateObj = Carbon::parse($date);
        
        // Check if date is in the past
        if ($dateObj->isPast()) {
            return response()->json(['available' => false, 'message' => 'Cannot book dates in the past']);
        }
        
        // Check if date is blocked
        $isBlocked = BlockedDate::isBlocked($dateObj)->exists();
        if ($isBlocked) {
            return response()->json(['available' => false, 'message' => 'This date is not available for booking']);
        }
        
        // Check if there's already a reservation for this date
        $hasReservation = Reservation::where('event_date', $dateObj->toDateString())
            ->where('status', '!=', 'cancelled')
            ->exists();
            
        if ($hasReservation) {
            return response()->json(['available' => false, 'message' => 'This date is already booked']);
        }
        
        return response()->json(['available' => true, 'message' => 'Date is available for booking']);
    }
    
    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_type_id' => 'required|exists:event_types,id',
            'package_template_id' => 'nullable|exists:package_templates,id',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required',
            'end_time' => 'required|after:event_time',
            'guest_count' => 'required|integer|min:1',
            'bride_name' => 'nullable|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'special_requests' => 'nullable|string',
            'custom_package' => 'nullable|array',
        ]);
        
        // Check date availability
        $dateObj = Carbon::parse($validated['event_date']);
        $isBlocked = BlockedDate::isDateBlocked($dateObj);
        $hasReservation = Reservation::where('event_date', $dateObj->toDateString())
            ->where('status', '!=', 'cancelled')
            ->exists();
            
        if ($isBlocked || $hasReservation) {
            return back()->withErrors(['event_date' => 'This date is not available for booking'])->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Create the reservation
            $reservation = new Reservation();
            $reservation->user_id = Auth::id();
            $reservation->event_type_id = $validated['event_type_id'];
            $reservation->package_template_id = $validated['package_template_id'] ?? null;
            $reservation->event_date = $validated['event_date'];
            $reservation->event_time = $validated['event_time'];
            $reservation->end_time = $validated['end_time'];
            $reservation->guest_count = $validated['guest_count'];
            $reservation->bride_name = $validated['bride_name'] ?? null;
            $reservation->groom_name = $validated['groom_name'] ?? null;
            $reservation->special_requests = $validated['special_requests'] ?? null;
            $reservation->status = Reservation::STATUS_PENDING;
            
            // Calculate total price based on package or custom items
            if ($reservation->package_template_id) {
                $package = PackageTemplate::findOrFail($reservation->package_template_id);
                $reservation->total_price = $package->base_price;
            } else if (isset($validated['custom_package'])) {
                // Create custom package
                $customPackage = new CustomPackage();
                $customPackage->items = $validated['custom_package'];
                
                // Calculate total price from custom items
                $totalPrice = 0;
                foreach ($validated['custom_package'] as $itemId => $quantity) {
                    $serviceItem = ServiceItem::findOrFail($itemId);
                    $totalPrice += $serviceItem->price * $quantity;
                }
                
                $reservation->total_price = $totalPrice;
                $reservation->save();
                
                $customPackage->reservation_id = $reservation->id;
                $customPackage->save();
            }
            
            $reservation->save();
            
            DB::commit();
            
            return redirect()->route('customer.dashboard.reservations')
                ->with('success', 'Your reservation has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memproses reservasi. Silakan coba lagi. Error: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
