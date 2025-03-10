<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ActivitiesResource\Pages;
use App\Filament\Student\Resources\ActivitiesResource\RelationManagers;
use App\Filament\Student\Resources\ActivitiesResource\RelationManagers\StudentParticipationsRelationManager;
use App\Models\Activities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ActivitiesResource extends Resource
{
    protected static ?string $model = Activities::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Tracking';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('organizer')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        $student = Auth::user();
        $studentId = $student->id;
        return $table
            ->modifyQueryUsing(function (Builder $query) use ($studentId) {
                $query->whereHas('studentParticipations', function (Builder $query) use ($studentId) {
                    $query->where('student_id', $studentId);
                });
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('studentParticipations.obtained_rank')
                    ->label('Your Rank')
                    ->getStateUsing(function ($record) {
                        $studentId = Auth::guard('students')->id(); // Get the authenticated student's ID
                        $participation = $record->studentParticipations->firstWhere('student_id', $studentId);

                        return $participation ? $participation->obtained_rank : 'N/A'; // Display rank or 'N/A' if not found
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizer')
                    ->sortable()
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->limit(30)

                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivities::route('/createeee'),
            'view' => Pages\ViewActivities::route('/{record}'),
            'edit' => Pages\EditActivities::route('/{record}/edittttt'),

        ];
    }
}
