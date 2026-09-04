<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMapping extends Model
{
    protected $fillable = [
        'student_id',
        'grade_id',
        'section_id',
        'batch_year_id',
        'roll_no',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function grades()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function batchYear()
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }
}
