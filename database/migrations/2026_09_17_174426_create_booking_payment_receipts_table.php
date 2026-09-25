<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_payment_receipts', function (Blueprint $table) {

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
            | BOOKING
            |--------------------------------------------------------------------------
            */

            $table->foreignId('booking_id')
                ->constrained('bookings')
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
            | BOOKING PAYMENT
            |--------------------------------------------------------------------------
            |
            | One receipt per payment.
            |--------------------------------------------------------------------------
            */

            $table->foreignId('booking_payment_id')
                ->unique()
                ->constrained('booking_payments')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | RECEIPT NUMBER
            |--------------------------------------------------------------------------
            */

            $table->string('receipt_number')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | RECEIPT DATE
            |--------------------------------------------------------------------------
            */

            $table->date('receipt_date');


            /*
            |--------------------------------------------------------------------------
            | BOOKING TOTAL
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'booking_total',
                15,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | PAYMENT BEFORE CURRENT PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'paid_before',
                15,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | CURRENT PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'current_payment',
                15,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | TOTAL PAID AFTER CURRENT PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'total_paid_after',
                15,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | REMAINING AFTER CURRENT PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'remaining_after',
                15,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | PAYMENT DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string(
                'payment_method'
            )->nullable();

            $table->string(
                'installment_type'
            )->nullable();

            $table->string(
                'transaction_reference'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | PAYMENT HISTORY SNAPSHOT
            |--------------------------------------------------------------------------
            |
            | Har receipt ke waqt complete payment history freeze ho jayegi.
            |--------------------------------------------------------------------------
            */

            $table->json(
                'payment_history'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text(
                'notes'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index(
                [
                    'tenant_id',
                    'booking_id',
                ],
                'bpr_tenant_booking_index'
            );

            $table->index(
                [
                    'tenant_id',
                    'receipt_date',
                ],
                'bpr_tenant_date_index'
            );

        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'booking_payment_receipts'
        );
    }
};