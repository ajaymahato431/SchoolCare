<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guard = 'teachers';

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

    public function teacherDetails(): HasOne
    {
        return $this->hasOne(TeacherDetail::class, 'teacher_id');
    }

    public function teacherDetail(): HasOne
    {
        return $this->hasOne(TeacherDetail::class, 'teacher_id');
    }

    public function subject(): HasOneThrough
    {
        return $this->hasOneThrough(Subject::class, TeacherDetail::class, 'teacher_id', 'id', 'id', 'subject_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'teacher_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    public function studentBehaviors(): HasMany
    {
        return $this->hasMany(StudentBehavior::class, 'teacher_id');
    }

    public function behaviors(): HasMany
    {
        return $this->hasMany(StudentBehavior::class, 'teacher_id');
    }
}
