<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'role',
        'status',
        'password',
        'terms_accepted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * User belongs to a Tenant (Banquet)
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TYPED PROPERTIES
    |--------------------------------------------------------------------------
    */

    public function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'terms_accepted_at' => 'datetime',
        ];
    }

    /**
     * Check if user is Super Admin (Platform Owner)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin'
            || (
                is_null($this->tenant_id)
                && $this->role === 'super_admin'
            );
    }

    /**
     * Check if user belongs to a Tenant (Banquet Business)
     */
    public function isTenantUser(): bool
    {
        return ! is_null($this->tenant_id);
    }

    /**
     * Role Helper Methods
     */
    public function isOwner(): bool
    {
        return $this->role === 'tenant_owner'
            || $this->hasRole('tenant_owner');
    }

    public function isManager(): bool
    {
        return $this->role === 'branch_manager'
            || $this->hasRole('branch_manager');
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant'
            || $this->hasRole('accountant');
    }

    public function isSalesOfficer(): bool
    {
        return $this->role === 'sales_officer'
            || $this->hasRole('sales_officer');
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist'
            || $this->hasRole('receptionist');
    }
}