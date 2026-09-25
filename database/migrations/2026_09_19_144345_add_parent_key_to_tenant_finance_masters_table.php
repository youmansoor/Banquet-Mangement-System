<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_finance_masters', function (Blueprint $table) {
            $table->string('parent_key', 100)
                ->nullable()
                ->after('parent_id');

            $table->index(
                ['tenant_id', 'master_type', 'parent_key', 'is_active'],
                'tfm_parent_key_lookup_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('tenant_finance_masters', function (Blueprint $table) {
            $table->dropIndex('tfm_parent_key_lookup_index');
            $table->dropColumn('parent_key');
        });
    }
};