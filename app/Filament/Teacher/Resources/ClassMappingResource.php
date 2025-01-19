<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\ClassMappingResource\Pages;
use App\Filament\Teacher\Resources\ClassMappingResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Setup';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->required()
                    ->relationship('students', 'name'),
                Forms\Components\Select::make('grade_id')
                    ->required()
                    ->relationship('grades', 'grade'),
                Forms\Components\Select::make('section_id')
                    ->required()
                    ->relationship('sections', 'section'),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('students.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grades.grade')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sections.section')
                    ->searchable()
                    ->sortable(),
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
