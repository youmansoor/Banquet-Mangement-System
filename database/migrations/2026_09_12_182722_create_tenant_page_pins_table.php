<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenant_page_pins')) {

            Schema::create('tenant_page_pins', function (Blueprint $table) {

                $table->id();

                $table->foreignId('tenant_id')
                    ->constrained('tenants')
                    ->cascadeOnDelete();

                $table->string('page_key');

                $table->string('page_name');

                $table->string('pin_hash')->nullable();

                $table->boolean('enabled')
                    ->default(false);

                $table->timestamps();

                $table->unique([
                    'tenant_id',
                    'page_key',
                ]);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_page_pins');
    }
};
