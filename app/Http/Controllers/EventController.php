<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use App\Models\PackageTemplate;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the event packages.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $eventTypeId = $request->query('event_type');
        $selectedEventType = null;
        
        $query = PackageTemplate::where('is_active', true)
            ->with(['eventType', 'media']);
        
        if ($eventTypeId) {
            $selectedEventType = EventType::find($eventTypeId);
            if ($selectedEventType) {
                $query->where('event_type_id', $eventTypeId);
            }
        }
        
        $packages = $query->paginate(9);
        $eventTypes = EventType::where('is_active', true)
            ->withCount(['packages' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();
        
        return view('events.index', [
            'packages' => $packages,
            'eventTypes' => $eventTypes,
            'eventTypeId' => $eventTypeId,
            'selectedEventType' => $selectedEventType
        ]);
    }
    
    /**
     * Display the specified event package.
     *
     * @param  \App\Models\PackageTemplate  $package
     * @return \Illuminate\View\View
     */
    public function show(PackageTemplate $package)
    {
        // Ensure the package is active
        if (!$package->is_active) {
            abort(404);
        }
        
        // Eager load relationships
        $package->load(['eventType', 'serviceItems']);
            
        // Get related packages (same event type)
        $relatedPackages = PackageTemplate::where('is_active', true)
            ->where('event_type_id', $package->event_type_id)
            ->where('id', '!=', $package->id)
            ->take(3)
            ->get();
            
        return view('events.show', compact('package', 'relatedPackages'));
    }
}
