<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Ward extends Model
{
    protected $fillable = [
        'ward',
    ];

    protected $casts = [
        'ward' => 'integer',
    ];

    public function teacherDetails(): HasMany
    {
        return $this->hasMany(TeacherDetail::class, 'ward_id');
    }

    public function studentDetails(): HasMany
    {
        return $this->hasMany(StudentDetail::class, 'ward_id');
    }

    public function teachers(): HasManyThrough
    {
        return $this->hasManyThrough(Teacher::class, TeacherDetail::class, 'ward_id', 'id', 'id', 'teacher_id');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, StudentDetail::class, 'ward_id', 'id', 'id', 'student_id');
    }
}
