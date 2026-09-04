<?php

namespace App\Filament\Resources\StudentBehaviorResource\Pages;

use App\Filament\Resources\StudentBehaviorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentBehavior extends ViewRecord
{
    protected static string $resource = StudentBehaviorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
