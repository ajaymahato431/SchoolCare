<?php

namespace App\Filament\Student\Resources\ActivitiesResource\Pages;

use App\Filament\Student\Resources\ActivitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivities extends EditRecord
{
    protected static string $resource = ActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
