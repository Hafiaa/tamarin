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
        
        $query = PackageTemplate::where('is_active', true);
        
        if ($eventTypeId) {
            $query->where('event_type_id', $eventTypeId);
        }
        
        $packages = $query->with('eventType')->paginate(9);
        $eventTypes = EventType::where('is_active', true)->get();
        
        return view('events.index', compact('packages', 'eventTypes', 'eventTypeId'));
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
