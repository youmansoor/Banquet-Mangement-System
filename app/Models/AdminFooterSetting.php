<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminFooterSetting extends Model
{
    protected $fillable = [
        'enabled',
        'footer_text',
        'brand_name',
        'link_text',
        'link_url',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}