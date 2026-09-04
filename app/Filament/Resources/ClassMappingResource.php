<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassMappingResource\Pages;
use App\Filament\Resources\ClassMappingResource\RelationManagers;
use App\Models\ClassMapping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassMappingResource extends Resource
{
    protected static ?string $model = ClassMapping::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('batch_year_id')
                    ->label('Academic Session')
                    ->relationship('batchYear', 'batch')
                    ->default(fn () => \App\Models\BatchYear::where('is_active', true)->value('id'))
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->relationship('students', 'name'),
                Forms\Components\Select::make('grade_id')
                    ->label('Grade / Class')
                    ->required()
                    ->relationship('grades', 'grade'),
                Forms\Components\Select::make('section_id')
                    ->label('Section')
                    ->required()
                    ->relationship('sections', 'section'),
                Forms\Components\TextInput::make('roll_no')
                    ->label('Roll Number')
                    ->placeholder('e.g. 05')
                    ->maxLength(30),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Enrollment Date')
                    ->default(now()),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Completion Date'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batchYear.batch')
                    ->label('Session')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('students.name')
                    ->label('Student')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grades.grade')
                    ->label('Grade')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sections.section')
                    ->label('Section')
                    ->sortable(),
                Tables\Columns\TextColumn::make('roll_no')
                    ->label('Roll No')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
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
            'index' => Pages\ListClassMappings::route('/'),
            'create' => Pages\CreateClassMapping::route('/create'),
            'view' => Pages\ViewClassMapping::route('/{record}'),
            'edit' => Pages\EditClassMapping::route('/{record}/edit'),
        ];
    }
}
