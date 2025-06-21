<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    /**
     * Display the about us page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return redirect()->route('company.tentang-kami');
    }
    
    /**
     * Display the integrated about us page with all sections.
     *
     * @return \Illuminate\View\View
     */
    public function tentangKami()
    {
        $aboutContent = Setting::get('about_content');
        $companyVision = Setting::get('company_vision');
        $companyMission = Setting::get('company_mission');
        
        $teamMembers = TeamMember::active()
            ->orderBy('sort_order')
            ->get();
            
        $socialMedia = [
            'instagram' => [
                [
                    'url' => 'https://www.instagram.com/tamarincafe?igsh=bDBqbzJwMHBjczZl',
                    'name' => 'Tamarin Cafe',
                    'handle' => '@tamarincafe'
                ],
                [
                    'url' => 'https://www.instagram.com/nikahditamarin?igsh=dTA3YjRuZ3BzbGRo',
                    'name' => 'Nikah di Tamarin',
                    'handle' => '@nikahditamarin'
                ]
            ]
        ];
        
        // Get gallery images (assuming you have a Gallery model)
        $galleryImages = [];
        for ($i = 1; $i <= 6; $i++) {
            $galleryImages[] = asset('images/gallery/event-' . $i . '.jpg');
        }
        
        return view('company.tentang-kami', compact(
            'aboutContent',
            'companyVision',
            'companyMission',
            'teamMembers',
            'socialMedia',
            'galleryImages'
        ));
    }
    
    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        // The view now uses the setting() helper directly
        return view('company.contact');
    }
    
    /**
     * Process the contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Send an email with the contact form data
        $toEmail = setting('email', 'info@tamacafe.com');
        $subject = "Contact Form: " . $request->subject;
        
        $emailContent = "You have received a new contact form submission:\n\n";
        $emailContent .= "Name: " . $request->name . "\n";
        $emailContent .= "Email: " . $request->email . "\n";
        $emailContent .= "Subject: " . $request->subject . "\n";
        $emailContent .= "Message: " . $request->message . "\n";
        
        // In a production environment, you would use Laravel's Mail facade
        // For now, we'll just log the email
        \Log::info("Contact form submission received. Would send to: " . $toEmail);
        \Log::info($emailContent);
        
        return redirect()->route('company.contact')
            ->with('success', 'Thank you for your message. We will get back to you soon!');
    }
    
    /**
     * Display the gallery page.
     *
     * @return \Illuminate\View\View
     */
    public function gallery()
    {
        // In a real application, you would fetch gallery images from a database or media library
        // For now, we'll just return a view
        
        return view('company.gallery');
    }
}
