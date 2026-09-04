<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentParticipation extends Model
{
    protected $fillable = [
        'activity_id',
        'student_id',
        'obtained_rank',
        'role_or_position',
        'certificate_issued',
    ];

    protected $casts = [
        'certificate_issued' => 'boolean',
    ];

    public function activities(): BelongsTo
    {
        return $this->belongsTo(Activities::class, 'activity_id');
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activities::class, 'activity_id');
    }

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
