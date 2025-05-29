<?php

use App\Filament\Pages\Settings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;

// Filament Admin Panel Routes
Route::domain(config('filament.domain'))
    ->middleware([
        'web',
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
    ])
    ->group(function () {
        // Explicitly define the settings route
        Route::get('/admin/settings', Settings::class)
            ->name('filament.admin.pages.settings')
            ->middleware([Authenticate::class]);
            
        // Add other Filament routes here if needed
    });

// Ensure the admin panel is accessible
Route::redirect('/admin', '/admin/dashboard');
