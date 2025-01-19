<?php

namespace App\Filament\Teacher\Resources\PositiveBehaviourResource\Pages;

use App\Filament\Teacher\Resources\PositiveBehaviourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPositiveBehaviour extends EditRecord
{
    protected static string $resource = PositiveBehaviourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
