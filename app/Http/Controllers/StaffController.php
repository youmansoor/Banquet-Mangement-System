<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class StaffController extends Controller
{
    /**
     * Set current tenant context for Spatie Permission.
     */
    private function setTenantContext(int $tenantId): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($tenantId);
    }

    /**
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $this->setTenantContext($tenantId);

        $query = User::with('roles')
            ->where('tenant_id', $tenantId)
            ->where('role', '!=', 'super_admin');

        /*
        |--------------------------------------------------------------------------
        | SEARCH - Name, Email, Phone, Role
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $searchTerm = trim($request->search);

            $query->where(function ($q) use ($searchTerm) {

                $q->where('name', 'like', "%{$searchTerm}%")

                    ->orWhere('email', 'like', "%{$searchTerm}%")

                    ->orWhere('phone', 'like', "%{$searchTerm}%")

                    ->orWhere('role', 'like', "%{$searchTerm}%")

                    ->orWhereHas('roles', function ($roleQuery) use ($searchTerm) {

                        $roleQuery->where('name', 'like', "%{$searchTerm}%");

                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {

            $query->where(function ($q) use ($request) {

                $q->where('role', $request->role)

                    ->orWhereHas('roles', function ($roleQuery) use ($request) {

                        $roleQuery->where('id', $request->role);

                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $staff = $query
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | ROLES FOR FILTER DROPDOWN
        |--------------------------------------------------------------------------
        */

        $roles = Role::where(function ($query) use ($tenantId) {

            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');

        })
            ->where('guard_name', 'web')
            ->where('name', '!=', 'Super Admin')
            ->orderBy('name')
            ->get();

        return view(
            'tenant.staff.index',
            compact(
                'staff',
                'roles'
            )
        );
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id;

        $this->setTenantContext($tenantId);

        $roles = Role::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->where('guard_name', 'web')
            ->where('name', '!=', 'Super Admin')
            ->orderBy('name')
            ->get();

        return view('tenant.staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $this->setTenantContext($tenantId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => 'nullable|string|max:20',

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'password' => 'required|string|min:6',

            'status' => 'required|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET ONLY THIS TENANT'S ROLE
        |--------------------------------------------------------------------------
        */

        $role = Role::where('id', $validated['role_id'])
            ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhereNull('tenant_id');
            })
            ->where('guard_name', 'web')
            ->where('name', '!=', 'Super Admin')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $role->name,
            'status' => (bool) $validated['status'],
            'password' => Hash::make($validated['password']),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ASSIGN SPATIE ROLE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Tenant context is already set above.
        |
        */

        $user->syncRoles([$role]);

        return redirect()
            ->route('tenant.staff.index')
            ->with(
                'success',
                'Staff member added successfully.'
            );
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        $tenantId = Auth::user()->tenant_id;

        $this->setTenantContext($tenantId);

        abort_unless(
            $staff->tenant_id === $tenantId,
            403,
            'Unauthorized action.'
        );

        $roles = Role::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->where('guard_name', 'web')
            ->where('name', '!=', 'Super Admin')
            ->orderBy('name')
            ->get();

        $currentRoleId = $staff->roles
            ->first()?->id;

        return view(
            'tenant.staff.edit',
            compact(
                'staff',
                'roles',
                'currentRoleId'
            )
        );
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff)
    {
        $tenantId = Auth::user()->tenant_id;

        $this->setTenantContext($tenantId);

        abort_unless(
            $staff->tenant_id === $tenantId,
            403,
            'Unauthorized action.'
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($staff->id),
            ],

            'phone' => 'nullable|string|max:20',

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'password' => 'nullable|string|min:6',

            'status' => 'required|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET ONLY THIS TENANT'S ROLE
        |--------------------------------------------------------------------------
        */

        $role = Role::where('id', $validated['role_id'])
            ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhereNull('tenant_id');
            })
            ->where('guard_name', 'web')
            ->where('name', '!=', 'Super Admin')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $role->name,
            'status' => (bool) $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make(
                $validated['password']
            );
        }

        $staff->update($updateData);

        /*
        |--------------------------------------------------------------------------
        | SYNC SPATIE ROLE
        |--------------------------------------------------------------------------
        */

        $staff->syncRoles([$role]);

        return redirect()
            ->route('tenant.staff.index')
            ->with(
                'success',
                'Staff member updated successfully.'
            );
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $staff)
    {
        $tenantId = Auth::user()->tenant_id;

        $this->setTenantContext($tenantId);

        abort_unless(
            $staff->tenant_id === $tenantId,
            403,
            'Unauthorized action.'
        );

        if ($staff->id === Auth::id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        if ($staff->role === 'tenant_owner') {
            return back()->with(
                'error',
                'The primary Tenant Owner account cannot be deleted.'
            );
        }

        $staff->delete();

        return redirect()
            ->route('tenant.staff.index')
            ->with(
                'success',
                'Staff member deleted successfully.'
            );
    }
}
