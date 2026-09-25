<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('subscription_id')
                ->constrained('subscriptions')
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);

            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'cheque',
                'online',
            ])->default('cash');

            $table->string('transaction_reference')->nullable();

            $table->dateTime('paid_at')->nullable();

            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])->default('paid');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
