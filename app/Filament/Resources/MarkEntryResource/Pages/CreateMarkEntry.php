<?php

namespace App\Filament\Resources\MarkEntryResource\Pages;

use App\Filament\Resources\MarkEntryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMarkEntry extends CreateRecord
{
    protected static string $resource = MarkEntryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['teacher_id'] = Auth::guard('teachers')->id() ?: Auth::guard('admins')->id() ?: 1;
        return $data;
    }
}
