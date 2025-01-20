<?php

namespace App\Filament\Student\Resources\ScholorshipResource\Pages;

use App\Filament\Student\Resources\ScholorshipResource;
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
