<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantRoleController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $query = Role::with('permissions')
            ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhereNull('tenant_id');
            })
            ->where('guard_name', 'web')
            ->where('name', '!=', 'Super Admin');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        $roles = $query->orderBy('tenant_id', 'desc')->orderBy('name')->get();

        return view('tenant.roles.index', compact('roles'));
    }

    public function create()
    {
        $groupedPermissions = Permission::where('guard_name', 'web')
            ->get()
            ->groupBy(function ($permission) {
                $enum = PermissionEnum::tryFrom($permission->name);

                return $enum ? $enum->module() : 'General';
            });

        return view('tenant.roles.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);

        $role = Role::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])
            ->where('guard_name', 'web')
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('tenant.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $tenantId = Auth::user()->tenant_id;

        // If template role (tenant_id is null), allow clone/customization or edit if tenant owned
        abort_if(
            $role->tenant_id !== null && $role->tenant_id !== $tenantId,
            403,
            'Unauthorized action.'
        );

        $groupedPermissions = Permission::where('guard_name', 'web')
            ->get()
            ->groupBy(function ($permission) {
                $enum = PermissionEnum::tryFrom($permission->name);

                return $enum ? $enum->module() : 'General';
            });

        $rolePermissions = $role->permissions
            ->pluck('id')
            ->toArray();

        return view('tenant.roles.edit', compact(
            'role',
            'groupedPermissions',
            'rolePermissions'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $tenantId = Auth::user()->tenant_id;

        abort_if(
            $role->tenant_id !== null && $role->tenant_id !== $tenantId,
            403,
            'Unauthorized action.'
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);

        // If editing a template role, create a tenant-specific copy
        if (is_null($role->tenant_id)) {
            $role = Role::create([
                'tenant_id' => $tenantId,
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
        } else {
            $role->update([
                'name' => $validated['name'],
            ]);
        }

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])
            ->where('guard_name', 'web')
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('tenant.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $tenantId = Auth::user()->tenant_id;

        abort_if(
            $role->tenant_id !== $tenantId,
            403,
            'Cannot delete system template roles.'
        );

        if ($role->name === 'tenant_owner') {
            return back()->with('error', 'The Tenant Owner role cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('tenant.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
