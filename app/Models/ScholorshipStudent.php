<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholorshipStudent extends Model
{
    protected $table = 'scholorship_student';

    protected $fillable = [
        'scholorship_id',
        'student_id',
    ];

    public function scholorship(): BelongsTo
    {
        return $this->belongsTo(Scholorship::class, 'scholorship_id');
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholorship::class, 'scholorship_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
