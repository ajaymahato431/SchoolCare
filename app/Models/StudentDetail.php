<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    protected $fillable = [
        'student_id',
        'phone',
        'address',
        'gender',
        'municipality_id',
        'ward_id',
        'blood_group',
    ];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function municipalities()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function wards()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}
