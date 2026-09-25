<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->id();

            // Tenant
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            // Customer Information
            $table->string('name');

            $table->string('email')->nullable();

            $table->string('phone_1')->nullable();

            $table->string('phone_2')->nullable();

            // CNIC
            $table->string('nic_number')->nullable();

            $table->string('cnic_front')->nullable();

            $table->string('cnic_back')->nullable();

            // Address
            $table->text('address')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // Same CNIC cannot be duplicated
            // inside the same tenant.
            $table->unique(
                ['tenant_id', 'nic_number'],
                'customers_tenant_nic_number_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
