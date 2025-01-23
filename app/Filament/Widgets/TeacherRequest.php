<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;

class TeacherRequest extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Teacher::query()->where('status', 'pending') // Query only students with status 'pending'
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable(),

                SelectColumn::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'reject' => 'Reject',
                        'pending' => 'Pending',
                    ]),
            ])->paginationPageOptions([5]);
    }
}
