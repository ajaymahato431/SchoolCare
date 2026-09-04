<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function sections(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function batchYear(): BelongsTo
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }

    public function batchYears(): BelongsTo
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }
}
