<?php

namespace App\Filament\Teacher\Resources\DailyAttendanceResource\Pages;

use App\Filament\Teacher\Resources\DailyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyAttendance extends CreateRecord
{
    protected static string $resource = DailyAttendanceResource::class;
}
