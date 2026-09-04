<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guard = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getGradeAttribute(): ?Grade
    {
        return $this->latestClassMapping?->grade;
    }

    public function getSectionAttribute(): ?Section
    {
        return $this->latestClassMapping?->section;
    }

    public function getBatchYearAttribute(): ?BatchYear
    {
        return $this->latestClassMapping?->batchYear;
    }

    public function studentDetails(): HasOne
    {
        return $this->hasOne(StudentDetail::class, 'student_id');
    }

    public function studentDetail(): HasOne
    {
        return $this->hasOne(StudentDetail::class, 'student_id');
    }

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'student_id');
    }

    public function attendences(): BelongsToMany
    {
        return $this->belongsToMany(Attendance::class, 'attendance_student')
            ->withPivot(['status', 'remarks'])
            ->withTimestamps();
    }

    public function attendances(): BelongsToMany
    {
        return $this->attendences();
    }

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'assignment_student')
            ->withPivot(['status', 'submitted_at', 'marks_obtained', 'feedback'])
            ->withTimestamps();
    }

    public function scholorships(): BelongsToMany
    {
        return $this->belongsToMany(Scholorship::class, 'scholorship_student');
    }

    public function scholarships(): BelongsToMany
    {
        return $this->belongsToMany(Scholorship::class, 'scholorship_student');
    }

    public function positiveBehaviours(): HasMany
    {
        return $this->hasMany(PositiveBehaviour::class, 'student_id');
    }

    public function negativeBehaviours(): HasMany
    {
        return $this->hasMany(NegativeBehaviour::class, 'student_id');
    }

    public function behaviors(): HasMany
    {
        return $this->hasMany(StudentBehavior::class, 'student_id');
    }

    public function studentBehaviors(): HasMany
    {
        return $this->hasMany(StudentBehavior::class, 'student_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(StudentParticipation::class, 'student_id');
    }

    public function studentParticipations(): HasMany
    {
        return $this->hasMany(StudentParticipation::class, 'student_id');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activities::class, 'student_participations', 'student_id', 'activity_id')
            ->withPivot(['obtained_rank', 'role_or_position', 'certificate_issued'])
            ->withTimestamps();
    }

    public function classMappings(): HasMany
    {
        return $this->hasMany(ClassMapping::class, 'student_id');
    }

    public function latestClassMapping(): HasOne
    {
        return $this->hasOne(ClassMapping::class, 'student_id')->latestOfMany();
    }
}
