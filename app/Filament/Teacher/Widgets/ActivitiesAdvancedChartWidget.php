<?php

namespace App\Filament\Teacher\Widgets;

use App\Models\StudentParticipation;
use App\Support\Concerns\BuildsYearExpression;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;

class ActivitiesAdvancedChartWidget extends AdvancedChartWidget
{
    use BuildsYearExpression;

    protected static ?string $heading = 'Student Participation Overview (Grouped by Year)';

    protected function getData(): array
    {
        $yearExpression = $this->yearExpression('activities.start_date');

        $participationData = StudentParticipation::query()
            ->join('activities', 'student_participations.activity_id', '=', 'activities.id')
            ->selectRaw("{$yearExpression} as year, COUNT(*) as total_participants")
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $participationData->pluck('year')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Participants',
                    'data' => $participationData->pluck('total_participants')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
