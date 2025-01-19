<?php

namespace App\Filament\Teacher\Resources\NegativeBehaviourResource\Pages;

use App\Filament\Teacher\Resources\NegativeBehaviourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNegativeBehaviour extends EditRecord
{
    protected static string $resource = NegativeBehaviourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
