<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamType extends Model
{
    protected $fillable = [
        'exam_type',
    ];

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'exam_type_id');
    }

    public function markEntry(): HasOne
    {
        return $this->hasOne(MarkEntry::class, 'exam_type_id');
    }
}
