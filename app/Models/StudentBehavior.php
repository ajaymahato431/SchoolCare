<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBehavior extends Model
{
    use HasFactory;

    protected $table = 'student_behaviors';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'grade_id',
        'type',
        'category',
        'severity',
        'title',
        'description',
        'action_taken',
        'points',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
        'points' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teachers(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function isPositive(): bool
    {
        return $this->type === 'positive';
    }

    public function isNegative(): bool
    {
        return $this->type === 'negative';
    }
}
