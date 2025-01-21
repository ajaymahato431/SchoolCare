<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\MarkEntryResource\Pages;
use App\Filament\Student\Resources\MarkEntryResource\RelationManagers;
use App\Models\BatchYear;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\MarkEntry;
use App\Models\Subject;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MarkEntryResource extends Resource
{
    protected static ?string $model = MarkEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    protected static ?string $navigationGroup = 'Tracking';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mark Entry Information')
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
                        Forms\Components\Select::make('exam_type_id')
                            ->required()
                            ->relationship('examTypes', 'exam_type'),
                        Forms\Components\Select::make('subject_id')
                            ->required()
                            ->relationship('subjects', 'subject'),
                        Forms\Components\TextInput::make('marks_obtained')
                            ->numeric()
                            ->default(null),
                        Forms\Components\TextInput::make('remarks')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('teacher_id')
                            ->label('Entered By')
                            ->required()
                            ->default(Auth::user()->id)
                            ->readOnly(),
                        Forms\Components\TextInput::make('batch_year_id')
                            ->label('Batch Year')
                            ->required()
                            ->default(function () {
                                $lastBatchYear = BatchYear::orderBy('id', 'desc')->first();
                                return $lastBatchYear ? $lastBatchYear->id : null;
                            })
                            ->readOnly(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        $student = Auth::user();
        $studentId = $student->id;
        return $table
            ->modifyQueryUsing(function (Builder $query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('students.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grades.grade')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('examTypes.exam_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subjects.subject')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marks_obtained')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('batchYears.batch')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Students.name')
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
                // Filter for Grades
                SelectFilter::make('grade_id')
                    ->label('Grade')
                    ->relationship('grades', 'grade') // Assuming a relationship 'grades' exists
                    ->options(function () {
                        return Grade::pluck('grade', 'id'); // Fetch grade options
                    })
                    ->searchable()
                    ->preload(),

                // Filter for Exam Types
                SelectFilter::make('exam_type_id')
                    ->label('Exam Type')
                    ->relationship('examTypes', 'exam_type') // Assuming a relationship 'examTypes' exists
                    ->options(function () {
                        return ExamType::pluck('exam_type', 'id'); // Fetch exam type options
                    })
                    ->searchable()
                    ->preload(),

                // Filter for Subjects
                SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->relationship('subjects', 'subject') // Assuming a relationship 'subjects' exists
                    ->options(function () {
                        return Subject::pluck('subject', 'id'); // Fetch subject options
                    })
                    ->searchable()
                    ->preload(),

                // Filter for Batch Years
                SelectFilter::make('batch_year_id')
                    ->label('Batch Year')
                    ->relationship('batchYears', 'batch') // Assuming a relationship 'batchYears' exists
                    ->options(function () {
                        return BatchYear::pluck('batch', 'id'); // Fetch batch year options
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListMarkEntries::route('/'),
            'create' => Pages\CreateMarkEntry::route('/createeeeeee'),
            'view' => Pages\ViewMarkEntry::route('/{record}'),
            'edit' => Pages\EditMarkEntry::route('/{record}/editttttt'),
        ];
    }
}
