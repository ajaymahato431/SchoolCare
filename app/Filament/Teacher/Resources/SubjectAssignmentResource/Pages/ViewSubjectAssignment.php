<?php

namespace App\Filament\Teacher\Resources\SubjectAssignmentResource\Pages;

use App\Filament\Teacher\Resources\SubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubjectAssignment extends ViewRecord
{
    protected static string $resource = SubjectAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
