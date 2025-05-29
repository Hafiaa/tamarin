<?php

namespace App\Filament\Resources\SettingsResource\Pages;

use App\Filament\Resources\SettingsResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Cache;

class EditSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    
    protected static string $resource = SettingsResource::class;
    protected static string $view = 'filament.resources.settings-resource.pages.edit-settings';
    
    public $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettings());
    }

    protected function getFormSchema(): array
    {
        return SettingsResource::form(Forms\Form::make())->getSchema();
    }
    
    protected function getSettings(): array
    {
        $settings = [];
        
        // Get all settings groups
        $groups = ['general', 'contact', 'social', 'business'];
        
        foreach ($groups as $group) {
            $groupSettings = Setting::getAll($group);
            if ($groupSettings) {
                foreach ($groupSettings as $setting) {
                    $settings[$setting->name] = $setting->payload;
                }
            }
        }
        
        return $settings;
    }
    
    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            // Save each setting
            foreach ($data as $key => $value) {
                // Determine the group based on the field name
                $group = $this->getGroupForField($key);
                
                if ($group) {
                    Setting::set($group, $key, $value);
                }
            }
            
            // Clear the cache
            Cache::forget('settings.all');
            
            Notification::make()
                ->title('Pengaturan berhasil disimpan')
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal menyimpan pengaturan')
                ->body($e->getMessage())
                ->danger()
                ->send();
                
            throw $e;
        }
    }
    
    protected function getGroupForField(string $field): ?string
    {
        $fieldToGroupMap = [
            // General
            'site_name' => 'general',
            'site_description' => 'general',
            'site_logo' => 'general',
            'favicon' => 'general',
            'timezone' => 'general',
            'date_format' => 'general',
            'time_format' => 'general',
            'site_tagline' => 'general',
            'site_favicon' => 'general',
            
            // Contact
            'email' => 'contact',
            'phone' => 'contact',
            'whatsapp' => 'contact',
            'address' => 'contact',
            'city' => 'contact',
            'province' => 'contact',
            'postal_code' => 'contact',
            'google_maps_embed' => 'contact',
            
            // Social
            'facebook' => 'social',
            'instagram' => 'social',
            'twitter' => 'social',
            'youtube' => 'social',
            'tiktok' => 'social',
            
            // Business hours
            'monday' => 'business',
            'tuesday' => 'business',
            'wednesday' => 'business',
            'thursday' => 'business',
            'friday' => 'business',
            'saturday' => 'business',
            'sunday' => 'business',
        ];
        
        return $fieldToGroupMap[$field] ?? null;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
