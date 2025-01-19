<?php

namespace App\Filament\Teacher\Resources\ActivitiesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentParticipationsRelationManager extends RelationManager
{
    protected static string $relationship = 'studentParticipations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('activity_id')
                    ->required()
                    ->relationship('activities', 'name'),
                Forms\Components\Select::make('student_id')
                    ->required()
                    ->relationship('students', 'name'),
                Forms\Components\TextInput::make('obtained_rank')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student_id')
            ->columns([
                Tables\Columns\TextColumn::make('activities.name'),
                Tables\Columns\TextColumn::make('students.name'),
                Tables\Columns\TextColumn::make('obtained_rank'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
