<?php

namespace App\Filament\Teacher\Resources\DailyAttendanceResource\Pages;

use App\Filament\Teacher\Resources\DailyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyAttendance extends EditRecord
{
    protected static string $resource = DailyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
