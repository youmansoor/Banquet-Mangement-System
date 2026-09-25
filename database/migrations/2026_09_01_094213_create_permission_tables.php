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
        $teams = config('permission.teams');

        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $teamForeignKey = $columnNames['team_foreign_key'] ?? 'tenant_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        throw_if(
            empty($tableNames),
            Exception::class,
            'Error: config/permission.php not loaded.'
        );

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Schema::create(
            $tableNames['permissions'],
            function (Blueprint $table) {

                $table->bigIncrements('id');

                $table->string('name');

                $table->string('guard_name');

                $table->timestamps();

                $table->unique(
                    [
                        'name',
                        'guard_name',
                    ],
                    'permissions_name_guard_unique'
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        Schema::create(
            $tableNames['roles'],
            function (Blueprint $table) use (
                $teams,
                $teamForeignKey
            ) {

                $table->bigIncrements('id');

                /*
                |--------------------------------------------------------------------------
                | TENANT
                |--------------------------------------------------------------------------
                |
                | NULL = Global role
                |
                | Example:
                | Super Admin
                |
                | tenant_id = actual tenant
                |
                | Example:
                | tenant_owner
                | branch_manager
                |
                */

                if ($teams) {

                    $table
                        ->unsignedBigInteger($teamForeignKey)
                        ->nullable();

                    $table->index(
                        $teamForeignKey,
                        'roles_tenant_id_index'
                    );
                }

                $table->string('name');

                $table->string('guard_name');

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | ROLE UNIQUE INDEX
                |--------------------------------------------------------------------------
                */

                if ($teams) {

                    $table->unique(
                        [
                            $teamForeignKey,
                            'name',
                            'guard_name',
                        ],
                        'roles_tenant_name_guard_unique'
                    );

                } else {

                    $table->unique(
                        [
                            'name',
                            'guard_name',
                        ],
                        'roles_name_guard_unique'
                    );
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | MODEL HAS PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Schema::create(
            $tableNames['model_has_permissions'],
            function (Blueprint $table) use (
                $tableNames,
                $pivotPermission,
                $teams,
                $teamForeignKey,
                $modelMorphKey
            ) {

                /*
                |--------------------------------------------------------------------------
                | PERMISSION ID
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    $pivotPermission
                );

                /*
                |--------------------------------------------------------------------------
                | MODEL
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'model_type'
                );

                $table->unsignedBigInteger(
                    $modelMorphKey
                );

                /*
                |--------------------------------------------------------------------------
                | MODEL INDEX
                |--------------------------------------------------------------------------
                */

                $table->index(
                    [
                        $modelMorphKey,
                        'model_type',
                    ],
                    'model_has_permissions_model_id_model_type_index'
                );

                /*
                |--------------------------------------------------------------------------
                | PERMISSION FOREIGN KEY
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    $pivotPermission
                )
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                /*
                |--------------------------------------------------------------------------
                | TENANT
                |--------------------------------------------------------------------------
                */

                if ($teams) {

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |--------------------------------------------------------------------------
                    |
                    | NULL = Global / Super Admin
                    |
                    | Actual tenant ID = Tenant user
                    |
                    */

                    $table
                        ->unsignedBigInteger($teamForeignKey)
                        ->nullable();

                    $table->index(
                        $teamForeignKey,
                        'model_has_permissions_tenant_id_index'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | UNIQUE
                    |--------------------------------------------------------------------------
                    |
                    | tenant_id is NOT part of PRIMARY KEY because
                    | tenant_id can be NULL.
                    |
                    */

                    $table->unique(
                        [
                            $teamForeignKey,
                            $pivotPermission,
                            $modelMorphKey,
                            'model_type',
                        ],
                        'model_has_permissions_tenant_permission_model_unique'
                    );

                } else {

                    $table->primary(
                        [
                            $pivotPermission,
                            $modelMorphKey,
                            'model_type',
                        ],
                        'model_has_permissions_primary'
                    );
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | MODEL HAS ROLES
        |--------------------------------------------------------------------------
        */

        Schema::create(
            $tableNames['model_has_roles'],
            function (Blueprint $table) use (
                $tableNames,
                $pivotRole,
                $teams,
                $teamForeignKey,
                $modelMorphKey
            ) {

                /*
                |--------------------------------------------------------------------------
                | ROLE ID
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    $pivotRole
                );

                /*
                |--------------------------------------------------------------------------
                | MODEL
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'model_type'
                );

                $table->unsignedBigInteger(
                    $modelMorphKey
                );

                /*
                |--------------------------------------------------------------------------
                | MODEL INDEX
                |--------------------------------------------------------------------------
                */

                $table->index(
                    [
                        $modelMorphKey,
                        'model_type',
                    ],
                    'model_has_roles_model_id_model_type_index'
                );

                /*
                |--------------------------------------------------------------------------
                | ROLE FOREIGN KEY
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    $pivotRole
                )
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');

                /*
                |--------------------------------------------------------------------------
                | TENANT
                |--------------------------------------------------------------------------
                */

                if ($teams) {

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |--------------------------------------------------------------------------
                    |
                    | Super Admin:
                    |
                    | tenant_id = NULL
                    |
                    | Tenant user:
                    |
                    | tenant_id = actual tenant ID
                    |
                    */

                    $table
                        ->unsignedBigInteger($teamForeignKey)
                        ->nullable();

                    $table->index(
                        $teamForeignKey,
                        'model_has_roles_tenant_id_index'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | UNIQUE
                    |--------------------------------------------------------------------------
                    |
                    | DO NOT put tenant_id in PRIMARY KEY.
                    |
                    | tenant_id is nullable.
                    |
                    */

                    $table->unique(
                        [
                            $teamForeignKey,
                            $pivotRole,
                            $modelMorphKey,
                            'model_type',
                        ],
                        'model_has_roles_tenant_role_model_unique'
                    );

                } else {

                    $table->primary(
                        [
                            $pivotRole,
                            $modelMorphKey,
                            'model_type',
                        ],
                        'model_has_roles_primary'
                    );
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ROLE HAS PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Schema::create(
            $tableNames['role_has_permissions'],
            function (Blueprint $table) use (
                $tableNames,
                $pivotRole,
                $pivotPermission
            ) {

                /*
                |--------------------------------------------------------------------------
                | PERMISSION ID
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    $pivotPermission
                );

                /*
                |--------------------------------------------------------------------------
                | ROLE ID
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    $pivotRole
                );

                /*
                |--------------------------------------------------------------------------
                | PERMISSION FOREIGN KEY
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    $pivotPermission
                )
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                /*
                |--------------------------------------------------------------------------
                | ROLE FOREIGN KEY
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    $pivotRole
                )
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');

                /*
                |--------------------------------------------------------------------------
                | PRIMARY KEY
                |--------------------------------------------------------------------------
                */

                $table->primary(
                    [
                        $pivotPermission,
                        $pivotRole,
                    ],
                    'role_has_permissions_primary'
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | CLEAR PERMISSION CACHE
        |--------------------------------------------------------------------------
        */

        app('cache')
            ->store(
                config('permission.cache.store') !== 'default'
                    ? config('permission.cache.store')
                    : null
            )
            ->forget(
                config('permission.cache.key')
            );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        throw_if(
            empty($tableNames),
            Exception::class,
            'Error: config/permission.php not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | DROP ROLE HAS PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            $tableNames['role_has_permissions']
        );

        /*
        |--------------------------------------------------------------------------
        | DROP MODEL HAS ROLES
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            $tableNames['model_has_roles']
        );

        /*
        |--------------------------------------------------------------------------
        | DROP MODEL HAS PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            $tableNames['model_has_permissions']
        );

        /*
        |--------------------------------------------------------------------------
        | DROP ROLES
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            $tableNames['roles']
        );

        /*
        |--------------------------------------------------------------------------
        | DROP PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            $tableNames['permissions']
        );
    }
};
