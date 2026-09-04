<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentBehaviorResource\Pages;
use App\Models\Student;
use App\Models\StudentBehavior;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentBehaviorResource extends Resource
{
    protected static ?string $model = StudentBehavior::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationGroup = 'Discipline & ECA';

    protected static ?string $navigationLabel = 'Student Behaviors';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Incident & Behavioral Record')
                    ->description('Log positive merits, recognitions, or disciplinary infractions')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('student_id')
                                ->label('Student')
                                ->relationship('student', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('grade_id')
                                ->label('Grade / Class')
                                ->relationship('grade', 'grade')
                                ->required(),

                            Select::make('teacher_id')
                                ->label('Reported By Faculty')
                                ->relationship('teacher', 'name')
                                ->searchable()
                                ->preload(),
                        ]),

                        Grid::make(4)->schema([
                            Select::make('type')
                                ->label('Behavior Type')
                                ->options([
                                    'positive' => 'Positive (Merit / Achievement)',
                                    'negative' => 'Negative (Demerit / Infraction)',
                                ])
                                ->default('positive')
                                ->required()
                                ->native(false),

                            Select::make('category')
                                ->label('Category')
                                ->options([
                                    'Academic' => 'Academic Excellence',
                                    'Leadership' => 'Leadership & Initiative',
                                    'Discipline' => 'Discipline & Conduct',
                                    'Punctuality' => 'Punctuality & Attendance',
                                    'Bullying' => 'Bullying / Harassment',
                                    'Kindness' => 'Kindness & Community Support',
                                    'ECA' => 'Sports & Extracurricular',
                                ])
                                ->required()
                                ->native(false),

                            Select::make('severity')
                                ->label('Severity / Significance')
                                ->options([
                                    'minor' => 'Minor',
                                    'moderate' => 'Moderate',
                                    'major' => 'Major',
                                    'exceptional' => 'Exceptional / Critical',
                                ])
                                ->default('minor')
                                ->native(false),

                            TextInput::make('points')
                                ->label('Merit / Demerit Points')
                                ->numeric()
                                ->placeholder('e.g. +5 or -5')
                                ->default(0),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Incident Title / Summary')
                                ->placeholder('e.g. Guided classmates through science project')
                                ->required()
                                ->maxLength(255),

                            DatePicker::make('event_date')
                                ->label('Date of Event')
                                ->default(now())
                                ->required(),
                        ]),

                        TextInput::make('action_taken')
                            ->label('Action Taken / Recognition Given')
                            ->placeholder('e.g. Awarded Certificate, Parent Meeting Held, Counseling Recommended')
                            ->maxLength(255),

                        RichEditor::make('description')
                            ->label('Detailed Report & Observations')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('student.name')
                    ->label('Student')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('grade.grade')
                    ->label('Grade')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'success' => 'positive',
                        'danger' => 'negative',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'positive' ? '★ Merit' : '⚠ Demerit'),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Summary')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('points')
                    ->label('Points')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn (int $state): string => $state > 0 ? "+{$state}" : (string) $state),

                TextColumn::make('action_taken')
                    ->label('Action Taken')
                    ->placeholder('None')
                    ->limit(25),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'positive' => 'Positive (Merit)',
                        'negative' => 'Negative (Demerit)',
                    ]),

                SelectFilter::make('category')
                    ->options([
                        'Academic' => 'Academic Excellence',
                        'Leadership' => 'Leadership',
                        'Discipline' => 'Discipline',
                        'Punctuality' => 'Punctuality',
                        'Bullying' => 'Bullying',
                        'Kindness' => 'Kindness',
                    ]),
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
            'index' => Pages\ListStudentBehaviors::route('/'),
            'create' => Pages\CreateStudentBehavior::route('/create'),
            'view' => Pages\ViewStudentBehavior::route('/{record}'),
            'edit' => Pages\EditStudentBehavior::route('/{record}/edit'),
        ];
    }
}
