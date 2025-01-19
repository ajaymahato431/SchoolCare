<?php

namespace App\Filament\Teacher\Resources\ExamTypeResource\Pages;

use App\Filament\Teacher\Resources\ExamTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamType extends EditRecord
{
    protected static string $resource = ExamTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
