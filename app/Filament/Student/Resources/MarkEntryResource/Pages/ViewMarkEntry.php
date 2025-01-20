<?php

namespace App\Filament\Student\Resources\MarkEntryResource\Pages;

use App\Filament\Student\Resources\MarkEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMarkEntry extends ViewRecord
{
    protected static string $resource = MarkEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
