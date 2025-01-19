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
            ->join('activities', 'student_participations.activity_id', '=', 'activities.id') // Join with activities table
            ->selectRaw('YEAR(activities.start_date) as year, COUNT(*) as total_participants, AVG(student_participations.obtained_rank) as avg_rank')
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
                [
                    'label' => 'Average Rank',
                    'data' => $participationData->pluck('avg_rank')->toArray(),
                    'backgroundColor' => 'rgba(255, 206, 86, 0.5)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Line chart type
    }
}
