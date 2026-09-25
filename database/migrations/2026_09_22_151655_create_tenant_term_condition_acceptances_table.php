<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_term_condition_acceptances', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('term_condition_id')
                ->constrained('term_conditions')
                ->cascadeOnDelete();

            $table->timestamp('accepted_at')->nullable();

            $table->timestamps();

            // Short unique index name
            $table->unique(
                ['tenant_id', 'term_condition_id'],
                'tenant_term_unique'
            );

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_term_condition_acceptances');
    }
};