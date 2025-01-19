<?php

namespace App\Filament\Student\Widgets;

use App\Models\StudentParticipation;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\Auth;

class ParticipationAdvancedChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Activity Participation';

    protected function getData(): array
    {
        // Fetch participation data for the authenticated student
        $participationData = StudentParticipation::query()
            ->join('activities', 'student_participations.activity_id', '=', 'activities.id') // Join with activities table
            ->where('student_participations.student_id', Auth::id()) // Filter by the authenticated student
            ->selectRaw('YEAR(activities.start_date) as year, COUNT(*) as total_participations')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $participationData->pluck('year')->toArray(), // Years
            'datasets' => [
                [
                    'label' => 'Total Activities Participated',
                    'data' => $participationData->pluck('total_participations')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)', // Light blue
                    'borderColor' => 'rgba(54, 162, 235, 1)',       // Blue
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Line chart type
    }
}
