<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PositiveBehaviour extends Model
{
    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public function grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class,'grade_id');
    }
}
