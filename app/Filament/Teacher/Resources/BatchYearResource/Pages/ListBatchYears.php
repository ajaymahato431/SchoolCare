<?php

namespace App\Filament\Teacher\Resources\BatchYearResource\Pages;

use App\Filament\Teacher\Resources\BatchYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBatchYears extends ListRecords
{
    protected static string $resource = BatchYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
