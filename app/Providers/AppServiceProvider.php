<?php

namespace App\Providers;

use App\Filament\Teacher\Widgets\AdvancedStatsOverviewWidget;
use App\Models\Student;
use App\Models\Teacher;
use App\Observers\StudentObserver;
use App\Observers\TeacherObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\MockObject\Builder\Stub;
use Filament\Facades\Filament;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Teacher::observe(TeacherObserver::class);
        Student::observe(StudentObserver::class);

        Filament::registerWidgets([
            AdvancedStatsOverviewWidget::class,
        ]);
    }
}
