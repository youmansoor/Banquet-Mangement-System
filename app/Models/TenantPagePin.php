<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantPagePin extends Model
{
    protected $fillable = [
        'tenant_id',
        'page_key',
        'page_name',
        'pin_hash',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
