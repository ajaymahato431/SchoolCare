<?php

namespace App\Filament\Widgets;

use App\Models\BatchYear;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentBehavior;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolOverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeStudents = Student::where('status', 'approved')->count();
        $pendingStudents = Student::where('status', 'pending')->count();

        $activeTeachers = Teacher::where('status', 'approved')->count();
        $pendingTeachers = Teacher::where('status', 'pending')->count();

        $activeBatch = BatchYear::where('is_active', true)->first();
        $batchLabel = $activeBatch ? $activeBatch->batch : (BatchYear::latest()->value('batch') ?? 'Not Configured');

        $gradesCount = Grade::count();
        $sectionsCount = Section::count();
        $subjectsCount = Subject::count();

        $meritsCount = StudentBehavior::where('type', 'positive')->count();
        $infractionsCount = StudentBehavior::where('type', 'negative')->count();

        return [
            Stat::make('Active Students', (string) $activeStudents)
                ->description($pendingStudents > 0 ? "{$pendingStudents} pending approval" : 'All accounts approved')
                ->descriptionIcon($pendingStudents > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-badge')
                ->color($pendingStudents > 0 ? 'warning' : 'success')
                ->chart([10, 15, 20, 25, 28, $activeStudents]),

            Stat::make('Faculty Members', (string) $activeTeachers)
                ->description($pendingTeachers > 0 ? "{$pendingTeachers} awaiting verification" : 'Active teaching staff')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($pendingTeachers > 0 ? 'warning' : 'info')
                ->chart([5, 6, 8, 10, 12, $activeTeachers]),

            Stat::make('Academic Session', $batchLabel)
                ->description('Current active academic calendar')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Curriculum & Structure', "{$gradesCount} Grades · {$sectionsCount} Sec")
                ->description("{$subjectsCount} subjects offered")
                ->descriptionIcon('heroicon-m-building-library')
                ->color('slate'),

            Stat::make('Student Behavior (ECA)', "{$meritsCount} Merits")
                ->description("{$infractionsCount} infractions logged")
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('amber'),
        ];
    }
}
