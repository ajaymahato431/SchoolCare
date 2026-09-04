<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\MarkEntryResource\Pages;
use App\Filament\Teacher\Resources\MarkEntryResource\RelationManagers;
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

    protected static ?string $navigationGroup = 'Classroom & Teaching';

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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batchYears.batch')
                    ->label('Session')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('students.name')
                    ->label('Student')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('grades.grade')
                    ->label('Grade')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subjects.subject')
                    ->label('Subject')
                    ->sortable(),

                Tables\Columns\TextColumn::make('examTypes.exam_type')
                    ->label('Exam Term')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                // In-table quick editable marks column!
                Tables\Columns\TextInputColumn::make('marks_obtained')
                    ->label('Marks (Quick Edit)')
                    ->rules(['numeric', 'min:0'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_marks')
                    ->label('Max')
                    ->numeric(),

                Tables\Columns\TextColumn::make('percentage')
                    ->label('Score')
                    ->state(fn (MarkEntry $record): string => ($record->percentage ?? 0) . '%')
                    ->badge()
                    ->color(fn (MarkEntry $record): string => ($record->is_pass ?? false) ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('remarks')
                    ->label('Remarks')
                    ->limit(20)
                    ->placeholder('—'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('bulkClassEntry')
                    ->label('⚡ Record Class Marks in Bulk')
                    ->icon('heroicon-o-bolt')
                    ->color('primary')
                    ->form([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('batch_year_id')
                                ->label('Session')
                                ->relationship('batchYears', 'batch')
                                ->default(fn () => BatchYear::where('is_active', true)->value('id'))
                                ->required(),

                            Forms\Components\Select::make('grade_id')
                                ->label('Grade')
                                ->relationship('grades', 'grade')
                                ->required(),

                            Forms\Components\Select::make('section_id')
                                ->label('Section')
                                ->options(\App\Models\Section::pluck('section', 'id'))
                                ->required(),
                        ]),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('exam_type_id')
                                ->label('Exam Term')
                                ->relationship('examTypes', 'exam_type')
                                ->required(),

                            Forms\Components\Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subjects', 'subject')
                                ->required(),
                        ]),

                        Forms\Components\Repeater::make('student_marks')
                            ->label('Student Scores')
                            ->schema([
                                Forms\Components\Select::make('student_id')
                                    ->label('Student')
                                    ->relationship('students', 'name')
                                    ->required(),

                                Forms\Components\TextInput::make('marks_obtained')
                                    ->label('Marks Obtained')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('remarks')
                                    ->label('Remarks')
                                    ->placeholder('Optional notes'),
                            ])
                            ->columns(3)
                            ->addActionLabel('+ Add Student Score')
                            ->collapsible(),
                    ])
                    ->action(function (array $data) {
                        $teacherId = Auth::id() ?: 1;
                        $savedCount = 0;

                        if (!empty($data['student_marks'])) {
                            foreach ($data['student_marks'] as $row) {
                                if (!empty($row['student_id']) && isset($row['marks_obtained'])) {
                                    MarkEntry::updateOrCreate(
                                        [
                                            'student_id' => $row['student_id'],
                                            'grade_id' => $data['grade_id'],
                                            'exam_type_id' => $data['exam_type_id'],
                                            'subject_id' => $data['subject_id'],
                                            'batch_year_id' => $data['batch_year_id'],
                                        ],
                                        [
                                            'teacher_id' => $teacherId,
                                            'marks_obtained' => $row['marks_obtained'],
                                            'full_marks' => 100,
                                            'pass_marks' => 40,
                                            'remarks' => $row['remarks'] ?? null,
                                        ]
                                    );
                                    $savedCount++;
                                }
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Class Marks Recorded')
                            ->body("Successfully entered marks for {$savedCount} students.")
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                SelectFilter::make('batch_year_id')
                    ->label('Batch Year')
                    ->relationship('batchYears', 'batch')
                    ->preload(),

                SelectFilter::make('grade_id')
                    ->label('Grade')
                    ->relationship('grades', 'grade')
                    ->preload(),

                SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->relationship('subjects', 'subject')
                    ->preload(),

                SelectFilter::make('exam_type_id')
                    ->label('Exam Type')
                    ->relationship('examTypes', 'exam_type')
                    ->preload(),
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
            'index' => Pages\ListMarkEntries::route('/'),
            'create' => Pages\CreateMarkEntry::route('/create'),
            'view' => Pages\ViewMarkEntry::route('/{record}'),
            'edit' => Pages\EditMarkEntry::route('/{record}/edit'),
        ];
    }
}
