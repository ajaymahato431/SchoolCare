<?php

namespace App\Providers\Filament;

use App\Filament\Teacher\Widgets\ActivitiesAdvancedChartWidget;
use App\Filament\Teacher\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Teacher\Widgets\ScholarshipAdvancedChartWidget;
use App\Filament\Teacher\Widgets\StudentRequest;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TeacherPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('teacher')
            ->path('teacher')
            ->login()
            ->profile()
            ->authGuard('teachers')
            ->brandLogo(asset('img/logo.png'))
            ->favicon(asset('img/favicon.png'))
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Tracking',
                'Setup',
                'User Section',
            ])
            ->brandLogoHeight('50px')
            ->passwordReset()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Teacher/Resources'), for: 'App\\Filament\\Teacher\\Resources')
            ->discoverPages(in: app_path('Filament/Teacher/Pages'), for: 'App\\Filament\\Teacher\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Teacher/Widgets'), for: 'App\\Filament\\Teacher\\Widgets')
            ->widgets([
                AdvancedStatsOverviewWidget::class,
                ScholarshipAdvancedChartWidget::class,
                ActivitiesAdvancedChartWidget::class,
            ])
            ->middleware([
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
            ]);
    }
}
