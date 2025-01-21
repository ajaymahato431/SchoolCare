<?php

namespace App\Filament\Teacher\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use App\Models\StudentParticipation;

class ActivitiesAdvancedChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Student Participation Overview (Grouped by Year)';

    protected function getData(): array
    {
        // Fetch participation data grouped by year
        $participationData = StudentParticipation::query()
            ->join('activities', 'student_participations.activity_id', '=', 'activities.id')
            ->selectRaw('YEAR(activities.start_date) as year, COUNT(*) as total_participants')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $participationData->pluck('year')->toArray(), // Years
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
        return 'line'; // Line chart type
    }
}
