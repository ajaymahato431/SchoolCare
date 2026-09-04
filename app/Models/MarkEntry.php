<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkEntry extends Model
{
    protected $fillable = [
        'student_id',
        'grade_id',
        'exam_type_id',
        'subject_id',
        'batch_year_id',
        'teacher_id',
        'marks_obtained',
        'full_marks',
        'pass_marks',
        'remarks',
    ];

    protected $casts = [
        'marks_obtained' => 'float',
        'full_marks' => 'float',
        'pass_marks' => 'float',
    ];

    public function getPercentageAttribute(): ?float
    {
        if ($this->full_marks > 0 && !is_null($this->marks_obtained)) {
            return round(($this->marks_obtained / $this->full_marks) * 100, 2);
        }
        return null;
    }

    public function getIsPassAttribute(): ?bool
    {
        if (is_null($this->marks_obtained)) {
            return null;
        }
        return $this->marks_obtained >= ($this->pass_marks ?? 40);
    }

    public function examTypes()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function grades()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function batchYears()
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }

    public function batchYear()
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }
}
