<?php

namespace App\Filament\Student\Resources\NegativeBehaviourResource\Pages;

use App\Filament\Student\Resources\NegativeBehaviourResource;
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
