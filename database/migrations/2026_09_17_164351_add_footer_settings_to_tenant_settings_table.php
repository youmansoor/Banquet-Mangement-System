<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('tenant_settings') &&
            ! Schema::hasColumn(
                'tenant_settings',
                'footer_text'
            )
        ) {

            Schema::table(
                'tenant_settings',
                function (Blueprint $table) {

                    $table->string('footer_text')
                        ->nullable()
                        ->after('business_address');

                }
            );
        }


        if (
            Schema::hasTable('tenant_settings') &&
            ! Schema::hasColumn(
                'tenant_settings',
                'footer_link_text'
            )
        ) {

            Schema::table(
                'tenant_settings',
                function (Blueprint $table) {

                    $table->string('footer_link_text')
                        ->nullable()
                        ->after('footer_text');

                }
            );
        }


        if (
            Schema::hasTable('tenant_settings') &&
            ! Schema::hasColumn(
                'tenant_settings',
                'footer_link_url'
            )
        ) {

            Schema::table(
                'tenant_settings',
                function (Blueprint $table) {

                    $table->string(
                        'footer_link_url',
                        2048
                    )
                        ->nullable()
                        ->after('footer_link_text');

                }
            );
        }
    }


    public function down(): void
    {
        if (
            Schema::hasTable('tenant_settings') &&
            Schema::hasColumn(
                'tenant_settings',
                'footer_link_url'
            )
        ) {

            Schema::table(
                'tenant_settings',
                function (Blueprint $table) {

                    $table->dropColumn(
                        'footer_link_url'
                    );

                }
            );
        }


        if (
            Schema::hasTable('tenant_settings') &&
            Schema::hasColumn(
                'tenant_settings',
                'footer_link_text'
            )
        ) {

            Schema::table(
                'tenant_settings',
                function (Blueprint $table) {

                    $table->dropColumn(
                        'footer_link_text'
                    );

                }
            );
        }


        if (
            Schema::hasTable('tenant_settings') &&
            Schema::hasColumn(
                'tenant_settings',
                'footer_text'
            )
        ) {

            Schema::table(
                'tenant_settings',
                function (Blueprint $table) {

                    $table->dropColumn(
                        'footer_text'
                    );

                }
            );
        }
    }
};