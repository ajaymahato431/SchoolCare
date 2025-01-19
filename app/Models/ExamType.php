<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    public function markEntries()
    {
        return $this->hasOne(MarkEntry::class, 'exam_type_id'); // Adjust foreign key as needed
    }
}
