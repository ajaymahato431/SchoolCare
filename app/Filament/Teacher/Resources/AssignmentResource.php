<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\AssignmentResource\Pages;
use App\Filament\Teacher\Resources\AssignmentResource\RelationManagers;
use App\Models\Assignment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Tracking';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Assignment Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('teacher_id')
                            ->required()
                            ->relationship('teachers', 'name'),
                        Forms\Components\Select::make('grade_id')
                            ->required()
                            ->relationship('grades', 'grade')
                            ->reactive(),
                        Forms\Components\Select::make('subject_id')
                            ->required()
                            ->relationship('subjects', 'subject'),
                        Forms\Components\DatePicker::make('assignment_date')
                            ->required(),
                        Forms\Components\DatePicker::make('submission_date')
                            ->default(now())
                            ->required(),
                    ])->columns(3),

                Section::make('Students')
                    ->schema([
                        CheckboxList::make('students')
                            ->columnSpan('full')
                            ->columns(5)
                            ->bulkToggleable()
                            ->searchable()
                            ->relationship('students', 'name')
                            ->options(function (callable $get) {
                                $selectedGradeId = $get('grade_id'); // Get the selected grade
                                if (!$selectedGradeId) {
                                    return [];
                                }

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
                            }),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teachers.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grades.grade')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subjects.subject')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('submission_date')
                    ->date()
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
                SelectFilter::make('grades')
                    ->label('Grade')
                    ->relationship('grades', 'grade') // Assuming a relationship 'grades' exists
                    ->options(function () {
                        return \App\Models\Grade::pluck('grade', 'id'); // Adjust based on your Grade model
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(),

                // Filter for Teachers
                SelectFilter::make('teachers')
                    ->label('Teacher')
                    ->relationship('teachers', 'name') // Assuming a relationship 'teachers' exists
                    ->options(function () {
                        return \App\Models\Teacher::pluck('name', 'id'); // Adjust based on your Teacher model
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(),

                // Date Range Filter
                Filter::make('assignment_date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Assignment Start Date')
                            ->placeholder('Select start date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Assignment End Date')
                            ->placeholder('Select end date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn($query, $date) => $query->whereDate('assignment_date', '>=', Carbon::parse($date)))
                            ->when($data['end_date'], fn($query, $date) => $query->whereDate('assignment_date', '<=', Carbon::parse($date)));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['start_date'] ?? null) {
                            $indicators['start_date'] = 'From: ' . Carbon::parse($data['start_date'])->toFormattedDateString();
                        }
                        if ($data['end_date'] ?? null) {
                            $indicators['end_date'] = 'Until: ' . Carbon::parse($data['end_date'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                Filter::make('submission_date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Submission Start Date')
                            ->placeholder('Select start date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Submission End Date')
                            ->placeholder('Select end date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn($query, $date) => $query->whereDate('submission_date', '>=', Carbon::parse($date)))
                            ->when($data['end_date'], fn($query, $date) => $query->whereDate('submission_date', '<=', Carbon::parse($date)));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['start_date'] ?? null) {
                            $indicators['start_date'] = 'From: ' . Carbon::parse($data['start_date'])->toFormattedDateString();
                        }
                        if ($data['end_date'] ?? null) {
                            $indicators['end_date'] = 'Until: ' . Carbon::parse($data['end_date'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->filtersFormColumns(2)
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
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'view' => Pages\ViewAssignment::route('/{record}'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}
