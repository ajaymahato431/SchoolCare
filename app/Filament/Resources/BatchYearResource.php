<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchYearResource\Pages;
use App\Filament\Resources\BatchYearResource\RelationManagers;
use App\Models\BatchYear;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchYearResource extends Resource
{
    protected static ?string $model = BatchYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Academic Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('batch')
                    ->label('Academic Batch / Year')
                    ->placeholder('e.g. 2024/25 or 2081/82')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('Current Active Session')
                    ->helperText('Setting this to active marks it as the default session across SchoolCare.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch')
                    ->label('Academic Session')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active Session')
                    ->boolean(),

                Tables\Columns\TextColumn::make('class_mappings_count')
                    ->counts('classMappings')
                    ->label('Enrolled Students')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListBatchYears::route('/'),
            'create' => Pages\CreateBatchYear::route('/create'),
            'view' => Pages\ViewBatchYear::route('/{record}'),
            'edit' => Pages\EditBatchYear::route('/{record}/edit'),
        ];
    }
}
