<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LawnType extends Model
{
    protected $fillable = [
        'tenant_id',
        'lawn_type',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'lawn_type', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
