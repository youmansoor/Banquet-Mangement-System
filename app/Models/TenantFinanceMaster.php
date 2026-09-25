<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantFinanceMaster extends Model
{
    protected $table = 'tenant_finance_masters';


    protected $fillable = [

        'tenant_id',

        'master_type',

        /*
        |--------------------------------------------------------------------------
        | LEGACY PARENT ID
        |--------------------------------------------------------------------------
        */

        'parent_id',

        /*
        |--------------------------------------------------------------------------
        | HARD-CODED TRANSACTION TYPE KEY
        |--------------------------------------------------------------------------
        |
        | customer_payment
        | vendor_payment
        | banquet_expense
        |
        */

        'parent_key',

        'name',

        'sort_order',

        'is_active',

    ];


    protected $casts = [

        'sort_order' => 'integer',

        'is_active' => 'boolean',

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


    /*
    |--------------------------------------------------------------------------
    | LEGACY PARENT
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LEGACY CHILDREN
    |--------------------------------------------------------------------------
    */

    public function children(): HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        );
    }
}