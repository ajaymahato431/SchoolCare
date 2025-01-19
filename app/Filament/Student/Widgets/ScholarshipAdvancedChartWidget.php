<?php

namespace App\Filament\Student\Widgets;

use App\Models\Scholorship;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\Auth;

class ScholarshipAdvancedChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'My Scholarship Distribution Over Time';

    protected function getData(): array
    {
        // Fetch scholarship data for the authenticated student grouped by year
        $scholarshipData = Scholorship::query()
            ->join('scholorship_student', 'scholorships.id', '=', 'scholorship_student.scholorship_id') // Join with the pivot table
            ->where('scholorship_student.student_id', Auth::id()) // Filter by authenticated student
            ->selectRaw('YEAR(scholorships.year) as year, COUNT(*) as total_scholarships, SUM(scholorships.amount) as total_amount')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $scholarshipData->pluck('year')->toArray(), // Years
            'datasets' => [
                [
                    'label' => 'Total Scholarships',
                    'data' => $scholarshipData->pluck('total_scholarships')->toArray(),
                    'borderColor' => '#4CAF50',
                    'backgroundColor' => 'rgba(76, 175, 80, 0.2)', // Light green
                ],
                [
                    'label' => 'Total Amount Awarded',
                    'data' => $scholarshipData->pluck('total_amount')->toArray(),
                    'borderColor' => '#FF9800',
                    'backgroundColor' => 'rgba(255, 152, 0, 0.2)', // Orange
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Line chart type
    }
}
