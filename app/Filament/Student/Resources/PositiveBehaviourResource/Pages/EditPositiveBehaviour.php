<?php

namespace App\Filament\Student\Resources\PositiveBehaviourResource\Pages;

use App\Filament\Student\Resources\PositiveBehaviourResource;
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
