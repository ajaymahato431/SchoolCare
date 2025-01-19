<?php

namespace App\Filament\Teacher\Resources\DailyAttendanceResource\Pages;

use App\Filament\Teacher\Resources\DailyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyAttendances extends ListRecords
{
    protected static string $resource = DailyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
