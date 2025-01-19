<?php

namespace App\Filament\Teacher\Resources\SectionResource\Pages;

use App\Filament\Teacher\Resources\SectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSection extends ViewRecord
{
    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
