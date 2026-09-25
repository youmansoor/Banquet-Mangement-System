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
        Schema::create('admin_footer_settings', function (Blueprint $table) {
    $table->id();

    $table->boolean('enabled')
        ->default(true);

    $table->string('footer_text')
        ->nullable();

    $table->string('brand_name')
        ->nullable();

    $table->string('link_text')
        ->nullable();

    $table->string('link_url')
        ->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_footer_settings');
    }
};
