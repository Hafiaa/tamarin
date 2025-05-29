<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\EventType;
use App\Models\PackageTemplate;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get featured event packages
        $featuredPackages = PackageTemplate::where('is_active', true)
            ->with('eventType')
            ->take(3)
            ->get();
            
        // Get event types for the navigation
        $eventTypes = EventType::where('is_active', true)->get();
        
        // Get active announcements
        $announcements = Announcement::active()
            ->public()
            ->latest()
            ->take(3)
            ->get();
            
        // Get featured testimonials
        $testimonials = Testimonial::where('status', 'published')
            ->where('is_featured', true)
            ->with('user')
            ->take(4)
            ->get();
            
        return view('home', compact(
            'featuredPackages',
            'eventTypes',
            'announcements',
            'testimonials'
        ));
    }
}
