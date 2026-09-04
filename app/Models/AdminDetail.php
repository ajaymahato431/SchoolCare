<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminDetail extends Model
{
    protected $fillable = [
        'admin_id',
        'phone',
        'address',
    ];

    public function admins(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
