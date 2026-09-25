<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'tenant_id',
        'customer_id',
        'lawn_type',
        'event_type',
        'booking_date',
        'booking_time',
        'number_of_guests',

        // Amounts
        'booking_amount',
        'total_amount',
        'tax_amount',
        'discount',
        'grand_total',
        'advance_amount',
        'remaining_amount',

        // Payment
        'payment_method',

        // Status
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',

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
    | PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(
            BookingPayment::class,
            'booking_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    | Contains both paid and free services.
    |--------------------------------------------------------------------------
    */

    public function services(): HasMany
    {
        return $this->hasMany(
            BookingService::class,
            'booking_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING SERVICES ALIAS
    |--------------------------------------------------------------------------
    | Kept for compatibility with existing controller/views.
    |--------------------------------------------------------------------------
    */

    public function bookingServices(): HasMany
    {
        return $this->hasMany(
            BookingService::class,
            'booking_id'
        );
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(
            Invoice::class,
            'booking_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CANCELLED BOOKING
    |--------------------------------------------------------------------------
    */

    public function cancelledBooking(): HasOne
    {
        return $this->hasOne(
            CancelledBooking::class,
            'booking_id'
        );
    }
    public function paymentReceipts(): HasMany
{
    return $this->hasMany(
        BookingPaymentReceipt::class,
        'booking_id'
    );
}
}