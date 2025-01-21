<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\PositiveBehaviourResource\Pages;
use App\Filament\Teacher\Resources\PositiveBehaviourResource\RelationManagers;
use App\Models\PositiveBehaviour;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositiveBehaviourResource extends Resource
{
    protected static ?string $model = PositiveBehaviour::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Tracking';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Behaviour Details')
                    ->schema([
                        Forms\Components\Select::make('grade_id')
                            ->required()
                            ->relationship('grades', 'grade') // Relationship for grades
                            ->label('Grade')
                            ->reactive(), // Make it reactive to trigger updates in dependent fields

                        Forms\Components\Select::make('student_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('students', 'name') // Relationship for students
                            ->label('Student')
                            ->options(function (callable $get) {
                                $selectedGradeId = $get('grade_id'); // Fetch the selected grade
                                if (!$selectedGradeId) {
                                    return []; // Return an empty array if no grade is selected
                                }

                                // Query students dynamically based on the selected grade
                                return \App\Models\Student::query()
                                    ->whereHas('classMappings', function ($query) use ($selectedGradeId) {
                                        $query->where('grade_id', $selectedGradeId)
                                            ->whereRaw('id = (
                        SELECT MAX(id)
                        FROM class_mappings cm
                        WHERE cm.student_id = class_mappings.student_id
                    )');
                                    })
                                    ->pluck('name', 'id'); // Return an array of student names and IDs
                            })
                            ->reactive(), // Make it reactive for better UX

                        Forms\Components\DatePicker::make('event_date')
                            ->default(now())
                            ->readOnly()
                            ->required(),
                    ])->columns(3),

                Section::make('Behaviour Description')
                    ->schema([
                        Forms\Components\RichEditor::make('report')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('students.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grades.grade')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositiveBehaviours::route('/'),
            'create' => Pages\CreatePositiveBehaviour::route('/create'),
            'view' => Pages\ViewPositiveBehaviour::route('/{record}'),
            'edit' => Pages\EditPositiveBehaviour::route('/{record}/edit'),
        ];
    }
}
