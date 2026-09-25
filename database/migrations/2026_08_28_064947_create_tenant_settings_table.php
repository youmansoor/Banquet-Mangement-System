<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tenant_id')->unique();

            // Business
            $table->string('business_name')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->text('business_address')->nullable();

            // Tax
            $table->boolean('tax_enabled')->default(false);
            $table->string('tax_name')->nullable();
            $table->decimal('tax_percentage', 8, 2)->default(0);

            // Payment
            $table->string('currency')->default('PKR');
            $table->string('payment_method')->nullable();
            $table->decimal('booking_advance_percentage', 8, 2)->default(0);

            // Installment Options
            $table->json('installment_options')->nullable();

            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
