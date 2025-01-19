<?php

namespace App\Filament\Teacher\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class AdvancedStatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', \App\Models\Student::count())
                ->icon('heroicon-o-users')
                ->description('Total registered students')
                ->descriptionColor('success'),

            Stat::make('Total Teachers', \App\Models\Teacher::count())
                ->icon('heroicon-o-academic-cap')
                ->description('Total registered teachers')
                ->descriptionColor('primary'),

            Stat::make('Total Sections', \App\Models\Section::count())
                ->icon('heroicon-o-view-columns')
                ->description('Total active sections')
                ->descriptionColor('info'),

            Stat::make('Activities Records', \App\Models\Activities::count())
                ->icon('heroicon-o-check-circle')
                ->description('Total activities records logged')
                ->descriptionColor('success'),

            Stat::make('Scholarships Awarded', \App\Models\Scholorship::count())
                ->icon('heroicon-o-currency-dollar')
                ->description('Scholarships awarded to students')
                ->descriptionColor('warning'),

            Stat::make('Assignments Created', \App\Models\Assignment::count())
                ->icon('heroicon-o-document-text')
                ->description('Total assignments created')
                ->descriptionColor('secondary'),

            Stat::make('Total Municipality', \App\Models\Municipality::count())
                ->icon('heroicon-o-map-pin')
                ->description('Municipality managed in the system')
                ->descriptionColor('info'),

            Stat::make('Total Grades', \App\Models\Grade::count())
                ->icon('heroicon-o-star')
                ->description('Grades assigned to students')
                ->descriptionColor('success'),
        ];
    }
}
