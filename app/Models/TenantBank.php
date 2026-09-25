<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantBank extends Model
{
    protected $table = 'tenant_banks';

    protected $fillable = [
        'tenant_id',
        'bank_name',
        'opening_balance',
        'current_balance',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }

    public function bookingPayments(): HasMany
    {
        return $this->hasMany(
            BookingPayment::class,
            'tenant_bank_id'
        );
    }
}