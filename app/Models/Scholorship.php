<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function batchYear()
    {
        return $this->belongsTo(BatchYear::class, 'batch_year_id');
    }
}
