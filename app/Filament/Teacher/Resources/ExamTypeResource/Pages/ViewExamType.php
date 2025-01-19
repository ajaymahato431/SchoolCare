<?php

namespace App\Filament\Teacher\Resources\ExamTypeResource\Pages;

use App\Filament\Teacher\Resources\ExamTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExamType extends ViewRecord
{
    protected static string $resource = ExamTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
