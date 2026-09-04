<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PositiveBehaviour extends Model
{
    protected $fillable = [
        'student_id',
        'grade_id',
        'report',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
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
}
