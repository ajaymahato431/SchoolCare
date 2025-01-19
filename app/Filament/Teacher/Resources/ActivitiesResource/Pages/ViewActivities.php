<?php

namespace App\Filament\Teacher\Resources\ActivitiesResource\Pages;

use App\Filament\Teacher\Resources\ActivitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewActivities extends ViewRecord
{
    protected static string $resource = ActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
