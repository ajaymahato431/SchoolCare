<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    public function teachers()
    {
        return $this->hasOne(TeacherDetail::class, 'municipality_id');
    }

    public function students()
    {
        return $this->hasOne(StudentDetail::class, 'municipality_id');
    }
}
