<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\TeacherResource\Pages;
use App\Filament\Teacher\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
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

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User Section';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Teacher Information')
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
                                        'rejected' => 'Approved',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Personal Details')
                    ->schema([
                        Repeater::make('teacher_details')
                            ->relationship('teacherDetails')
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

                                Select::make('subject_id')
                                    ->label('Subject')
                                    ->relationship('subjects', 'subject')
                                    ->nullable(),
                            ])
                            ->defaultItems(1)
                            ->columns(3)
                            ->columnSpanFull()
                            ->deletable(false)
                            ->disableItemCreation(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacherDetails.phone')
                    ->searchable()
                    ->label('Phone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacherDetails.address')
                    ->searchable()
                    ->label('Address')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'reject' => 'Reject',
                        'pending' => 'Pending',
                    ]),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view' => Pages\ViewTeacher::route('/{record}'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
