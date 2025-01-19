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
            ->selectRaw('YEAR(year) as year, COUNT(*) as total_scholarships, SUM(amount) as total_amount')
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
