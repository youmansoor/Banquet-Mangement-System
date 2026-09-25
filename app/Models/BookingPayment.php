<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPayment extends Model
{
    protected $fillable = [
        'tenant_id',
        'booking_id',
        'invoice_id',
        'tenant_bank_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_reference',
        'notes',
        'paid_at',
        'installment_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOKING
    |--------------------------------------------------------------------------
    */

    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            Booking::class,
            'booking_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICE
    |--------------------------------------------------------------------------
    */

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
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
    | TENANT BANK
    |--------------------------------------------------------------------------
    |
    | This is the tenant's own bank account.
    |
    */

    public function tenantBank(): BelongsTo
    {
        return $this->belongsTo(
            TenantBank::class,
            'tenant_bank_id'
        );
    }
    public function receipt(): HasOne
{
    return $this->hasOne(
        BookingPaymentReceipt::class,
        'booking_payment_id'
    );
}
}