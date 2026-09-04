<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchYear extends Model
{
    protected $fillable = [
        'batch',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function markEntries()
    {
        return $this->hasMany(MarkEntry::class, 'batch_year_id');
    }

    public function classMappings()
    {
        return $this->hasMany(ClassMapping::class, 'batch_year_id');
    }
}
