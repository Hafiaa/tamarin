<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update the settings in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'working_hours' => 'nullable|string',
            'google_maps_embed' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            foreach ($validated as $key => $value) {
                if ($value !== null) {
                    DB::table('settings')
                        ->updateOrInsert(
                            ['group' => 'site', 'name' => $key],
                            [
                                'payload' => json_encode($value),
                                'updated_at' => now()
                            ]
                        );
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating settings: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan pengaturan.')
                ->withInput();
        }
    }
}
