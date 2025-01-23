<?php

namespace App\Filament\Student\Resources\StudentResource\Pages;

use App\Filament\Student\Resources\StudentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Download Report')
                ->url(fn($record) => route('studentReport.pdf', $record->id))
                ->openUrlInNewTab()
                ->label('Download Report')
                ->icon('heroicon-o-arrow-down-tray'),
        ];
    }
}
