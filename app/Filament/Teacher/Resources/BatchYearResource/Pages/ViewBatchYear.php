<?php

namespace App\Filament\Teacher\Resources\BatchYearResource\Pages;

use App\Filament\Teacher\Resources\BatchYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBatchYear extends ViewRecord
{
    protected static string $resource = BatchYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
