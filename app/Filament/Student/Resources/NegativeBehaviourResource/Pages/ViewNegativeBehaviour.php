<?php

namespace App\Filament\Student\Resources\NegativeBehaviourResource\Pages;

use App\Filament\Student\Resources\NegativeBehaviourResource;
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
