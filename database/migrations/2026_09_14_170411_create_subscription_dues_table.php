<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_dues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('subscription_id')
                ->constrained('subscriptions')
                ->cascadeOnDelete();

            // Example: 2026-09-01 = September billing month
            $table->date('billing_month');

            // Actual monthly due date
            $table->date('due_date');

            // Monthly subscription amount
            $table->decimal('amount', 12, 2);

            // Amount allocated against this month's due
            $table->decimal('paid_amount', 12, 2)
                ->default(0);

            // Amount still outstanding
            $table->decimal('remaining_amount', 12, 2)
                ->default(0);

            $table->enum('status', [
                'unpaid',
                'partial',
                'paid',
            ])->default('unpaid');

            $table->timestamps();

            $table->unique([
                'subscription_id',
                'billing_month',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_dues');
    }
};
