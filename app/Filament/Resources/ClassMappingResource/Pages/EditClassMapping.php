<?php

namespace App\Filament\Resources\ClassMappingResource\Pages;

use App\Filament\Resources\ClassMappingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassMapping extends EditRecord
{
    protected static string $resource = ClassMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
