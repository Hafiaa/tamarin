<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ServiceItem;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CustomPackageController extends Controller
{
    /**
     * Show the wizard step 1 - Event Details
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function step1(Request $request)
    {
        try {
            $eventTypes = EventType::where('is_active', true)
                ->orderBy('name')
                ->get();
            
            $reservation = $request->old() ? new Reservation($request->old()) : null;
            
            return view('custom-package.step1', [
                'eventTypes' => $eventTypes,
                'reservation' => $reservation,
                'currentStep' => 1,
                'progress' => 33
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in step1: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
    
    /**
     * Process step 1 and go to step 2
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processStep1(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'event_type_id' => 'required|exists:event_types,id',
                'event_date' => [
                    'required',
                    'date',
                    'after_or_equal:today',
                    function ($attribute, $value, $fail) {
                        if (strtotime($value) < strtotime('today')) {
                            $fail('Tanggal tidak boleh di masa lalu');
                        }
                    },
                ],
                'event_time' => 'required|date_format:H:i',
                'guest_count' => 'required|integer|min:1|max:1000',
                'bride_name' => 'nullable|string|max:255',
                'groom_name' => 'nullable|string|max:255',
                'special_requests' => 'nullable|string|max:1000',
                'decoration_theme' => 'nullable|string|max:255',
            ], [
                'event_type_id.required' => 'Pilih tipe acara',
                'event_date.required' => 'Tanggal acara harus diisi',
                'event_date.date' => 'Format tanggal tidak valid',
                'event_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu',
                'event_time.required' => 'Waktu acara harus diisi',
                'event_time.date_format' => 'Format waktu tidak valid',
                'guest_count.required' => 'Jumlah tamu harus diisi',
                'guest_count.integer' => 'Jumlah tamu harus berupa angka',
                'guest_count.min' => 'Jumlah tamu minimal 1',
                'guest_count.max' => 'Maksimal 1000 tamu',
            ]);
            
            // Simpan data ke session
            $request->session()->put('custom_package', [
                'event_details' => $validated
            ]);
            
            // Debug: Tampilkan isi session
            Log::info('Session after step1:', [
                'session_data' => $request->session()->get('custom_package')
            ]);
            
            return redirect()->route('custom-package.step2')
                ->with('success', 'Langkah 1 berhasil disimpan. Silakan pilih layanan yang diinginkan.');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error in processStep1: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput();
        }
    }
    
    /**
     * Show the wizard step 2 - Service Selection
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function step2(Request $request)
    {
        try {
            // Debug: Tampilkan isi session
            Log::info('Session in step2:', [
                'session_data' => $request->session()->all(),
                'custom_package' => $request->session()->get('custom_package')
            ]);

            // Redirect to step 1 if no previous input
            if (!$request->session()->has('custom_package.event_details')) {
                Log::warning('No event details found in session, redirecting to step1');
                return redirect()->route('custom-package.step1')
                    ->with('error', 'Silakan isi detail acara terlebih dahulu');
            }
            
            // Get all service items with media
            $serviceItems = ServiceItem::query()
                ->with('media')
                ->orderBy('name')
                ->get()
                ->groupBy('type');
            
            if ($serviceItems->isEmpty()) {
                Log::error('No active service items found');
                return redirect()->route('custom-package.step1')
                    ->with('error', 'Maaf, saat ini tidak ada layanan yang tersedia. Silakan coba lagi nanti.');
            }
            
            // Get selected services from session or old input
            $selectedServices = [];
            
            if ($request->old('services')) {
                $selectedServices = $this->processSelectedServices($request->old('services'));
                
                // Simpan kembali ke session jika ada old input
                $request->session()->put('custom_package.services', $selectedServices);
            } elseif ($request->session()->has('custom_package.services')) {
                $selectedServices = $request->session()->get('custom_package.services');
            }
            
            // Dapatkan event details dari session
            $eventDetails = $request->session()->get('custom_package.event_details');
            
            Log::info('Rendering step2 view with data:', [
                'service_items_count' => $serviceItems->count(),
                'selected_services_count' => count($selectedServices),
                'event_details' => $eventDetails
            ]);
            
            return view('custom-package.step2', [
                'serviceItems' => $serviceItems,
                'selectedServices' => $selectedServices,
                'eventDetails' => $eventDetails,
                'currentStep' => 2,
                'progress' => 66
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in step2: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return redirect()->route('custom-package.step1')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Process selected services from old input
     * 
     * @param  array|null  $services
     * @return array
     */
    protected function processSelectedServices($services): array
    {
        $selectedServices = [];
        
        if (is_array($services)) {
            foreach ($services as $service) {
                if (isset($service['service_item_id'])) {
                    $selectedServices[$service['service_item_id']] = [
                        'quantity' => (int)($service['quantity'] ?? 1),
                        'notes' => $service['notes'] ?? null
                    ];
                }
            }
        }
        
        return $selectedServices;
    }
    
    /**
     * Process step 2 and go to step 3
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Process step 2 and go to step 3
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processStep2(Request $request)
    {
        try {
            Log::info('Processing step2 with input:', $request->all());
            
            // Pastikan ada data event details di session
            if (!$request->session()->has('custom_package.event_details')) {
                Log::warning('No event details in session, redirecting to step1');
                return redirect()->route('custom-package.step1')
                    ->with('error', 'Silakan isi detail acara terlebih dahulu');
            }
            
            // Validasi input
            $validated = $request->validate([
                'services' => 'required|array|min:1|max:20',
                'services.*.service_item_id' => 'required|exists:service_items,id',
                'services.*.quantity' => 'required|integer|min:1|max:1000',
                'services.*.notes' => 'nullable|string|max:500',
            ], [
                'services.required' => 'Pilih setidaknya satu layanan',
                'services.min' => 'Pilih setidaknya satu layanan',
                'services.*.service_item_id.required' => 'ID layanan tidak valid',
                'services.*.service_item_id.exists' => 'Layanan yang dipilih tidak valid',
                'services.*.quantity.required' => 'Jumlah harus diisi',
                'services.*.quantity.integer' => 'Jumlah harus berupa angka',
                'services.*.quantity.min' => 'Jumlah minimal 1',
                'services.*.quantity.max' => 'Jumlah maksimal 1000',
                'services.*.notes.max' => 'Catatan maksimal 500 karakter',
            ]);
            
            // Dapatkan service items yang dipilih
            $serviceItems = ServiceItem::whereIn('id', collect($validated['services'])
                ->pluck('service_item_id'))
                ->get()
                ->keyBy('id');
                
            if ($serviceItems->isEmpty()) {
                throw new \Exception('Tidak ada layanan yang valid ditemukan');
            }
            
            // Siapkan data services untuk disimpan
            $services = [];
            $hasValidService = false;
            
            foreach ($validated['services'] as $service) {
                $serviceItemId = $service['service_item_id'];
                $serviceItem = $serviceItems->get($serviceItemId);
                
                if (!$serviceItem) {
                    Log::warning('Service item not found, skipping', ['id' => $serviceItemId]);
                    continue;
                }
                
                $quantity = max(1, (int)($service['quantity'] ?? 1));
                $price = (float)($serviceItem->base_price ?? 0);
                
                $services[] = [
                    'service_item_id' => $serviceItem->id,
                    'name' => $serviceItem->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'notes' => $this->sanitizeString($service['notes'] ?? null),
                    'total' => $price * $quantity,
                    'image' => $serviceItem->getFirstMediaUrl('default', 'thumb')
                ];
                
                $hasValidService = true;
            }
            
            if (!$hasValidService) {
                throw new \Exception('Tidak ada layanan yang valid untuk disimpan');
            }
            
            // Update session
            $request->session()->put('custom_package.services', $services);
            
            Log::info('Services saved to session:', [
                'service_count' => count($services),
                'first_service' => $services[0] ?? null
            ]);
            
            return redirect()->route('custom-package.step3')
                ->with('success', 'Layanan berhasil dipilih, silakan tinjau pesanan Anda');
                
        } catch (ValidationException $e) {
            Log::warning('Validation error in processStep2:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error in processStep2: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Validate service items existence and availability
     * 
     * @param  array  $services
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    protected function validateServiceItems(array $services): void
    {
        $serviceIds = collect($services)->pluck('service_item_id')->unique();
        
        $existingServices = ServiceItem::query()
            ->whereIn('id', $serviceIds)
            ->pluck('id')
            ->toArray();
            
        if (count($existingServices) !== $serviceIds->count()) {
            throw ValidationException::withMessages([
                'services' => ['Beberapa layanan yang dipilih tidak tersedia.']
            ]);
        }
    }

    /**
     * Store service data in session for review
     * 
     * @param  array  $validatedServices
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function storeServicesInSession(array $validatedServices, Request $request): void
    {
        // Get all service items with their translations
        $serviceItems = ServiceItem::query()
            ->with(['translations'])
            ->whereIn('id', collect($validatedServices)->pluck('service_item_id'))
            ->get()
            ->keyBy('id');

        // Prepare services data with proper formatting
        $servicesData = collect($validatedServices)
            ->map(function ($service) use ($serviceItems) {
                $serviceItem = $serviceItems->get($service['service_item_id']);
                
                if (!$serviceItem) {
                    return null;
                }

                $quantity = (int) ($service['quantity'] ?? 1);
                $price = (float) $serviceItem->base_price;
                
                // Get translations with fallback
                $name = $serviceItem->getTranslation('name', app()->getLocale(), 'id');
                $description = $serviceItem->getTranslation('description', app()->getLocale(), 'id');

                return [
                    'service_item_id' => $serviceItem->id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'quantity' => $quantity,
                    'notes' => $this->sanitizeString($service['notes'] ?? null),
                    'total' => $price * $quantity,
                    'image' => $serviceItem->getFirstMediaUrl('default', 'thumb')
                ];
            })
            ->filter() // Remove null values
            ->values() // Reset array keys
            ->toArray();

        // Store in session
        $request->session()->put('custom_package.services', $servicesData);
    }
    
    /**
     * Sanitize string input
     *
     * @param  string|null  $value
     * @return string|null
     */
    protected function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Show the wizard step 3 - Budget & Review
     */
    public function step3(Request $request)
    {
        try {
            // Debug: Tampilkan isi session
            Log::info('Session in step3:', [
                'session_data' => $request->session()->all(),
                'custom_package' => $request->session()->get('custom_package')
            ]);

            // Redirect to step 1 if no session data
            if (!$request->session()->has('custom_package.event_details')) {
                Log::warning('No event details in session, redirecting to step1');
                return redirect()->route('custom-package.step1')
                    ->with('error', 'Silakan isi detail acara terlebih dahulu');
            }
            
            // Get event details from session
            $eventDetails = $request->session()->get('custom_package.event_details');
            $eventTypeId = $eventDetails['event_type_id'] ?? null;
            
            if (!$eventTypeId) {
                Log::error('No event type ID found in session');
                return redirect()->route('custom-package.step1')
                    ->with('error', 'Tipe acara tidak valid');
            }
            
            // Get event type
            $eventType = EventType::find($eventTypeId);
            
            if (!$eventType) {
                Log::error('Event type not found:', ['event_type_id' => $eventTypeId]);
                return redirect()->route('custom-package.step1')
                    ->with('error', 'Tipe acara tidak ditemukan');
            }
            
            // Get services data from session
            $services = $request->session()->get('custom_package.services', []);
            
            Log::info('Services from session:', ['services' => $services]);
            
            if (empty($services)) {
                Log::warning('No services selected, redirecting to step2');
                return redirect()->route('custom-package.step2')
                    ->with('error', 'Silakan pilih setidaknya satu layanan');
            }
            
            // Get service items for additional data
            $serviceItemIds = collect($services)->pluck('service_item_id')->filter()->toArray();
            
            if (empty($serviceItemIds)) {
                Log::error('No valid service item IDs found');
                return redirect()->route('custom-package.step2')
                    ->with('error', 'Data layanan tidak valid')
                    ->withInput();
            }
            
            $serviceItems = ServiceItem::with('media')
                ->whereIn('id', $serviceItemIds)
                ->get()
                ->keyBy('id');
            
            if ($serviceItems->isEmpty()) {
                Log::error('No service items found for the given IDs', ['ids' => $serviceItemIds]);
                return redirect()->route('custom-package.step2')
                    ->with('error', 'Data layanan tidak ditemukan')
                    ->withInput();
            }
            
            // Enrich services data with additional information
            $enrichedServices = [];
            $subtotal = 0;
            
            foreach ($services as $service) {
                $serviceItemId = $service['service_item_id'] ?? null;
                
                if (!$serviceItemId || !$serviceItems->has($serviceItemId)) {
                    Log::warning('Skipping invalid service item', ['service' => $service]);
                    continue;
                }
                
                $serviceItem = $serviceItems->get($serviceItemId);
                $quantity = max(1, (int)($service['quantity'] ?? 1));
                $unitPrice = (float) ($serviceItem->base_price ?? 0);
                $serviceTotal = $unitPrice * $quantity;
                
                $enrichedService = [
                    'service_item' => (object)[
                        'id' => $serviceItem->id,
                        'name' => $serviceItem->name,
                        'description' => $serviceItem->description,
                        'image' => $serviceItem->getFirstMediaUrl('default', 'thumb')
                    ],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $serviceTotal,
                    'notes' => $this->sanitizeString($service['notes'] ?? null)
                ];
                
                $enrichedServices[] = $enrichedService;
                $subtotal += $serviceTotal;
            }
            
            if (empty($enrichedServices)) {
                Log::error('No valid services to display');
                return redirect()->route('custom-package.step2')
                    ->with('error', 'Tidak ada layanan yang valid untuk ditampilkan')
                    ->withInput();
            }
            
            // Calculate total price
            $totalPrice = $subtotal;
            
            // Store the enriched services in session for the store method
            $request->session()->put('custom_package.enriched_services', $enrichedServices);
            
            Log::info('Rendering step3 view with data:', [
                'event_type' => $eventType->name,
                'services_count' => count($enrichedServices),
                'subtotal' => $subtotal,
                'total_price' => $totalPrice
            ]);
            
            return view('custom-package.step3', [
                'currentStep' => 3,
                'progress' => 100,
                'eventType' => $eventType,
                'eventDetails' => $eventDetails,
                'services' => $enrichedServices,
                'subtotal' => $subtotal,
                'totalPrice' => $totalPrice,
                'budget' => $request->old('budget', 0),
                'reference_files' => $request->old('reference_files'),
                'terms' => $request->old('terms', false)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in step3: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return redirect()->route('custom-package.step2')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
