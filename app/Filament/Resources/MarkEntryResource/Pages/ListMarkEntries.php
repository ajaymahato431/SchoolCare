<?php

namespace App\Filament\Resources\MarkEntryResource\Pages;

use App\Filament\Resources\MarkEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarkEntries extends ListRecords
{
    protected static string $resource = MarkEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('+ Single Mark Entry'),
        ];
    }
}
