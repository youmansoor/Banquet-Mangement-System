<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_payments', function (Blueprint $table) {

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
            | AMOUNT
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | PAYMENT METHOD
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_method', [
                'cash',
                'bank',
                'online',
                'card',
                'cheque',
            ])->default('cash');

            /*
            |--------------------------------------------------------------------------
            | PAYMENT STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | PAID AT
            |--------------------------------------------------------------------------
            */

            $table->timestamp('paid_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION REFERENCE
            |--------------------------------------------------------------------------
            */

            $table->string('transaction_reference')->nullable();

            /*
            |--------------------------------------------------------------------------
            | INSTALLMENT TYPE
            |--------------------------------------------------------------------------
            */

            $table->string('installment_type')->nullable();

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
                'booking_id',
            ]);

            $table->index([
                'tenant_id',
                'payment_method',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
