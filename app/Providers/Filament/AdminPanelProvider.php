<?php

namespace App\Providers\Filament;

use App\Filament\Resources\TrainingProgramResource;
use App\Filament\Resources\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
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
            ->login()
            ->brandName('Plataforma Institucional de Formación')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->resources([
                UserResource::make('superadministradores')->slug('users/superadministradores'),
                UserResource::make('personal')->slug('users/personal'),
                UserResource::make('responsables_area')->slug('users/responsables-area'),
                UserResource::make('evaluadores')->slug('users/evaluadores'),
                UserResource::make('recursos_humanos')->slug('users/recursos-humanos'),
                UserResource::make('calidad_academica')->slug('users/calidad-academica'),
                UserResource::make('educacion_continua')->slug('users/educacion-continua'),
                UserResource::make('profesores')->slug('users/profesores'),
                UserResource::make('alumnos')->slug('users/alumnos'),
                UserResource::make('externos')->slug('users/externos'),
                TrainingProgramResource::make('cursos')->slug('training-programs/cursos'),
                TrainingProgramResource::make('minicursos')->slug('training-programs/minicursos'),
                TrainingProgramResource::make('talleres')->slug('training-programs/talleres'),
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => view('filament.partials.panel-styles')->render(),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => view('filament.partials.panel-loader')->render(),
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): string => view('filament.partials.panel-loader-script')->render(),
            )
            ->renderHook(
                PanelsRenderHook::PAGE_END,
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
