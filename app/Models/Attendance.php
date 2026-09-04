<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    protected $fillable = [
        'name',
        'teacher_id',
        'grade_id',
        'section_id',
        'attendance_date',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'attendance_student')
            ->withPivot(['status', 'remarks'])
            ->withTimestamps();
    }

    public function attendanceStudents(): HasMany
    {
        return $this->hasMany(AttendanceStudent::class, 'attendance_id');
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

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function sections(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
