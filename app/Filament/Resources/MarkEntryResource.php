<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarkEntryResource\Pages;
use App\Models\BatchYear;
use App\Models\ClassMapping;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\MarkEntry;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MarkEntryResource extends Resource
{
    protected static ?string $model = MarkEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    protected static ?string $navigationGroup = 'Student Affairs';

    protected static ?string $navigationLabel = 'Mark Entries';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Assessment Information')
                    ->description('Record examination scores and evaluations for students')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('batch_year_id')
                                ->label('Academic Session')
                                ->relationship('batchYear', 'batch')
                                ->default(fn () => BatchYear::where('is_active', true)->value('id'))
                                ->required(),

                            Select::make('exam_type_id')
                                ->label('Exam / Assessment Term')
                                ->relationship('examType', 'exam_type')
                                ->required(),

                            Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'subject')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    if ($subject = Subject::find($state)) {
                                        $set('full_marks', $subject->full_marks);
                                        $set('pass_marks', $subject->pass_marks);
                                    }
                                }),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('grade_id')
                                ->label('Grade / Class')
                                ->relationship('grade', 'grade')
                                ->required()
                                ->reactive(),

                            Select::make('student_id')
                                ->label('Student')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options(function (Get $get) {
                                    $gradeId = $get('grade_id');
                                    if (!$gradeId) {
                                        return Student::pluck('name', 'id');
                                    }
                                    return Student::whereHas('classMappings', function ($q) use ($gradeId) {
                                        $q->where('grade_id', $gradeId);
                                    })->pluck('name', 'id');
                                }),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('marks_obtained')
                                ->label('Marks Obtained')
                                ->numeric()
                                ->required()
                                ->placeholder('e.g. 85.5'),

                            TextInput::make('full_marks')
                                ->label('Full Marks')
                                ->numeric()
                                ->default(100)
                                ->required(),

                            TextInput::make('pass_marks')
                                ->label('Pass Marks')
                                ->numeric()
                                ->default(40)
                                ->required(),
                        ]),

                        TextInput::make('remarks')
                            ->label('Teacher Remarks / Notes')
                            ->placeholder('e.g. Excellent presentation, thorough analysis')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batchYear.batch')
                    ->label('Session')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('student.name')
                    ->label('Student')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('grade.grade')
                    ->label('Grade')
                    ->sortable(),

                TextColumn::make('subject.subject')
                    ->label('Subject')
                    ->sortable(),

                TextColumn::make('examType.exam_type')
                    ->label('Exam Term')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                // Fast in-place editable mark cell!
                TextInputColumn::make('marks_obtained')
                    ->label('Marks (Quick Edit)')
                    ->rules(['numeric', 'min:0'])
                    ->sortable(),

                TextColumn::make('full_marks')
                    ->label('Max')
                    ->numeric(),

                TextColumn::make('percentage')
                    ->label('Score')
                    ->state(fn (MarkEntry $record): string => ($record->percentage ?? 0) . '%')
                    ->badge()
                    ->color(fn (MarkEntry $record): string => ($record->is_pass ?? false) ? 'success' : 'danger'),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->limit(20)
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('batch_year_id')
                    ->label('Session')
                    ->relationship('batchYear', 'batch'),

                SelectFilter::make('grade_id')
                    ->label('Grade')
                    ->relationship('grade', 'grade'),

                SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->relationship('subject', 'subject'),

                SelectFilter::make('exam_type_id')
                    ->label('Exam Term')
                    ->relationship('examType', 'exam_type'),
            ])
            ->headerActions([
                // 1-Click Mass Class Mark Entry Action!
                Action::make('bulkClassEntry')
                    ->label('⚡ Record Class Marks in Bulk')
                    ->icon('heroicon-o-bolt')
                    ->color('primary')
                    ->form([
                        Grid::make(3)->schema([
                            Select::make('batch_year_id')
                                ->label('Session')
                                ->relationship('batchYear', 'batch')
                                ->default(fn () => BatchYear::where('is_active', true)->value('id'))
                                ->required(),

                            Select::make('grade_id')
                                ->label('Grade')
                                ->relationship('grades', 'grade')
                                ->required()
                                ->reactive(),

                            Select::make('section_id')
                                ->label('Section')
                                ->relationship('sections', 'section')
                                ->required()
                                ->reactive(),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('exam_type_id')
                                ->label('Exam Term')
                                ->relationship('examType', 'exam_type')
                                ->required(),

                            Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'subject')
                                ->required(),
                        ]),

                        Repeater::make('student_marks')
                            ->label('Student Scores')
                            ->schema([
                                Select::make('student_id')
                                    ->label('Student')
                                    ->relationship('students', 'name')
                                    ->required(),

                                TextInput::make('marks_obtained')
                                    ->label('Marks Obtained')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('remarks')
                                    ->label('Remarks')
                                    ->placeholder('Optional notes'),
                            ])
                            ->columns(3)
                            ->addActionLabel('+ Add Student Score')
                            ->collapsible(),
                    ])
                    ->action(function (array $data) {
                        $teacherId = Auth::guard('teachers')->id() ?: Auth::guard('admins')->id() ?: 1;
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

                        Notification::make()
                            ->title('Class Marks Saved')
                            ->body("Successfully recorded marks for {$savedCount} students.")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
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
            'index' => Pages\ListMarkEntries::route('/'),
            'create' => Pages\CreateMarkEntry::route('/create'),
            'edit' => Pages\EditMarkEntry::route('/{record}/edit'),
        ];
    }
}
