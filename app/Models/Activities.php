<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activities extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'name',
        'category',
        'start_date',
        'end_date',
        'organizer',
        'address',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function studentParticipations(): HasMany
    {
        return $this->hasMany(StudentParticipation::class, 'activity_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(StudentParticipation::class, 'activity_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_participations', 'activity_id', 'student_id')
            ->withPivot(['obtained_rank', 'role_or_position', 'certificate_issued'])
            ->withTimestamps();
    }
}
