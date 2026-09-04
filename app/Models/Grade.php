<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'grade',
    ];

    protected $casts = [
        'grade' => 'integer',
    ];

    public function classMappings(): HasMany
    {
        return $this->hasMany(ClassMapping::class, 'grade_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_mappings', 'grade_id', 'student_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'class_mappings', 'grade_id', 'section_id')->distinct();
    }

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'grade_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'grade_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'grade_id');
    }

    public function positiveBehaviours(): HasMany
    {
        return $this->hasMany(PositiveBehaviour::class, 'grade_id');
    }

    public function negativeBehaviours(): HasMany
    {
        return $this->hasMany(NegativeBehaviour::class, 'grade_id');
    }

    public function studentBehaviors(): HasMany
    {
        return $this->hasMany(StudentBehavior::class, 'grade_id');
    }

    public function behaviors(): HasMany
    {
        return $this->hasMany(StudentBehavior::class, 'grade_id');
    }
}
