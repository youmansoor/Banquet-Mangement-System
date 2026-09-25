<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [

        'tenant_id',

        'customer_id',

        'booking_id',

        'quotation_id',

        'subscription_id',

        'invoice_number',

        'invoice_date',

        'due_date',

        'subtotal',

        'discount',

        'tax',

        'additional_charges',

        'grand_total',

        /*
        |--------------------------------------------------------------------------
        | PAY AMOUNT
        |--------------------------------------------------------------------------
        |
        | Amount customer is required/expected to pay according to
        | the invoice.
        |
        */

        'pay_amount',

        /*
        |--------------------------------------------------------------------------
        | ACTUAL PAID AMOUNT
        |--------------------------------------------------------------------------
        |
        | Amount customer has actually paid.
        |
        */

        'paid_amount',

        /*
        |--------------------------------------------------------------------------
        | REMAINING AMOUNT
        |--------------------------------------------------------------------------
        */

        'remaining_amount',

        'status',

        'notes',

    ];

    protected $casts = [

        'invoice_date' => 'date',

        'due_date' => 'date',

        'subtotal' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'additional_charges' => 'decimal:2',

        'grand_total' => 'decimal:2',

        /*
        |--------------------------------------------------------------------------
        | PAY AMOUNT
        |--------------------------------------------------------------------------
        */

        'pay_amount' => 'decimal:2',

        /*
        |--------------------------------------------------------------------------
        | ACTUAL PAID AMOUNT
        |--------------------------------------------------------------------------
        */

        'paid_amount' => 'decimal:2',

        /*
        |--------------------------------------------------------------------------
        | REMAINING AMOUNT
        |--------------------------------------------------------------------------
        */

        'remaining_amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | TENANT
    |--------------------------------------------------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(
            Customer::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING
    |--------------------------------------------------------------------------
    */

    public function booking()
    {
        return $this->belongsTo(
            Booking::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUOTATION
    |--------------------------------------------------------------------------
    */

    public function quotation()
    {
        return $this->belongsTo(
            Quotation::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function subscription()
    {
        return $this->belongsTo(
            Subscription::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICE ITEMS
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(
            InvoiceItem::class
        );
    }
}
