<?php

namespace App\Filament\Teacher\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use App\Models\Scholorship;
use Illuminate\Support\Facades\DB;

class ScholarshipAdvancedChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Scholarship Distribution Over Time';

    protected function getData(): array
    {
        // Determine the database driver
        $driver = DB::connection()->getDriverName();

        // Set the correct date extraction function based on the driver for the 'year' column
        $yearExpression = ($driver === 'sqlite')
            ? "strftime('%Y', year)"
            : "YEAR(year)";

        // Fetch scholarship data grouped by year
        $scholarshipData = Scholorship::query()
            ->selectRaw("{$yearExpression} as year, COUNT(id) as total_scholarships, SUM(amount) as total_amount")
            ->groupBy('year') // Group by the alias 'year'
            ->orderBy('year') // Order by the alias 'year'
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
