<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assignment extends Model
{
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function teachers(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function subjects(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
