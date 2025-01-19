<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkEntry extends Model
{
    public function examTypes()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function grades()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function batchYears()
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }
}
