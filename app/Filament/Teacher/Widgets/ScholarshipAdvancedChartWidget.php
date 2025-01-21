<?php

namespace App\Filament\Teacher\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use App\Models\Scholorship;

class ScholarshipAdvancedChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Scholarship Distribution Over Time';

    protected function getData(): array
    {
        // Fetch scholarship data grouped by year
        $scholarshipData = Scholorship::query()
            ->selectRaw('YEAR(year) as year, COUNT(id) as total_scholarships, SUM(amount) as total_amount')
            ->groupByRaw('YEAR(year)') // Group by year extracted from the 'year' column
            ->orderByRaw('YEAR(year)') // Order by year
            ->get();

        return [
            'labels' => $scholarshipData->pluck('year')->toArray(), // Years
            'datasets' => [
                [
                    'label' => 'Total Scholarships Distributed',
                    'data' => $scholarshipData->pluck('total_scholarships')->toArray(),
                    'borderColor' => '#4CAF50',
                    'backgroundColor' => 'rgba(76, 175, 80, 0.2)',
                ],
                [
                    'label' => 'Total Amount Awarded',
                    'data' => $scholarshipData->pluck('total_amount')->toArray(),
                    'borderColor' => '#FF9800',
                    'backgroundColor' => 'rgba(255, 152, 0, 0.2)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Line chart type
    }
}
