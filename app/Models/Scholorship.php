<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholorship extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'criteria',
        'status',
        'year',
        'batch_year_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'scholorship_student');
    }

    public function batchYear(): BelongsTo
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }

    public function batchYears(): BelongsTo
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }

    public function scholorshipStudents(): HasMany
    {
        return $this->hasMany(ScholorshipStudent::class, 'scholorship_id');
    }

    public function scholarshipStudents(): HasMany
    {
        return $this->hasMany(ScholorshipStudent::class, 'scholorship_id');
    }
}
