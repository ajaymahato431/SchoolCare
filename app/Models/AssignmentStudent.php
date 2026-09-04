<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentStudent extends Model
{
    protected $table = 'assignment_student';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'status',
        'submitted_at',
        'marks_obtained',
        'feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'marks_obtained' => 'float',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function assignments(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
