<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function markEntries()
    {
        return $this->hasOne(MarkEntry::class, 'grade_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'grade_id');
    }

    public function positiveBehaviours()
    {
        return $this->hasMany(PositiveBehaviour::class, 'grade_id');
    }

    public function negativeBehaviours()
    {
        return $this->hasMany(NegativeBehaviour::class, 'grade_id');
    }
}
