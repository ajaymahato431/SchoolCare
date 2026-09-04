<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Administration & Users';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Teachers awaiting verification';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Faculty Account & Credentials')
                    ->description('Access credentials and account status')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('name')
                            ->label('Faculty Full Name')
                            ->placeholder('e.g. Dr. Jane Smith')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Institutional / Primary Email')
                            ->email()
                            ->placeholder('teacher@schoolcare.edu')
                            ->required()
                            ->unique(Teacher::class, 'email', ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('status')
                            ->label('Account Status')
                            ->options([
                                'approved' => 'Approved',
                                'pending' => 'Pending Verification',
                                'rejected' => 'Suspended / Rejected',
                            ])
                            ->default('approved')
                            ->native(false)
                            ->required(),

                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->placeholder('••••••••')
                            ->maxLength(255)
                            ->helperText('Leave blank on edit to preserve current password.'),
                    ])
                    ->columns(2),

                Section::make('Professional & Contact Details')
                    ->description('Contact info, assigned primary subject, and location')
                    ->icon('heroicon-o-briefcase')
                    ->relationship('teacherDetails')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Contact Phone')
                            ->tel()
                            ->placeholder('98XXXXXXXX')
                            ->maxLength(20),

                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other',
                            ])
                            ->native(false),

                        Select::make('subject_id')
                            ->label('Specialization / Primary Subject')
                            ->relationship('subject', 'subject')
                            ->searchable()
                            ->preload(),

                        Select::make('municipality_id')
                            ->label('Municipality')
                            ->relationship('municipality', 'municipality')
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('ward_id')
                            ->label('Ward')
                            ->options(function (Get $get) {
                                $municipalityId = $get('municipality_id');
                                if (!$municipalityId) {
                                    return Ward::pluck('ward', 'id');
                                }
                                return Ward::where('municipality_id', $municipalityId)->pluck('ward', 'id');
                            })
                            ->searchable()
                            ->preload(),

                        TextInput::make('address')
                            ->label('Residential Address')
                            ->placeholder('e.g. Kathmandu, Ward 3'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Faculty Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('teacherDetails.subject.subject')
                    ->label('Primary Subject')
                    ->badge()
                    ->color('primary')
                    ->placeholder('General'),

                TextColumn::make('teacherDetails.phone')
                    ->label('Phone')
                    ->icon('heroicon-m-phone')
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'approved',
                        'heroicon-m-clock' => 'pending',
                        'heroicon-m-x-circle' => 'rejected',
                    ]),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending Verification',
                        'rejected' => 'Suspended / Rejected',
                    ]),

                SelectFilter::make('subject')
                    ->relationship('teacherDetails.subject', 'subject')
                    ->label('Filter by Subject'),
            ])
            ->actions([
                // 1-Click Instant Approve
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Teacher $record): bool => $record->status !== 'approved')
                    ->action(function (Teacher $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Teacher Approved')
                            ->body("{$record->name} account is now active.")
                            ->success()
                            ->send();
                    }),

                // 1-Click Instant Reject
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Teacher $record): bool => $record->status !== 'rejected')
                    ->action(function (Teacher $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Teacher Rejected')
                            ->body("{$record->name} account rejected/suspended.")
                            ->danger()
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('bulkApprove')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'approved']);
                            Notification::make()
                                ->title('Teachers Approved')
                                ->body(count($records) . ' teachers approved successfully.')
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('bulkReject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'rejected']);
                            Notification::make()
                                ->title('Teachers Rejected')
                                ->body(count($records) . ' teachers rejected.')
                                ->danger()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
