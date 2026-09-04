<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\ActivitiesResource\Pages;
use App\Filament\Teacher\Resources\ActivitiesResource\RelationManagers;
use App\Filament\Teacher\Resources\ActivitiesResource\RelationManagers\StudentParticipationsRelationManager;
use App\Models\Activities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivitiesResource extends Resource
{
    protected static ?string $model = Activities::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Discipline & ECA';

    protected static ?string $navigationLabel = 'ECA & Activities';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Activity Title')
                    ->placeholder('e.g. Annual Inter-School Basketball Tournament')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options([
                        'Sports' => 'Sports & Athletics',
                        'STEM' => 'STEM & Robotics',
                        'Arts' => 'Arts & Music',
                        'Debate' => 'Debate & Literature',
                        'Cultural' => 'Cultural & Drama',
                        'Other' => 'Other / Community Service',
                    ])
                    ->default('Sports')
                    ->native(false)
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('End Date')
                    ->required(),

                Forms\Components\TextInput::make('organizer')
                    ->label('Organizer / Host Club')
                    ->placeholder('e.g. Sports Club')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->label('Venue / Location')
                    ->placeholder('e.g. School Gymnasium')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Event Description & Scope')
                    ->placeholder('Brief overview of the activity and student goals...')
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Activity')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('organizer')
                    ->label('Organizer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('student_participations_count')
                    ->counts('studentParticipations')
                    ->label('Participants')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StudentParticipationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivities::route('/create'),
            'view' => Pages\ViewActivities::route('/{record}'),
            'edit' => Pages\EditActivities::route('/{record}/edit'),
        ];
    }
}
