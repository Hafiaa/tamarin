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
            
        // Initialize selected services array
        $selectedServices = [];
        
        // Process old input if it exists
        if ($request->old('services')) {
            foreach ($request->old('services') as $service) {
                if (isset($service['service_item_id'])) {
                    $selectedServices[$service['service_item_id']] = $service['quantity'] ?? 1;
                }
            }
        }
        
        $currentStep = 2;
        
        return view('custom-package.step2', [
            'serviceItems' => $serviceItems,
            'selectedServices' => $selectedServices,
            'currentStep' => $currentStep
        ]);
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
        ], [
            'services.required' => 'Pilih setidaknya satu layanan',
            'services.min' => 'Pilih setidaknya satu layanan',
            'services.*.service_item_id.required' => 'ID layanan tidak valid',
            'services.*.service_item_id.exists' => 'Layanan yang dipilih tidak valid',
            'services.*.quantity.required' => 'Jumlah harus diisi',
            'services.*.quantity.min' => 'Jumlah minimal 1',
        ]);

        // Ensure we have the service items data for the review page
        $serviceItems = ServiceItem::whereIn('id', collect($validated['services'])->pluck('service_item_id'))
            ->get()
            ->keyBy('id');

        // Store the services data in the session for the review page
        $servicesData = [];
        foreach ($validated['services'] as $service) {
            $serviceItem = $serviceItems->get($service['service_item_id']);
            if ($serviceItem) {
                $servicesData[] = [
                    'service_item_id' => $serviceItem->id,
                    'name' => $serviceItem->name,
                    'description' => $serviceItem->description,
                    'price' => $serviceItem->base_price,
                    'quantity' => $service['quantity'],
                    'notes' => $service['notes'] ?? null,
                    'total' => $serviceItem->base_price * $service['quantity']
                ];
            }
        }

        // Store in session for the review page
        $request->session()->put('custom_package.services', $servicesData);
        
        return redirect()->route('custom-package.step3')
            ->withInput();
    }
    
    /**
     * Show the wizard step 3 - Budget & Review
     */
    public function step3(Request $request)
    {
        // Redirect to step 1 if no session data
        if (!$request->session()->has('_old_input')) {
            return redirect()->route('custom-package.step1')
                ->with('error', 'Silakan isi detail acara terlebih dahulu');
        }
        
        // Get event type ID from session
        $eventTypeId = $request->old('event_type_id');
        if (!$eventTypeId) {
            return redirect()->route('custom-package.step1')
                ->with('error', 'Tipe acara tidak valid');
        }
        
        // Get event type with translations
        $eventType = EventType::with(['translations' => function($query) {
            $query->where('locale', app()->getLocale())
                  ->orWhere('locale', 'id')
                  ->orderByRaw("FIELD(locale, '".app()->getLocale()."', 'id')");
        }])->find($eventTypeId);
        
        if (!$eventType) {
            return redirect()->route('custom-package.step1')
                ->with('error', 'Tipe acara tidak ditemukan');
        }
        
        // Get services data from session
        $services = $request->session()->get('custom_package.services', []);
        
        if (empty($services)) {
            return redirect()->route('custom-package.step2')
                ->with('error', 'Silakan pilih setidaknya satu layanan')
                ->withInput();
        }
        
        // Get service items for additional data
        $serviceItems = ServiceItem::whereIn('id', collect($services)->pluck('service_item_id'))
            ->get()
            ->keyBy('id');
        
        // Enrich services data with additional information
        $enrichedServices = [];
        $totalPrice = 0;
        
        foreach ($services as $service) {
            $serviceItem = $serviceItems->get($service['service_item_id']);
            if ($serviceItem) {
                $serviceTotal = $serviceItem->base_price * $service['quantity'];
                $totalPrice += $serviceTotal;
                
                $enrichedServices[] = [
                    'id' => $serviceItem->id,
                    'name' => $serviceItem->name,
                    'description' => $serviceItem->description,
                    'price' => $serviceItem->base_price,
                    'quantity' => $service['quantity'],
                    'notes' => $service['notes'] ?? null,
                    'total' => $serviceTotal,
                    'image' => $serviceItem->image ? asset('storage/' . $serviceItem->image) : null
                ];
            }
        }
        
        // Store the enriched services in session for the store method
        $request->session()->put('custom_package.enriched_services', $enrichedServices);
        
        return view('custom-package.step3', [
            'currentStep' => 3,
            'eventType' => $eventType,
            'services' => $enrichedServices,
            'totalPrice' => $totalPrice,
            'budget' => $request->old('budget'),
            'reference_files' => $request->old('reference_files'),
            'terms' => $request->old('terms', false)
        ]);
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
            
            // Step 3 fields
            'budget' => 'nullable|numeric|min:0',
            'reference_files' => 'nullable|array|max:5',
            'reference_files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'terms' => 'required|accepted',
        ], [
            'event_type_id.required' => 'Silakan pilih tipe acara',
            'event_date.required' => 'Tanggal acara harus diisi',
            'event_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu',
            'event_time.required' => 'Waktu acara harus diisi',
            'guest_count.required' => 'Jumlah tamu harus diisi',
            'guest_count.min' => 'Jumlah tamu minimal 1',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
            'reference_files.max' => 'Maksimal 5 file yang diizinkan',
            'reference_files.*.mimes' => 'Format file yang diizinkan: jpg, jpeg, png, pdf, doc, docx',
            'reference_files.*.max' => 'Ukuran file maksimal 5MB',
        ]);
        
        // Get services from session instead of request
        $services = $request->session()->get('custom_package.enriched_services', []);
        
        if (empty($services)) {
            return redirect()->route('custom-package.step2')
                ->with('error', 'Silakan pilih setidaknya satu layanan')
                ->withInput();
        }
        
        // Start transaction
        try {
            return DB::transaction(function () use ($request, $services) {
                // Get the event type
                $eventType = EventType::findOrFail($request->event_type_id);
                
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
                    'budget' => $request->budget ?? 0,
                    'status' => Reservation::STATUS_PENDING,
                    'notes' => $request->notes ?? null,
                    'total_price' => 0, // Will be calculated below
                ]);
                
                $reservation->save();
                
                // Add custom package items
                $totalPrice = 0;
                
                foreach ($services as $service) {
                    $itemTotal = $service['price'] * $service['quantity'];
                    $totalPrice += $itemTotal;
                    
                    $reservation->customPackageItems()->create([
                        'service_item_id' => $service['id'],
                        'quantity' => $service['quantity'],
                        'unit_price' => $service['price'],
                        'total_price' => $itemTotal,
                        'notes' => $service['notes'] ?? null,
                    ]);
                }
                
                // Handle file uploads
                $referenceFiles = [];
                if ($request->hasFile('reference_files')) {
                    foreach ($request->file('reference_files') as $file) {
                        try {
                            $path = $file->store('reference-files/' . $reservation->id, 'public');
                            $referenceFiles[] = [
                                'name' => $file->getClientOriginalName(),
                                'path' => $path,
                                'size' => $file->getSize(),
                                'mime' => $file->getMimeType(),
                                'uploaded_at' => now()->toDateTimeString(),
                            ];
                        } catch (\Exception $e) {
                            \Log::error('Error uploading file: ' . $e->getMessage());
                            // Continue with other files if one fails
                            continue;
                        }
                    }
                }
                
                // Update reservation with total price and reference files
                $reservation->total_price = $totalPrice;
                if (!empty($referenceFiles)) {
                    $reservation->reference_files = $referenceFiles;
                }
                $reservation->save();
                
                // Clear the session data
                $request->session()->forget([
                    'custom_package.services',
                    'custom_package.enriched_services',
                    '_old_input'
                ]);
                
                // TODO: Send notifications
                
                return redirect()->route('custom-package.thank-you', $reservation->id);
            });
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating reservation: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memproses pemesanan. Silakan coba lagi.']);
        }
    }
    
    /**
     * Show the thank you page after successful submission
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\View\View
     */
    public function thankYou(Reservation $reservation)
    {
        // Ensure the authenticated user can only view their own reservation
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load the necessary relationships
        $reservation->load([
            'eventType',
            'customPackageItems.serviceItem',
            'user'
        ]);
        
        // Calculate total price if not already set
        if (!$reservation->total_price) {
            $reservation->total_price = $reservation->custom_package_total;
            $reservation->save();
        }
        
        return view('custom-package.thank-you', compact('reservation'));
    }
}
