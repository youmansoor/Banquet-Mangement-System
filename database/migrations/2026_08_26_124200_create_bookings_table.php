<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

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
            | CUSTOMER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | LAWN
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('lawn_type');


            /*
            |--------------------------------------------------------------------------
            | EVENT DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string('event_type');

            $table->date('booking_date');

            $table->enum('booking_time', [
                'day',
                'night',
            ]);

            $table->unsignedInteger('number_of_guests');


            /*
            |--------------------------------------------------------------------------
            | PRICING
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'booking_amount',
                15,
                2
            )->default(0);

            $table->decimal(
                'total_amount',
                15,
                2
            )->default(0);

            $table->decimal(
                'tax_amount',
                15,
                2
            )->default(0);

            $table->decimal(
                'discount',
                15,
                2
            )->default(0);

            $table->decimal(
                'grand_total',
                15,
                2
            )->default(0);

            $table->decimal(
                'advance_amount',
                15,
                2
            )->default(0);

            $table->decimal(
                'remaining_amount',
                15,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'card',
                'online',
                'cheque',
            ])->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index([
                'tenant_id',
                'booking_date',
            ]);

            $table->index([
                'tenant_id',
                'lawn_type',
                'booking_date',
                'booking_time',
            ]);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};