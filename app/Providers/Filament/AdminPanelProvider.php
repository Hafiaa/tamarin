<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Widgets;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'info' => Color::Blue,
            ])
            ->font('Poppins')
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->discoverResources(app_path('Filament/Resources'), 'App\\Filament\\Resources')
            ->discoverPages(app_path('Filament/Pages'), 'App\\Filament\\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class,
                \App\Filament\Pages\Settings::class,
            ])
            ->navigationGroups([
                'Settings',
            ])
            ->discoverWidgets(app_path('Filament/Widgets'), 'App\\Filament\\Widgets')
            ->resources([
                // Keep the SettingsResource for backward compatibility
                \App\Filament\Resources\SettingsResource::class,
            ])
            ->widgets([
                StatsOverview::class,
                Widgets\AccountWidget::class,
            ])
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->spa()
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full');
    }
}
