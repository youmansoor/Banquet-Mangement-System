<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'tenant_finance_masters',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | TENANT
                |--------------------------------------------------------------------------
                */

                $table->foreignId('tenant_id')
                    ->constrained('tenants')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | MASTER TYPE
                |--------------------------------------------------------------------------
                */

                $table->string('master_type');


                /*
                |--------------------------------------------------------------------------
                | PARENT
                |--------------------------------------------------------------------------
                |
                | Payment Category ke liye:
                | parent_id = Transaction Type ID
                |
                */

                $table->foreignId('parent_id')
                    ->nullable()
                    ->constrained(
                        'tenant_finance_masters'
                    )
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | NAME
                |--------------------------------------------------------------------------
                */

                $table->string('name');


                /*
                |--------------------------------------------------------------------------
                | SORT ORDER
                |--------------------------------------------------------------------------
                */

                $table->unsignedInteger('sort_order')
                    ->default(0);


                /*
                |--------------------------------------------------------------------------
                | ACTIVE
                |--------------------------------------------------------------------------
                */

                $table->boolean('is_active')
                    ->default(true);


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | UNIQUE MASTER NAME
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'tenant_id',
                        'master_type',
                        'name',
                    ],
                    'tfm_tenant_type_name_unique'
                );


                /*
                |--------------------------------------------------------------------------
                | SEARCH INDEX
                |--------------------------------------------------------------------------
                |
                | Short explicit name use kiya gaya hai.
                |
                */

                $table->index(
                    [
                        'tenant_id',
                        'master_type',
                        'parent_id',
                        'is_active',
                    ],
                    'tfm_lookup_index'
                );
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'tenant_finance_masters'
        );
    }
};