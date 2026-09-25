<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'booking_id',
        'service_type',
        'service_id',
        'quantity',
        'price',
        'total',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
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
    | PAID SERVICE
    |--------------------------------------------------------------------------
    |
    | service_type = paid
    | service_id belongs to services.id
    |
    */

    public function paidService(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'service_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FREE SERVICE
    |--------------------------------------------------------------------------
    |
    | service_type = free
    | service_id belongs to free_services.id
    |
    */

    public function freeService(): BelongsTo
    {
        return $this->belongsTo(
            FreeService::class,
            'service_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BACKWARD COMPATIBILITY
    |--------------------------------------------------------------------------
    |
    | Purane code mein bookingService->service use ho sakta hai.
    |
    | IMPORTANT:
    | Ye relation sirf paid services ke liye reliable hai.
    | Free services ke liye freeService() use karo.
    |
    */

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'service_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAID / FREE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->service_type === 'paid';
    }


    public function isFree(): bool
    {
        return $this->service_type === 'free';
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE NAME
    |--------------------------------------------------------------------------
    |
    | Paid ho to Service se name
    | Free ho to FreeService se name
    |
    */

    public function getServiceNameAttribute(): ?string
    {
        if ($this->isFree()) {

            return $this->freeService?->service_name;
        }

        return $this->paidService?->service_name
            ?? $this->service?->service_name;
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE UNIT
    |--------------------------------------------------------------------------
    */

    public function getServiceUnitAttribute(): ?string
    {
        if ($this->isFree()) {

            return $this->freeService?->service_unit;
        }

        return $this->paidService?->service_unit
            ?? $this->service?->service_unit;
    }
}