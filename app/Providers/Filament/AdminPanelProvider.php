<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use App\Filament\Teacher\Widgets\ActivitiesAdvancedChartWidget;
use App\Filament\Teacher\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Teacher\Widgets\ScholarshipAdvancedChartWidget;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->default()
            ->login()
            ->authGuard('admins')
            ->passwordReset()
            ->authPasswordBroker('admins')
            ->brandName('SchoolCare ERP')
            ->brandLogo(asset('img/logo.png'))
            ->favicon(asset('img/favicon.png'))
            ->sidebarCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Academic Management')
                    ->icon('heroicon-o-academic-cap'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Student Affairs')
                    ->icon('heroicon-o-user-group'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Discipline & ECA')
                    ->icon('heroicon-o-sparkles'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Administration & Users')
                    ->icon('heroicon-o-shield-check'),
            ])
            ->brandLogoHeight('42px')
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\SchoolOverviewStatsWidget::class,
                \App\Filament\Widgets\StudentRequest::class,
                \App\Filament\Widgets\TeacherRequest::class,
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
