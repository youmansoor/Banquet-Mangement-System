<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawn_types', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->string('lawn_type');

            $table->timestamps();

            $table->unique(['tenant_id', 'lawn_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawn_types');
    }
};
