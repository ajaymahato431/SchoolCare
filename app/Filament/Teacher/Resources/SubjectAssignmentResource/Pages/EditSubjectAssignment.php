<?php

namespace App\Filament\Teacher\Resources\SubjectAssignmentResource\Pages;

use App\Filament\Teacher\Resources\SubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubjectAssignment extends EditRecord
{
    protected static string $resource = SubjectAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
