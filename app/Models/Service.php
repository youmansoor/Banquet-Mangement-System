<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'tenant_id',
        'vendor_id',
        'service_name',
        'service_unit',
        'amount',
        'purchase_amount',
        'payment_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'purchase_amount' => 'decimal:2',
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
    | VENDOR
    |--------------------------------------------------------------------------
    */

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(
            Vendor::class,
            'vendor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING SERVICES
    |--------------------------------------------------------------------------
    */

    public function bookingServices(): HasMany
    {
        return $this->hasMany(
            BookingService::class,
            'service_id'
        );
    }
}