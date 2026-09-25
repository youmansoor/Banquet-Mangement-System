<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        $teamKey = $columnNames['team_foreign_key'] ?? 'tenant_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        /*
        |--------------------------------------------------------------------------
        | model_has_roles
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn(
            $tableNames['model_has_roles'],
            $teamKey
        )) {
            Schema::table(
                $tableNames['model_has_roles'],
                function (Blueprint $table) use (
                    $teamKey,
                    $pivotRole
                ) {
                    $table
                        ->unsignedBigInteger($teamKey)
                        ->nullable()
                        ->after($pivotRole);

                    $table->index(
                        $teamKey,
                        'model_has_roles_tenant_id_index'
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | model_has_permissions
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn(
            $tableNames['model_has_permissions'],
            $teamKey
        )) {
            Schema::table(
                $tableNames['model_has_permissions'],
                function (Blueprint $table) use (
                    $teamKey,
                    $pivotPermission
                ) {
                    $table
                        ->unsignedBigInteger($teamKey)
                        ->nullable()
                        ->after($pivotPermission);

                    $table->index(
                        $teamKey,
                        'model_has_permissions_tenant_id_index'
                    );
                }
            );
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        $teamKey = $columnNames['team_foreign_key'] ?? 'tenant_id';

        /*
        |--------------------------------------------------------------------------
        | model_has_roles
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn(
            $tableNames['model_has_roles'],
            $teamKey
        )) {
            Schema::table(
                $tableNames['model_has_roles'],
                function (Blueprint $table) use ($teamKey) {
                    $table->dropIndex(
                        'model_has_roles_tenant_id_index'
                    );

                    $table->dropColumn($teamKey);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | model_has_permissions
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn(
            $tableNames['model_has_permissions'],
            $teamKey
        )) {
            Schema::table(
                $tableNames['model_has_permissions'],
                function (Blueprint $table) use ($teamKey) {
                    $table->dropIndex(
                        'model_has_permissions_tenant_id_index'
                    );

                    $table->dropColumn($teamKey);
                }
            );
        }
    }
};
