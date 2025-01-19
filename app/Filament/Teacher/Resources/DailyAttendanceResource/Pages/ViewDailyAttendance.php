<?php

namespace App\Filament\Teacher\Resources\DailyAttendanceResource\Pages;

use App\Filament\Teacher\Resources\DailyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyAttendance extends ViewRecord
{
    protected static string $resource = DailyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
