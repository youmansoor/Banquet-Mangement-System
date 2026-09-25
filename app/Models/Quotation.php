<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $fillable = [

        'tenant_id',
        'customer_id',
        'lawn_type_id',

        'quotation_number',
        'quotation_date',
        'valid_until',

        'event_type',
        'event_date',
        'booking_time',
        'number_of_guests',

        'package_name',
        'menu_details',
        'services',

        'banquet_booking_amount',
        'subtotal',
        'discount',
        'tax',
        'additional_charges',

        'grand_total',
        'totalamount',
        'bookingamount',
        'remainingamount',

        'status',
        'notes',

        'invoice_id',
    ];


    protected $casts = [

        'quotation_date' => 'date',
        'event_date' => 'date',
        'valid_until' => 'date',

        'services' => 'array',

        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'additional_charges' => 'decimal:2',

        'banquet_booking_amount' => 'decimal:2',

        'grand_total' => 'decimal:2',

        'totalamount' => 'decimal:2',
        'bookingamount' => 'decimal:2',
        'remainingamount' => 'decimal:2',
    ];


    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }


    public function lawnType(): BelongsTo
    {
        return $this->belongsTo(
            LawnType::class,
            'lawn_type_id'
        );
    }


    public function items(): HasMany
    {
        return $this->hasMany(
            QuotationItem::class,
            'quotation_id'
        );
    }


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }
}