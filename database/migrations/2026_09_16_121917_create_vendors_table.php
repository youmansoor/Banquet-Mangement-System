<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | TENANT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | VENDOR INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('vendor_name');

            $table->string('contact_person')
                ->nullable();

            $table->string('email')
                ->nullable();

            $table->string('phone', 30)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | TAX / IDENTITY
            |--------------------------------------------------------------------------
            */

            $table->string('ntn_number', 15)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | VENDOR TYPE
            |--------------------------------------------------------------------------
            */

            $table->string('vendor_type')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            $table->text('address')
                ->nullable();

            $table->string('city')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ACCOUNTING
            |--------------------------------------------------------------------------
            |
            | Agar vendor se pehle se maal liya hua ho aur payment baki ho
            | to opening balance yahan store hoga.
            |
            */

            $table->decimal(
                'opening_balance',
                15,
                2
            )->default(0);

            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */

            $table->string('logo')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index([
                'tenant_id',
                'vendor_name'
            ]);

            $table->index([
                'tenant_id',
                'phone'
            ]);

            $table->index([
                'tenant_id',
                'ntn_number'
            ]);

            $table->index([
                'tenant_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};