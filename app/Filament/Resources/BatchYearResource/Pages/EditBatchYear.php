<?php

namespace App\Filament\Resources\BatchYearResource\Pages;

use App\Filament\Resources\BatchYearResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchYear extends EditRecord
{
    protected static string $resource = BatchYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
