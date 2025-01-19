<?php

namespace App\Filament\Teacher\Resources\ActivitiesResource\Pages;

use App\Filament\Teacher\Resources\ActivitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
