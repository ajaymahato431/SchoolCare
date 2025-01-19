<?php

namespace App\Filament\Teacher\Resources\NegativeBehaviourResource\Pages;

use App\Filament\Teacher\Resources\NegativeBehaviourResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNegativeBehaviour extends ViewRecord
{
    protected static string $resource = NegativeBehaviourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
