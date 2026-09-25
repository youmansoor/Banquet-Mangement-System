<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancelledBooking extends Model
{
    protected $fillable = [
        'tenant_id',
        'booking_id',
        'customer_id',
        'lawn_type',
        'event_type',
        'booking_date',
        'booking_time',
        'number_of_guests',
        'booking_amount',
        'total_amount',
        'tax_amount',
        'discount',
        'grand_total',
        'advance_amount',
        'remaining_amount',
        'payment_method',
        'status',
        'notes',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'cancelled_at' => 'datetime',

        'booking_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LAWN TYPE
    |--------------------------------------------------------------------------
    */

    public function lawnType(): BelongsTo
    {
        return $this->belongsTo(
            LawnType::class,
            'lawn_type',
            'id'
        );
    }

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
    | ORIGINAL BOOKING
    |--------------------------------------------------------------------------
    */

    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            Booking::class,
            'booking_id'
        );
    }
}
