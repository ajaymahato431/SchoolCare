<?php

namespace App\Filament\Teacher\Resources\ScholorshipResource\Pages;

use App\Filament\Teacher\Resources\ScholorshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewScholorship extends ViewRecord
{
    protected static string $resource = ScholorshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
