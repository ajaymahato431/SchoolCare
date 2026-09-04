<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
