<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {

            $table->foreignId('vendor_id')
                ->nullable()
                ->after('tenant_id')
                ->constrained('vendors')
                ->nullOnDelete();

            $table->decimal(
                'purchase_amount',
                12,
                2
            )
                ->default(0)
                ->after('amount');

            $table->string(
                'payment_status',
                20
            )
                ->default('unpaid')
                ->after('purchase_amount');

            $table->index([
                'tenant_id',
                'vendor_id'
            ]);

            $table->index([
                'tenant_id',
                'payment_status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {

            $table->dropForeign([
                'vendor_id'
            ]);

            $table->dropIndex([
                'services_tenant_id_vendor_id_index'
            ]);

            $table->dropIndex([
                'services_tenant_id_payment_status_index'
            ]);

            $table->dropColumn([
                'vendor_id',
                'purchase_amount',
                'payment_status'
            ]);
        });
    }
};