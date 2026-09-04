<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'section',
    ];

    public function classMappings(): HasMany
    {
        return $this->hasMany(ClassMapping::class, 'section_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'section_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_mappings', 'section_id', 'student_id');
    }

    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class, 'class_mappings', 'section_id', 'grade_id')->distinct();
    }
}
