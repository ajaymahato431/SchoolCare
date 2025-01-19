<?php

namespace App\Filament\Teacher\Resources\ActivitiesResource\Pages;

use App\Filament\Teacher\Resources\ActivitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivities extends EditRecord
{
    protected static string $resource = ActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
