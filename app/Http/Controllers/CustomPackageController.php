<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ServiceItem;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomPackageController extends Controller
{
    /**
     * Show the wizard step 1 - Event Details
     */
    public function step1(Request $request)
    {
        $eventTypes = EventType::where('is_active', true)->get();
        
        // If coming from next step, get the old input
        $reservation = $request->old() ? new Reservation($request->old()) : null;
        
        $currentStep = 1;
        
        return view('custom-package.step1', compact('eventTypes', 'reservation', 'currentStep'));
    }
    
    /**
     * Process step 1 and go to step 2
     */
    public function processStep1(Request $request)
    {
        $validated = $request->validate([
            'event_type_id' => 'required|exists:event_types,id',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1',
            'bride_name' => 'nullable|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'special_requests' => 'nullable|string',
        ]);
        
        return redirect()->route('custom-package.step2')
            ->withInput();
    }
    
    /**
     * Show the wizard step 2 - Service Selection
     */
    public function step2(Request $request)
    {
        if (!$request->old()) {
            return redirect()->route('custom-package.step1');
        }
        
        $serviceItems = ServiceItem::active()
            ->with('media')
            ->get()
            ->groupBy('type');
            
        $selectedServices = collect($request->old('services', []))->mapWithKeys(function ($item) {
            return [$item['service_item_id'] => $item['quantity']];
        });
        
        $currentStep = 2;
        
        return view('custom-package.step2', compact('serviceItems', 'selectedServices', 'currentStep'));
    }
    
    /**
     * Process step 2 and go to step 3
     */
    public function processStep2(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*.service_item_id' => 'required|exists:service_items,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.notes' => 'nullable|string',
        ]);
        
        return redirect()->route('custom-package.step3')
            ->withInput();
    }
    
    /**
     * Show the wizard step 3 - Budget & Review
     */
    public function step3(Request $request)
    {
        if (!$request->old()) {
            return redirect()->route('custom-package.step1');
        }
        
        $serviceItems = ServiceItem::whereIn('id', collect($request->old('services'))->pluck('service_item_id'))
            ->get()
            ->keyBy('id');
            
        $services = collect($request->old('services'))->map(function ($item) use ($serviceItems) {
            $service = $serviceItems->get($item['service_item_id']);
            $quantity = $item['quantity'];
            $unitPrice = $service->base_price;
            $total = $unitPrice * $quantity;
            
            return [
                'service_item' => $service,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $total,
                'notes' => $item['notes'] ?? null,
            ];
        });
        
        $subtotal = $services->sum('total_price');
        
        $currentStep = 3;
        
        return view('custom-package.step3', compact('services', 'subtotal', 'currentStep'));
    }
    
    /**
     * Process step 3 and store the reservation
     */
    public function store(Request $request)
    {
        // Validate all the data
        $validated = $request->validate([
            // Step 1 fields
            'event_type_id' => 'required|exists:event_types,id',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1',
            'bride_name' => 'nullable|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'special_requests' => 'nullable|string',
            
            // Step 2 fields
            'services' => 'required|array|min:1',
            'services.*.service_item_id' => 'required|exists:service_items,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.notes' => 'nullable|string',
            
            // Step 3 fields
            'budget' => 'nullable|numeric|min:0',
            'reference_files' => 'nullable|array',
            'reference_files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'terms' => 'required|accepted',
        ]);
        
        // Start transaction
        return DB::transaction(function () use ($request) {
            // Create the reservation
            $reservation = new Reservation([
                'user_id' => auth()->id(),
                'event_type_id' => $request->event_type_id,
                'event_date' => $request->event_date,
                'event_time' => $request->event_time,
                'guest_count' => $request->guest_count,
                'bride_name' => $request->bride_name,
                'groom_name' => $request->groom_name,
                'special_requests' => $request->special_requests,
                'budget' => $request->budget,
                'status' => Reservation::STATUS_PENDING,
                'notes' => $request->notes,
            ]);
            
            $reservation->save();
            
            // Add custom package items
            $serviceItems = ServiceItem::whereIn('id', collect($request->services)->pluck('service_item_id'))->get()->keyBy('id');
            
            foreach ($request->services as $service) {
                $serviceItem = $serviceItems->get($service['service_item_id']);
                
                $reservation->customPackageItems()->create([
                    'service_item_id' => $serviceItem->id,
                    'quantity' => $service['quantity'],
                    'unit_price' => $serviceItem->base_price,
                    'total_price' => $serviceItem->base_price * $service['quantity'],
                    'notes' => $service['notes'] ?? null,
                ]);
            }
            
            // Handle file uploads
            if ($request->hasFile('reference_files')) {
                $referenceFiles = [];
                
                foreach ($request->file('reference_files') as $file) {
                    $path = $file->store('reference-files/' . $reservation->id, 'public');
                    $referenceFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType(),
                    ];
                }
                
                $reservation->reference_files = $referenceFiles;
                $reservation->save();
            }
            
            // Calculate and save total price
            $reservation->total_price = $reservation->custom_package_total;
            $reservation->save();
            
            // TODO: Send notifications
            
            return redirect()->route('custom-package.thank-you', $reservation->id);
        });
    }
    
    /**
     * Show the thank you page
     */
    public function thankYou(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('custom-package.thank-you', compact('reservation'));
    }
}
