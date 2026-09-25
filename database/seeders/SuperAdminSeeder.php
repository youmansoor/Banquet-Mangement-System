<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Reset Spatie team context
        |--------------------------------------------------------------------------
        |
        | Super Admin is a platform-level user and does not belong
        | to any tenant.
        |
        */

        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        /*
        |--------------------------------------------------------------------------
        | Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdminRole = Role::firstOrCreate(
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'tenant_id' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Super Admin User
        |--------------------------------------------------------------------------
        */

        $user = User::updateOrCreate(
            [
                'email' => 'admin@banquet.test',
            ],
            [
                'tenant_id' => null,
                'name' => 'Super Admin',
                'phone' => null,
                'role' => 'super_admin',
                'status' => true,
                'password' => 'password',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Assign Global Super Admin Role
        |--------------------------------------------------------------------------
        */

        $user->syncRoles([$superAdminRole]);
    }
}
