<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\StudentResource\Pages;
use App\Filament\Student\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Profile Section';
    protected static ?string $navigationLabel = 'Profile';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student Information')
                    ->schema([
                        Fieldset::make('Account Details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                DateTimePicker::make('email_verified_at')
                                    ->label('Email Verified At'),

                                TextInput::make('password')
                                    ->password()
                                    ->hiddenOn('edit')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Personal Details')
                    ->schema([
                        Repeater::make('student_details')
                            ->relationship('studentDetails')
                            ->label('')
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Phone')
                                    ->maxLength(15)
                                    ->nullable(),

                                TextInput::make('address')
                                    ->label('Address')
                                    ->nullable(),

                                Select::make('gender')
                                    ->label('Gender')
                                    ->options([
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                        'Other' => 'Other',
                                    ])
                                    ->nullable(),

                                Select::make('municipality_id')
                                    ->label('Municipality')
                                    ->relationship('municipalities', 'municipality')
                                    ->nullable(),

                                Select::make('ward_id')
                                    ->label('Ward')
                                    ->relationship('wards', 'ward')
                                    ->nullable(),

                                TextInput::make('blood_group')
                                    ->label('Blood Group')
                                    ->nullable(),
                            ])
                            ->defaultItems(1)
                            ->columns(3)
                            ->columnSpanFull()
                            ->deletable(false)
                            ->disableItemCreation(),
                    ]),


                Section::make('Class Mapping')
                    ->schema([
                        Repeater::make('class_mappings')
                            ->relationship('classMappings')
                            ->label('')
                            ->schema([
                                Select::make('grade_id')
                                    ->required()
                                    ->relationship('grades', 'grade'),
                                Select::make('section_id')
                                    ->required()
                                    ->relationship('sections', 'section'),
                                DatePicker::make('start_date'),
                                DatePicker::make('end_date'),
                            ])
                            ->defaultItems(1)
                            ->columns(3)
                            ->columnSpanFull()
                            ->deletable(false)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $student = Auth::user();
        $studentId = $student->id;
        return $table
            ->modifyQueryUsing(function (Builder $query) use ($studentId) {
                $query->where('id', $studentId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('studentDetails.phone')
                    ->searchable()
                    ->label('Phone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('studentDetails.address')
                    ->searchable()
                    ->label('Address')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                    })
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
                Tables\Actions\Action::make('Download Report')
                    ->url(fn($record) => route('studentReport.pdf', $record->id))
                    ->openUrlInNewTab()
                    ->label('Download Report')
                    ->icon('heroicon-o-arrow-down-tray'),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/createeeeee'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/editttttt'),
        ];
    }
}
