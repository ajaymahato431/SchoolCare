<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subject extends Model
{
    protected $fillable = [
        'subject',
        'code',
        'full_marks',
        'pass_marks',
    ];

    protected $casts = [
        'full_marks' => 'float',
        'pass_marks' => 'float',
    ];

    public function teacherDetails(): HasMany
    {
        return $this->hasMany(TeacherDetail::class, 'subject_id');
    }

    public function teacherDetail(): HasOne
    {
        return $this->hasOne(TeacherDetail::class, 'subject_id');
    }

    public function teachers(): HasManyThrough
    {
        return $this->hasManyThrough(Teacher::class, TeacherDetail::class, 'subject_id', 'id', 'id', 'teacher_id');
    }

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'subject_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'subject_id');
    }
}
