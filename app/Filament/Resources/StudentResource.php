<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Grade;
use App\Models\Section as SchoolSection;
use App\Models\Student;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
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

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Student Affairs';

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
        return 'Students awaiting approval';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Account Credentials & Status')
                    ->description('Basic login credentials and verification status')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->placeholder('e.g. John Doe')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('student@example.com')
                            ->required()
                            ->unique(Student::class, 'email', ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('status')
                            ->label('Admission Status')
                            ->options([
                                'approved' => 'Approved',
                                'pending' => 'Pending Review',
                                'rejected' => 'Rejected',
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
                            ->helperText('Leave blank on edit to preserve existing password.'),
                    ])
                    ->columns(2),

                Section::make('Personal Profile & Contact')
                    ->description('Contact, physical location, and health info')
                    ->icon('heroicon-o-identification')
                    ->relationship('studentDetails')
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

                        Select::make('blood_group')
                            ->label('Blood Group')
                            ->options([
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+' => 'O+',
                                'O-' => 'O-',
                            ])
                            ->native(false),

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
                            ->label('Street Address')
                            ->placeholder('e.g. Ward 4, New Road'),
                    ])
                    ->columns(3),

                Section::make('Class Enrollments & History')
                    ->description('Academic session, class, section, and roll number assignment')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Repeater::make('classMappings')
                            ->relationship('classMappings')
                            ->label('')
                            ->schema([
                                Select::make('batch_year_id')
                                    ->label('Academic Year')
                                    ->relationship('batchYear', 'batch')
                                    ->default(fn () => \App\Models\BatchYear::where('is_active', true)->value('id'))
                                    ->required(),

                                Select::make('grade_id')
                                    ->label('Grade / Class')
                                    ->relationship('grades', 'grade')
                                    ->required(),

                                Select::make('section_id')
                                    ->label('Section')
                                    ->relationship('sections', 'section')
                                    ->required(),

                                TextInput::make('roll_no')
                                    ->label('Roll Number')
                                    ->placeholder('e.g. 15')
                                    ->maxLength(30),

                                DatePicker::make('start_date')
                                    ->label('Enrolled Date')
                                    ->default(now()),

                                DatePicker::make('end_date')
                                    ->label('Completion Date'),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('+ Enroll in Another Class / Year')
                            ->collapsible(),
                    ]),
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
                    ->label('Student Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('studentDetails.phone')
                    ->label('Phone')
                    ->icon('heroicon-m-phone')
                    ->placeholder('—'),

                TextColumn::make('latestClassMapping.grades.grade')
                    ->label('Grade')
                    ->badge()
                    ->color('info')
                    ->placeholder('Unassigned'),

                TextColumn::make('latestClassMapping.sections.section')
                    ->label('Sec')
                    ->placeholder('—'),

                TextColumn::make('latestClassMapping.roll_no')
                    ->label('Roll')
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
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending Review',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('grade')
                    ->relationship('classMappings.grades', 'grade')
                    ->label('Filter by Grade'),
            ])
            ->actions([
                // 1-Click Instant Approve
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Student $record): bool => $record->status !== 'approved')
                    ->action(function (Student $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Student Approved')
                            ->body("{$record->name} has been approved successfully.")
                            ->success()
                            ->send();
                    }),

                // 1-Click Instant Reject
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Student $record): bool => $record->status !== 'rejected')
                    ->action(function (Student $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Student Rejected')
                            ->body("{$record->name} status changed to rejected.")
                            ->danger()
                            ->send();
                    }),

                // Download Comprehensive PDF Report
                Action::make('downloadReport')
                    ->label('Report')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Student $record): string => route('studentReport.pdf', $record->id))
                    ->openUrlInNewTab(),

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
                                ->title('Students Approved')
                                ->body(count($records) . ' students approved successfully.')
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
                                ->title('Students Rejected')
                                ->body(count($records) . ' students marked as rejected.')
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
