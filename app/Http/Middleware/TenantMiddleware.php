<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | User must be authenticated
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        |
        | Super Admin is a global user and does not belong to a tenant.
        |
        */

        if ($user->isSuperAdmin()) {

            app(PermissionRegistrar::class)
                ->setPermissionsTeamId(null);

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Tenant User Must Have tenant_id
        |--------------------------------------------------------------------------
        */

        if (! $user->tenant_id) {
            abort(403, 'You are not associated with any business.');
        }

        /*
        |--------------------------------------------------------------------------
        | Tenant Must Exist
        |--------------------------------------------------------------------------
        */

        $tenant = $user->tenant;

        if (! $tenant) {
            auth()->logout();

            return redirect()
                ->route('login')
                ->with('error', 'Your business account could not be found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Tenant Must Be Active
        |--------------------------------------------------------------------------
        */

        if (! $tenant->status) {
            auth()->logout();

            return redirect()
                ->route('login')
                ->with('error', 'Your business account is inactive.');
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT: Set Spatie Permission Tenant Context
        |--------------------------------------------------------------------------
        */

        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($user->tenant_id);

        /*
        |--------------------------------------------------------------------------
        | Clear Previously Loaded Roles
        |--------------------------------------------------------------------------
        |
        | This is important when switching between users/tenants.
        |
        */

        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');

        return $next($request);
    }
}
