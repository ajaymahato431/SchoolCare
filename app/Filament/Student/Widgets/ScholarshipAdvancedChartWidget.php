<?php

namespace App\Filament\Student\Widgets;

use App\Models\Scholorship;
use App\Support\Concerns\BuildsYearExpression;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\Auth;

class ScholarshipAdvancedChartWidget extends AdvancedChartWidget
{
    use BuildsYearExpression;

    protected static ?string $heading = 'My Scholarship Distribution Over Time';

    protected function getData(): array
    {
        $yearExpression = $this->yearExpression('scholorships.year');

        $scholarshipData = Scholorship::query()
            ->join('scholorship_student', 'scholorships.id', '=', 'scholorship_student.scholorship_id')
            ->where('scholorship_student.student_id', Auth::id())
            ->selectRaw("{$yearExpression} as year, COUNT(*) as total_scholarships, SUM(scholorships.amount) as total_amount")
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $scholarshipData->pluck('year')->toArray(),
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
        return 'line';
    }
}
