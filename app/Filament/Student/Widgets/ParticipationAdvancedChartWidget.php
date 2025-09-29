<?php

namespace App\Filament\Student\Widgets;

use App\Models\Activities;
use App\Models\StudentParticipation;
use App\Support\Concerns\BuildsYearExpression;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\Auth;

class ParticipationAdvancedChartWidget extends AdvancedChartWidget
{
    use BuildsYearExpression;

    protected static ?string $heading = 'Activity Participation';

    protected function getData(): array
    {
        $yearExpression = $this->yearExpression('activities.start_date');

        $participationData = Activities::query()
            ->join('student_participations', 'activities.id', '=', 'student_participations.activity_id')
            ->where('student_participations.student_id', Auth::id())
            ->selectRaw("{$yearExpression} as year, COUNT(*) as total_activities")
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $participationData->pluck('year')->toArray(),
            'datasets' => [
                [
                    'label' => 'Activities Participated By Student',
                    'data' => $participationData->pluck('total_activities')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
