<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDetail extends Model
{
    public function admins()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
