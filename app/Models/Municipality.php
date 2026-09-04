<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Municipality extends Model
{
    protected $fillable = [
        'municipality',
    ];

    public function teacherDetails(): HasMany
    {
        return $this->hasMany(TeacherDetail::class, 'municipality_id');
    }

    public function studentDetails(): HasMany
    {
        return $this->hasMany(StudentDetail::class, 'municipality_id');
    }

    public function teachers(): HasManyThrough
    {
        return $this->hasManyThrough(Teacher::class, TeacherDetail::class, 'municipality_id', 'id', 'id', 'teacher_id');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, StudentDetail::class, 'municipality_id', 'id', 'id', 'student_id');
    }
}
