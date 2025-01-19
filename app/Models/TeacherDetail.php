<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherDetail extends Model
{
    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function municipalities()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function wards()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
