<?php

namespace App\Filament\Teacher\Resources\PositiveBehaviourResource\Pages;

use App\Filament\Teacher\Resources\PositiveBehaviourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPositiveBehaviours extends ListRecords
{
    protected static string $resource = PositiveBehaviourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
