<?php

namespace App\Filament\Resources\ScholorshipResource\Pages;

use App\Filament\Resources\ScholorshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScholorships extends ListRecords
{
    protected static string $resource = ScholorshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
