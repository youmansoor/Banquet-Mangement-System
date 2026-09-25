<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

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
            | SUBSCRIPTION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('subscriptions')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PAYMENT DATE
            |--------------------------------------------------------------------------
            */

            $table->date('payment_date');

            /*
            |--------------------------------------------------------------------------
            | PAYMENT TYPE
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_type', [
                'cash',
                'bank',
                'online',
                'card',
            ])->default('cash');

            /*
            |--------------------------------------------------------------------------
            | PAYMENT IN / OUT
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_direction', [
                'in',
                'out',
            ])->default('in');

            /*
            |--------------------------------------------------------------------------
            | SUBSCRIPTION TOTAL
            |--------------------------------------------------------------------------
            */

            $table->decimal('subscription_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | PAYMENT AMOUNT
            |--------------------------------------------------------------------------
            */

            $table->decimal('payment_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | REMAINING AMOUNT
            |--------------------------------------------------------------------------
            */

            $table->decimal('remaining_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION REFERENCE
            |--------------------------------------------------------------------------
            */

            $table->string('transaction_reference')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
