<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceStudent extends Model
{
    protected $table = 'attendance_student';

    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
        'remarks',
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function attendances(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
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
