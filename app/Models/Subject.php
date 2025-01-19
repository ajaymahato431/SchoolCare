<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function teacherDetails()
    {
        return $this->belongsTo(TeacherDetail::class, 'subject_id');
    }
}
