<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activities extends Model
{
    public function studentParticipations():HasMany
    {
        return $this->hasMany(StudentParticipation::class,'activity_id');
    }
}
