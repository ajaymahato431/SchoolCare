<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'grade_id',
        'subject_id',
        'max_marks',
        'assignment_date',
        'submission_date',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'submission_date' => 'date',
        'max_marks' => 'float',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'assignment_student')
            ->withPivot(['status', 'submitted_at', 'marks_obtained', 'feedback'])
            ->withTimestamps();
    }

    public function assignmentStudents(): HasMany
    {
        return $this->hasMany(AssignmentStudent::class, 'assignment_id');
    }

    public function teachers(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function subjects(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
