<?php

namespace App\Filament\Student\Resources\ActivitiesResource\Pages;

use App\Filament\Student\Resources\ActivitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewActivities extends ViewRecord
{
    protected static string $resource = ActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
