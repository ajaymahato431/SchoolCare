<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TeacherRequest extends BaseWidget
{
    protected static ?string $heading = 'Pending Teacher Registrations';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Teacher::query()->where('status', 'pending')->latest()
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Teacher Name')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('teacherDetails.phone')
                    ->label('Contact')
                    ->placeholder('N/A'),

                TextColumn::make('teacherDetails.subject.subject')
                    ->label('Subject Specialization')
                    ->badge()
                    ->color('warning')
                    ->placeholder('General'),

                TextColumn::make('created_at')
                    ->label('Applied')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->action(function (Teacher $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Teacher Approved')
                            ->body("{$record->name} is now approved to access the Teacher Portal.")
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Faculty Application')
                    ->modalDescription('Are you sure you want to reject this faculty registration?')
                    ->action(function (Teacher $record) {
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
                    ->url(fn (Teacher $record): string => TeacherResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('No Pending Teacher Applications')
            ->emptyStateDescription('All faculty registration requests have been reviewed.')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->paginated([5, 10]);
    }
}
