<?php

namespace App\Filament\Student\Resources\AssignmentResource\Pages;

use App\Filament\Student\Resources\AssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignment extends ViewRecord
{
    protected static string $resource = AssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
