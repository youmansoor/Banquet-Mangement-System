<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | BUSINESS INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string(
                'business_name'
            );

            $table->string(
                'owner_name'
            );

            $table->string(
                'email'
            )->nullable();

            $table->string(
                'phone',
                30
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | TAX / IDENTITY
            |--------------------------------------------------------------------------
            */

            $table->string(
                'ntn_number',
                15
            )->nullable();

            $table->string(
                'nic_number',
                12
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | ADDRESSES
            |--------------------------------------------------------------------------
            */

            $table->text(
                'business_address'
            )->nullable();

            $table->text(
                'home_address'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | BACKWARD COMPATIBILITY
            |--------------------------------------------------------------------------
            |
            | Purane code mein agar $tenant->address use ho raha ho
            | to woh break nahi hoga.
            |
            */

            $table->text(
                'address'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */

            $table->string(
                'logo'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean(
                'status'
            )->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index(
                'business_name'
            );

            $table->index(
                'owner_name'
            );

            $table->index(
                'ntn_number'
            );

            $table->index(
                'nic_number'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'tenants'
        );
    }
};