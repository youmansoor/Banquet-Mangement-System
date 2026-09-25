<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_services')) {
            return;
        }

        Schema::create('booking_services', function (Blueprint $table) {

            $table->id();

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
            | SERVICE TYPE
            |--------------------------------------------------------------------------
            */

            $table->enum('service_type', [
                'paid',
                'free',
            ])->default('paid');

            /*
            |--------------------------------------------------------------------------
            | PAID SERVICE
            |--------------------------------------------------------------------------
            |
            | Used only when service_type = paid
            |
            */

            $table->foreignId('paid_service_id')
                ->nullable()
                ->constrained('services')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FREE SERVICE
            |--------------------------------------------------------------------------
            |
            | Used only when service_type = free
            |
            */

            $table->foreignId('free_service_id')
                ->nullable()
                ->constrained('free_services')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SERVICE DETAILS AT BOOKING TIME
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity')
                ->default(1);

            /*
            |--------------------------------------------------------------------------
            | PRICE
            |--------------------------------------------------------------------------
            |
            | Paid service:
            |     actual service price
            |
            | Free service:
            |     0
            |
            */

            $table->decimal(
                'price',
                15,
                2
            )->default(0);

            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            |
            | Paid service:
            |     quantity × price
            |
            | Free service:
            |     0
            |
            */

            $table->decimal(
                'total',
                15,
                2
            )->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index([
                'booking_id',
                'service_type',
            ]);

            $table->index('paid_service_id');

            $table->index('free_service_id');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('booking_services');

        Schema::enableForeignKeyConstraints();
    }
};