<?php

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
Route::prefix('admin')
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
        // Filament will automatically register routes for resources
        Route::get('/login', '\Filament\Http\Controllers\Auth\LoginController@create')
            ->name('filament.admin.auth.login');
        
        Route::post('/login', '\Filament\Http\Controllers\Auth\LoginController@store');
    });

// Ensure the admin panel is accessible
Route::redirect('/admin', '/admin/login');
