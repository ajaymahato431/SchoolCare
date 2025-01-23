<?php

namespace App\Filament\Resources\ClassMappingResource\Pages;

use App\Filament\Resources\ClassMappingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClassMapping extends ViewRecord
{
    protected static string $resource = ClassMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
