<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchYear extends Model
{
    public function markEntries()
    {
        return $this->hasMany(MarkEntry::class, 'batch_year_id');
    }
}
