<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->foreignId('quotation_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('quotations')
                ->nullOnDelete();

            $table->index([
                'tenant_id',
                'quotation_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->dropForeign([
                'quotation_id',
            ]);

            $table->dropIndex([
                'invoices_tenant_id_quotation_id_index',
            ]);

            $table->dropColumn('quotation_id');
        });
    }
};
