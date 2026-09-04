<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BatchYear extends Model
{
    protected $fillable = [
        'batch',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->batch;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'batch_year_id');
    }

    public function classMappings(): HasMany
    {
        return $this->hasMany(ClassMapping::class, 'batch_year_id');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, ClassMapping::class, 'batch_year_id', 'id', 'id', 'student_id');
    }

    public function scholorships(): HasMany
    {
        return $this->hasMany(Scholorship::class, 'batch_year_id');
    }

    public function scholarships(): HasMany
    {
        return $this->hasMany(Scholorship::class, 'batch_year_id');
    }
}
