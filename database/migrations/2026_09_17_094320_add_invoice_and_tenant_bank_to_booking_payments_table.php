<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {

            $table->foreignId('invoice_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('invoices')
                ->nullOnDelete();

            $table->foreignId('tenant_bank_id')
                ->nullable()
                ->after('invoice_id')
                ->constrained('tenant_banks')
                ->nullOnDelete();

            $table->index([
                'tenant_id',
                'invoice_id',
            ]);

            $table->index([
                'tenant_id',
                'tenant_bank_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {

            $table->dropForeign([
                'invoice_id',
            ]);

            $table->dropForeign([
                'tenant_bank_id',
            ]);

            $table->dropIndex([
                'tenant_id',
                'invoice_id',
            ]);

            $table->dropIndex([
                'tenant_id',
                'tenant_bank_id',
            ]);

            $table->dropColumn([
                'invoice_id',
                'tenant_bank_id',
            ]);
        });
    }
};