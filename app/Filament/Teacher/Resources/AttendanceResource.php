<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\AttendanceResource\Pages;
use App\Filament\Teacher\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
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

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationGroup = 'Tracking';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Attendance Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Select::make('teacher_id')
                            ->required()
                            ->relationship('teachers', 'name'),
                        Forms\Components\Select::make('grade_id')
                            ->required()
                            ->relationship('grades', 'grade')
                            ->label('Select Grade')
                            ->reactive(),
                        Forms\Components\DatePicker::make('attendance_date')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Students')
                    ->schema([
                        CheckboxList::make('student_id')
                            ->columnSpan('full')
                            ->columns(5)
                            ->bulkToggleable()
                            ->searchable()
                            ->label('Students')
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
                    ])
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
                Tables\Columns\TextColumn::make('attendance_date')
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
                Filter::make('attendance_date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->placeholder('Select start date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->placeholder('Select end date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn($query, $date) => $query->whereDate('attendance_date', '>=', Carbon::parse($date)))
                            ->when($data['end_date'], fn($query, $date) => $query->whereDate('attendance_date', '<=', Carbon::parse($date)));
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
            // ->filtersFormColumns(2)
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
