<?php

namespace App\Filament\Teacher\Resources\SubjectAssignmentResource\Pages;

use App\Filament\Teacher\Resources\SubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubjectAssignments extends ListRecords
{
    protected static string $resource = SubjectAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
