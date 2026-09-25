<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPagePin extends Model
{
    protected $fillable = [
        'user_id',
        'page_key',
        'page_name',
        'pin_hash',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
