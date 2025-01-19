<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function municipalities()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function wards()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}
