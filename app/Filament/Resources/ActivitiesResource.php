<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesResource\Pages;
use App\Models\Activities;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                Section::make('Activity Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Activity Title')
                            ->placeholder('e.g. Annual Inter-School Basketball Tournament')
                            ->required()
                            ->maxLength(255),

                        Select::make('category')
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

                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required(),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->required(),

                        TextInput::make('organizer')
                            ->label('Organizer / Host Club')
                            ->placeholder('e.g. Sports Club')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('address')
                            ->label('Venue / Location')
                            ->placeholder('e.g. School Gymnasium')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Event Description & Scope')
                            ->placeholder('Brief overview of the activity and student goals...')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Activity')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('organizer')
                    ->label('Organizer')
                    ->searchable(),

                TextColumn::make('student_participations_count')
                    ->counts('studentParticipations')
                    ->label('Participants')
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
