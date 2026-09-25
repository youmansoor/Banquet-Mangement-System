<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('lawn_type_id')
                ->constrained('lawn_types')
                ->cascadeOnDelete();

            $table->string('quotation_number')->unique();

            $table->string('event_type');

            $table->date('event_date');

            $table->string('booking_time');

            $table->unsignedInteger('number_of_guests');

            $table->string('package_name')->nullable();

            $table->text('menu_details')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Services
            |--------------------------------------------------------------------------
            | Agar quotation services alag quotation_services table mein save
            | kar rahe hain to ye column nullable rakhein.
            */
            $table->text('services')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */
            $table->decimal('banquet_booking_amount', 15, 2)->default(0);

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->decimal('discount', 15, 2)->default(0);

            $table->decimal('tax', 15, 2)->default(0);

            $table->decimal('totalamount', 15, 2)->default(0);

            $table->decimal('bookingamount', 15, 2)->default(0);

            $table->decimal('remainingamount', 15, 2)->default(0);

            $table->date('valid_until')->nullable();

            $table->enum('status', [
                'draft',
                'sent',
                'accepted',
                'rejected',
                'expired',
            ])->default('draft');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
