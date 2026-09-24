<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(fn () => redirect()->route('login'))
            ->brandName('Plataforma Institucional de Formación y Microcredenciales')
            ->brandLogo(asset('img/LogoUNAQ.webp'))
            ->brandLogoHeight('3.75rem')
            ->darkMode(false)
            ->globalSearch(false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->maxContentWidth(Width::Full)
            ->sidebarWidth('17rem')
            ->topNavigation()
            ->subNavigationPosition(SubNavigationPosition::Top)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => view('filament.partials.panel-loader')->render(),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): string => view('filament.partials.system-name')->render(),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): string => view('filament.partials.user-name')->render(),
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): string => view('filament.partials.panel-loader-script')->render(),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => view('filament.partials.panel-footer')->render(),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
