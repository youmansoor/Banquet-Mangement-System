<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_banks', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Bank Information
            |--------------------------------------------------------------------------
            */

            $table->string('bank_name');

            $table->decimal(
                'opening_balance',
                15,
                2
            )->default(0);

            $table->decimal(
                'current_balance',
                15,
                2
            )->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Foreign key check ko temporary off kiya taake dependent tables ki waja se drop error na aaye
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::dropIfExists('tenant_banks');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};