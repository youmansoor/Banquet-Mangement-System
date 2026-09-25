<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'business_name',
        'owner_name',

        'email',
        'phone',

        'ntn_number',
        'nic_number',

        'business_address',
        'home_address',

        // Backward compatibility
        'address',

        'logo',
        'status',
        'terms_accepted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS
    |--------------------------------------------------------------------------
    */

    public function customers(): HasMany
    {
        return $this->hasMany(
            Customer::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LAWN TYPES
    |--------------------------------------------------------------------------
    */

    public function lawnTypes(): HasMany
    {
        return $this->hasMany(
            LawnType::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTIONS
    |--------------------------------------------------------------------------
    */

    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            Subscription::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(
            Subscription::class,
            'tenant_id'
        )
            ->where(
                'status',
                'active'
            )
            ->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    */

    public function services(): HasMany
    {
        return $this->hasMany(
            Service::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function bookings(): HasMany
    {
        return $this->hasMany(
            Booking::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICES
    |--------------------------------------------------------------------------
    */

    public function invoices(): HasMany
    {
        return $this->hasMany(
            Invoice::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(
            AdminTenantPayment::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTION DUES
    |--------------------------------------------------------------------------
    */

    public function subscriptionDues(): HasMany
    {
        return $this->hasMany(
            SubscriptionDue::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT SETTINGS
    |--------------------------------------------------------------------------
    */

    public function settings(): HasOne
    {
        return $this->hasOne(
            TenantSetting::class,
            'tenant_id'
        );
    }

    public function termConditions(): HasMany
    {
        return $this->hasMany(
            TenantTermCondition::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VENDORS
    |--------------------------------------------------------------------------
    */

    public function vendors(): HasMany
    {
        return $this->hasMany(
            Vendor::class,
            'tenant_id'
        );
    }
}