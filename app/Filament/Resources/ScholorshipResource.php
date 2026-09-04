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

    protected static ?string $navigationGroup = 'Discipline & ECA';

    protected static ?string $navigationLabel = 'Scholarships';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Scholarship Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Scholarship Title')
                            ->placeholder('e.g. Merit Scholarship 2024')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount (NPR / $)')
                            ->prefix('Rs.')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('batch_year_id')
                            ->label('Academic Session')
                            ->relationship('batchYear', 'batch')
                            ->default(fn () => \App\Models\BatchYear::where('is_active', true)->value('id')),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive / Closed',
                            ])
                            ->default('active')
                            ->native(false),
                        Forms\Components\RichEditor::make('criteria')
                            ->label('Eligibility & Selection Criteria')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Scholarship Beneficiaries (Students)')
                    ->schema([
                        CheckboxList::make('students')
                            ->columnSpan('full')
                            ->columns(4)
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
                    ->label('Scholarship')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('NPR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batchYear.batch')
                    ->label('Session')
                    ->badge()
                    ->color('primary')
                    ->placeholder('General'),
                Tables\Columns\TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Beneficiaries')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
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
            'index' => Pages\ListScholorships::route('/'),
            'create' => Pages\CreateScholorship::route('/create'),
            'view' => Pages\ViewScholorship::route('/{record}'),
            'edit' => Pages\EditScholorship::route('/{record}/edit'),
        ];
    }
}
