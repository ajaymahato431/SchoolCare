<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StudentRequest extends BaseWidget
{
    protected static ?string $heading = 'Pending Student Registrations';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()->where('status', 'pending')->latest()
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('studentDetails.phone')
                    ->label('Contact')
                    ->placeholder('N/A'),

                TextColumn::make('latestClassMapping.grade.grade')
                    ->label('Grade')
                    ->formatStateUsing(fn ($state) => $state ? "Grade {$state}" : 'Unassigned')
                    ->badge()
                    ->color('info')
                    ->placeholder('Unassigned'),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->action(function (Student $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Student Approved')
                            ->body("{$record->name} is now an approved student.")
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Student Application')
                    ->modalDescription('Are you sure you want to reject this student registration?')
                    ->action(function (Student $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Application Rejected')
                            ->body("{$record->name}'s registration was rejected.")
                            ->danger()
                            ->send();
                    }),

                Action::make('view')
                    ->label('View Profile')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Student $record): string => StudentResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('No Pending Student Applications')
            ->emptyStateDescription('All student registration requests have been reviewed.')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->paginated([5, 10]);
    }
}
