<?php

namespace App\Filament\Student\Resources\ActivitiesResource\Pages;

use App\Filament\Student\Resources\ActivitiesResource;
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
