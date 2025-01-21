<?php

namespace App\Filament\Student\Resources\PositiveBehaviourResource\Pages;

use App\Filament\Student\Resources\PositiveBehaviourResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPositiveBehaviour extends ViewRecord
{
    protected static string $resource = PositiveBehaviourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
