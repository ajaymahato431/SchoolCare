<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function teacherDetails()
    {
        return $this->hasMany(TeacherDetail::class, 'subject_id');
    }

    public function markEntries()
    {
        return $this->hasMany(MarkEntry::class, 'subject_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'subject_id');
    }
}
