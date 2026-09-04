<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Student Affairs';

    protected static ?string $navigationLabel = 'Homework & Assignments';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Assignment Specifications')
                    ->description('Define homework/project requirements, due dates, and marks')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Assignment Title')
                                ->placeholder('e.g. Chapter 4 Trigonometry Problem Set')
                                ->required()
                                ->maxLength(255),

                            Select::make('teacher_id')
                                ->label('Assigning Faculty')
                                ->relationship('teacher', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),

                        Grid::make(3)->schema([
                            Select::make('grade_id')
                                ->label('Target Grade')
                                ->relationship('grade', 'grade')
                                ->required()
                                ->reactive(),

                            Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'subject')
                                ->required(),

                            TextInput::make('max_marks')
                                ->label('Max Marks')
                                ->numeric()
                                ->default(100)
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            DatePicker::make('assignment_date')
                                ->label('Assigned Date')
                                ->default(now())
                                ->required(),

                            DatePicker::make('submission_date')
                                ->label('Due Date / Deadline')
                                ->default(now()->addDays(7))
                                ->required(),
                        ]),

                        Textarea::make('description')
                            ->label('Task Instructions & Guidelines')
                            ->placeholder('Describe the deliverables, questions to solve, or reading material...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Assigned Students')
                    ->description('Select which students in this grade receive this assignment')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        CheckboxList::make('students')
                            ->relationship('students', 'name')
                            ->columns(4)
                            ->bulkToggleable()
                            ->searchable()
                            ->options(function (callable $get) {
                                $selectedGradeId = $get('grade_id');
                                if (!$selectedGradeId) {
                                    return [];
                                }

                                return Student::query()
                                    ->whereHas('classMappings', function ($query) use ($selectedGradeId) {
                                        $query->where('grade_id', $selectedGradeId);
                                    })
                                    ->pluck('name', 'id');
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Assignment')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('grade.grade')
                    ->label('Grade')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('subject.subject')
                    ->label('Subject')
                    ->sortable(),

                TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->sortable(),

                TextColumn::make('submission_date')
                    ->label('Due Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Assigned')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('grade_id')
                    ->label('Grade')
                    ->relationship('grade', 'grade'),

                SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->relationship('subject', 'subject'),
            ])
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
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'view' => Pages\ViewAssignment::route('/{record}'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}
