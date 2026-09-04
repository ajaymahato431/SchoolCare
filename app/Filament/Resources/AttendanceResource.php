<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\Section as SchoolSection;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Student Affairs';

    protected static ?string $navigationLabel = 'Attendance Records';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Attendance Session')
                    ->schema([
                        TextInput::make('name')
                            ->label('Roll Call Title')
                            ->default(fn () => 'Daily Roll Call - ' . now()->format('M d, Y'))
                            ->required(),

                        Select::make('teacher_id')
                            ->label('Recorded By (Faculty)')
                            ->relationship('teachers', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('grade_id')
                            ->label('Grade / Class')
                            ->relationship('grades', 'grade')
                            ->required()
                            ->reactive(),

                        Select::make('section_id')
                            ->label('Section')
                            ->options(SchoolSection::pluck('section', 'id'))
                            ->required()
                            ->reactive(),

                        DatePicker::make('attendance_date')
                            ->label('Attendance Date')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Students Roll Call')
                    ->description('Check present students (Use Select All for rapid 1-click marking)')
                    ->schema([
                        CheckboxList::make('students')
                            ->columnSpan('full')
                            ->columns(4)
                            ->bulkToggleable()
                            ->searchable()
                            ->label('Students Present')
                            ->relationship('students', 'name')
                            ->options(function (callable $get) {
                                $selectedGradeId = $get('grade_id');
                                $selectedSectionId = $get('section_id');

                                if (!$selectedGradeId) {
                                    return [];
                                }

                                return Student::query()
                                    ->whereHas('classMappings', function ($query) use ($selectedGradeId, $selectedSectionId) {
                                        $query->where('grade_id', $selectedGradeId);
                                        if ($selectedSectionId) {
                                            $query->where('section_id', $selectedSectionId);
                                        }
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
                TextColumn::make('attendance_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Session')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('grades.grade')
                    ->label('Grade')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('section.section')
                    ->label('Section')
                    ->badge()
                    ->color('gray')
                    ->placeholder('All'),

                TextColumn::make('teachers.name')
                    ->label('Recorded By')
                    ->icon('heroicon-m-user')
                    ->sortable(),

                TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Present Count')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                SelectFilter::make('grade_id')
                    ->label('Grade')
                    ->relationship('grades', 'grade'),

                SelectFilter::make('teacher_id')
                    ->label('Teacher')
                    ->relationship('teachers', 'name'),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
