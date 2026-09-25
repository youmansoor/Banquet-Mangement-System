<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('free_services', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->string('service_name');

            $table->string('service_unit', 100);

            $table->boolean('status')
                ->default(true);

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | UNIQUE SERVICE NAME PER TENANT
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'tenant_id',
                'service_name',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_services');
    }
};