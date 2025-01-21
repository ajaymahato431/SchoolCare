<?php

namespace App\Filament\Student\Resources\MarkEntryResource\Pages;

use App\Filament\Student\Resources\MarkEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarkEntry extends EditRecord
{
    protected static string $resource = MarkEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
