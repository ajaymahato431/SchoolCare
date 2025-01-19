<?php

namespace App\Filament\Teacher\Resources\ScholorshipResource\Pages;

use App\Filament\Teacher\Resources\ScholorshipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScholorship extends EditRecord
{
    protected static string $resource = ScholorshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
