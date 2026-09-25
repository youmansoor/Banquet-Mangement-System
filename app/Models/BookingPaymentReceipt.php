<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPaymentReceipt extends Model
{
    protected $table =
        'booking_payment_receipts';


    protected $fillable = [

        'tenant_id',

        'booking_id',

        'customer_id',

        'booking_payment_id',

        'receipt_number',

        'receipt_date',

        'booking_total',

        'paid_before',

        'current_payment',

        'total_paid_after',

        'remaining_after',

        'payment_method',

        'installment_type',

        'transaction_reference',

        'payment_history',

        'notes',

    ];


    protected $casts = [

        'receipt_date' =>
            'date',

        'booking_total' =>
            'decimal:2',

        'paid_before' =>
            'decimal:2',

        'current_payment' =>
            'decimal:2',

        'total_paid_after' =>
            'decimal:2',

        'remaining_after' =>
            'decimal:2',

        'payment_history' =>
            'array',

    ];


    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }


    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            Booking::class,
            'booking_id'
        );
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }


    public function bookingPayment(): BelongsTo
    {
        return $this->belongsTo(
            BookingPayment::class,
            'booking_payment_id'
        );
    }
}