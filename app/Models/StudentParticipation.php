<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentParticipation extends Model
{
    public function activities(): BelongsTo
    {
        return $this->belongsTo(Activities::class, 'activity_id');
    }

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
