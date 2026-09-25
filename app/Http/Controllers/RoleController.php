<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display roles.
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions')
            ->where('guard_name', 'web');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        $roles = $query->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show create role form.
     */
    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Group permissions according to PermissionEnum modules
        |--------------------------------------------------------------------------
        */

        $groupedPermissions = [];

        foreach (PermissionEnum::cases() as $enumPermission) {

            $permission = $permissions->firstWhere(
                'name',
                $enumPermission->value
            );

            if (! $permission) {
                continue;
            }

            $module = $enumPermission->module();

            $groupedPermissions[$module][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'label' => $enumPermission->label(),
            ];
        }

        return view('roles.create', compact(
            'groupedPermissions'
        ));
    }

    /**
     * Store new role.
     */
    public function store(Request $request)
    {
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
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $permissions = Permission::whereIn(
            'id',
            $validated['permissions'] ?? []
        )
            ->where('guard_name', 'web')
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show edit role form.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('id')
            ->get();

        $groupedPermissions = [];

        foreach (PermissionEnum::cases() as $enumPermission) {

            $permission = $permissions->firstWhere(
                'name',
                $enumPermission->value
            );

            if (! $permission) {
                continue;
            }

            $module = $enumPermission->module();

            $groupedPermissions[$module][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'label' => $enumPermission->label(),
            ];
        }

        $rolePermissions = $role->permissions
            ->pluck('id')
            ->toArray();

        return view('roles.edit', compact(
            'role',
            'groupedPermissions',
            'rolePermissions'
        ));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role)
    {
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
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $permissions = Permission::whereIn(
            'id',
            $validated['permissions'] ?? []
        )
            ->where('guard_name', 'web')
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with(
                'error',
                'Super Admin role cannot be deleted.'
            );
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
