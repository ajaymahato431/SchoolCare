<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScholorshipResource\Pages;
use App\Filament\Resources\ScholorshipResource\RelationManagers;
use App\Models\Scholorship;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScholorshipResource extends Resource
{
    protected static ?string $model = Scholorship::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Tracking';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Scholorship Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        Forms\Components\RichEditor::make('criteria')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('year')
                            ->label('Distributed Year')

                    ])->columns(2),

                Section::make('Scholorship Holder Students')
                    ->schema([
                        CheckboxList::make('students')
                            ->columnSpan('full')
                            ->columns(5)
                            ->bulkToggleable()
                            ->searchable()
                            ->relationship('students', 'name'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->date()
                    ->label('Distributed Year')
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
            'index' => Pages\ListScholorships::route('/'),
            'create' => Pages\CreateScholorship::route('/create'),
            'view' => Pages\ViewScholorship::route('/{record}'),
            'edit' => Pages\EditScholorship::route('/{record}/edit'),
        ];
    }
}
