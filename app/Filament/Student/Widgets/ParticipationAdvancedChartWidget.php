<?php

namespace App\Filament\Student\Widgets;

use App\Models\Activities;
use App\Models\StudentParticipation;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\Auth;

class ParticipationAdvancedChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Activity Participation';

    protected function getData(): array
    {
        // Fetch the authenticated user's participation data
        $participationData = Activities::query()
            ->join('student_participations', 'activities.id', '=', 'student_participations.activity_id')
            ->where('student_participations.student_id', Auth::id())
            ->selectRaw('YEAR(activities.start_date) as year, COUNT(*) as total_activities')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $participationData->pluck('year')->toArray(), // Extract years for the labels
            'datasets' => [
                [
                    'label' => 'Activities Participated By Student',
                    'data' => $participationData->pluck('total_activities')->toArray(), // Extract unique activity counts
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)', // Light red
                    'borderColor' => 'rgba(255, 99, 132, 1)',       // Red
                    'borderWidth' => 1,
                ],
            ],
        ];
    }



    protected function getType(): string
    {
        return 'line'; // Line chart type
    }
}
