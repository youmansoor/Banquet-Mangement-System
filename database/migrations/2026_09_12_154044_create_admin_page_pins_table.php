<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_page_pins', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('page_key');

            $table->string('page_name');

            $table->string('pin_hash')->nullable();

            $table->boolean('enabled')->default(false);

            $table->timestamps();

            $table->unique([
                'user_id',
                'page_key',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_page_pins');
    }
};
