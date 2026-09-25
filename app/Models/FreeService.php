<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeService extends Model
{
    protected $fillable = [
        'tenant_id',
        'service_name',
        'service_unit',
        'status',
    ];


    protected $casts = [
        'status' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | TENANT
    |--------------------------------------------------------------------------
    */

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }
}