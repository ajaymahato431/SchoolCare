<?php

namespace App\Filament\Teacher\Widgets;

use App\Models\Scholorship;
use App\Support\Concerns\BuildsYearExpression;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;

class ScholarshipAdvancedChartWidget extends AdvancedChartWidget
{
    use BuildsYearExpression;

    protected static ?string $heading = 'Scholarship Distribution Over Time';

    protected function getData(): array
    {
        $yearExpression = $this->yearExpression('scholorships.year');

        $scholarshipData = Scholorship::query()
            ->selectRaw("{$yearExpression} as year, COUNT(id) as total_scholarships, SUM(amount) as total_amount")
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $scholarshipData->pluck('year')->toArray(),
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
        return 'line';
    }
}
