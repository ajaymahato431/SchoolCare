<?php

namespace App\Filament\Teacher\Resources\MarkEntryResource\Pages;

use App\Filament\Teacher\Resources\MarkEntryResource;
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
