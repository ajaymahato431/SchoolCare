<?php

namespace App\Filament\Student\Widgets;

use App\Models\Activities;
use App\Models\AssignmentStudent;
use App\Models\ClassMapping;
use App\Models\Scholorship;
use App\Models\ScholorshipStudent;
use App\Models\StudentParticipation;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdvancedStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Exam Attend', Auth::user()->markEntries->count())
                ->icon('heroicon-o-document-text')
                ->description('Exam Attended by you')
                ->descriptionColor('info'),

            Stat::make('My Attendance', Auth::user()->attendences->count())
                ->icon('heroicon-o-calendar-days')
                ->description('Attendance records logged for you')
                ->descriptionColor('primary'),

            Stat::make('My Assignments', Auth::user()->assignments->count())
                ->icon('heroicon-o-document-text')
                ->description('Assignments submitted by you')
                ->descriptionColor('secondary'),

            Stat::make('My Scholarships', Auth::user()->scholorships->count())
                ->icon('heroicon-o-currency-dollar')
                ->description('Scholarships awarded to you')
                ->descriptionColor('warning'),

            Stat::make('Positive Behaviours', Auth::user()->positiveBehaviours->count())
                ->icon('heroicon-o-check-circle')
                ->description('Positive behaviours recorded')
                ->descriptionColor('success'),

            Stat::make('Negative Behaviours', Auth::user()->negativeBehaviours->count())
                ->icon('heroicon-o-x-circle')
                ->description('Negative behaviours recorded')
                ->descriptionColor('danger'),

            Stat::make('Participations', Auth::user()->participations->count())
                ->icon('heroicon-o-light-bulb')
                ->description('Events & Activities you have participated in')
                ->descriptionColor('info'),

            Stat::make('My Classes', Auth::user()->classMappings->count())
                ->icon('heroicon-o-academic-cap')
                ->description('Classes you are enrolled in')
                ->descriptionColor('primary'),

        ];
    }
}
