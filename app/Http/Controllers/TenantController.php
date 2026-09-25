<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\SubscriptionDue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TenantController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Tenant::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $searchTerm = trim(
                $request->search
            );

            $query->where(function ($q) use (
                $searchTerm
            ) {

                $q->where(
                    'business_name',
                    'like',
                    "%{$searchTerm}%"
                )
                    ->orWhere(
                        'owner_name',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'ntn_number',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'nic_number',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'business_address',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'home_address',
                        'like',
                        "%{$searchTerm}%"
                    )
                    ->orWhere(
                        'address',
                        'like',
                        "%{$searchTerm}%"
                    );

                /*
                |--------------------------------------------------------------------------
                | ID SEARCH
                |--------------------------------------------------------------------------
                */

                if (is_numeric($searchTerm)) {

                    $q->orWhere(
                        'id',
                        $searchTerm
                    );
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->has('status')
            &&
            $request->status !== ''
        ) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CREATED DATE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('created_date')) {

            $query->whereDate(
                'created_at',
                $request->created_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TENANTS
        |--------------------------------------------------------------------------
        */

        $tenants = $query
            ->orderByDesc('id')
            ->get();

        return view(
            'tenants.index',
            compact('tenants')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'tenants.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | BUSINESS INFORMATION
            |--------------------------------------------------------------------------
            */

            'business_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'owner_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            /*
            |--------------------------------------------------------------------------
            | NTN
            |--------------------------------------------------------------------------
            */

            'ntn_number' => [
                'nullable',
                'string',
                'max:15',
            ],

            /*
            |--------------------------------------------------------------------------
            | NIC
            |--------------------------------------------------------------------------
            */

            'nic_number' => [
                'nullable',
                'string',
                'max:12',
            ],

            /*
            |--------------------------------------------------------------------------
            | BUSINESS ADDRESS
            |--------------------------------------------------------------------------
            */

            'business_address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | HOME ADDRESS
            |--------------------------------------------------------------------------
            */

            'home_address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            /*
            |--------------------------------------------------------------------------
            | OWNER LOGIN
            |--------------------------------------------------------------------------
            */

            'owner_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'owner_password' => [
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $request,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | RESET SPATIE TEAM CONTEXT
                |--------------------------------------------------------------------------
                */

                app(
                    PermissionRegistrar::class
                )->setPermissionsTeamId(null);

                /*
                |--------------------------------------------------------------------------
                | LOGO
                |--------------------------------------------------------------------------
                */

                $logo = null;

                if ($request->hasFile('logo')) {

                    $logo = $request
                        ->file('logo')
                        ->store(
                            'tenants/logos',
                            'public'
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | BUSINESS ADDRESS
                |--------------------------------------------------------------------------
                */

                $businessAddress =
                    $validated['business_address']
                    ?? null;

                /*
                |--------------------------------------------------------------------------
                | CREATE TENANT
                |--------------------------------------------------------------------------
                */

                $tenant = Tenant::create([

                    'business_name' =>
                        $validated['business_name'],

                    'owner_name' =>
                        $validated['owner_name'],

                    'email' =>
                        $validated['email']
                        ?? null,

                    'phone' =>
                        $validated['phone']
                        ?? null,

                    'ntn_number' =>
                        $validated['ntn_number']
                        ?? null,

                    'nic_number' =>
                        $validated['nic_number']
                        ?? null,

                    'business_address' =>
                        $businessAddress,

                    'home_address' =>
                        $validated['home_address']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | OLD ADDRESS FIELD
                    |--------------------------------------------------------------------------
                    |
                    | Existing pages/code agar $tenant->address
                    | use karte hain to woh bhi work kare.
                    |
                    */

                    'address' =>
                        $businessAddress,

                    'logo' =>
                        $logo,

                    'status' =>
                        true,
                ]);

                /*
                |--------------------------------------------------------------------------
                | SET SPATIE TENANT CONTEXT
                |--------------------------------------------------------------------------
                */

                app(
                    PermissionRegistrar::class
                )->setPermissionsTeamId(
                    $tenant->id
                );

                /*
                |--------------------------------------------------------------------------
                | CREATE OWNER ROLE
                |--------------------------------------------------------------------------
                */

                $ownerRole = Role::firstOrCreate(
                    [
                        'tenant_id' =>
                            $tenant->id,

                        'name' =>
                            'tenant_owner',

                        'guard_name' =>
                            'web',
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | OWNER PERMISSIONS
                |--------------------------------------------------------------------------
                */

                $permissions = Permission::where(
                    'guard_name',
                    'web'
                )->get();

                $ownerRole->syncPermissions(
                    $permissions
                );

                /*
                |--------------------------------------------------------------------------
                | CREATE OWNER USER
                |--------------------------------------------------------------------------
                */

                $owner = User::create([

                    'tenant_id' =>
                        $tenant->id,

                    'name' =>
                        $validated['owner_name'],

                    'email' =>
                        $validated['owner_email'],

                    'phone' =>
                        $validated['phone']
                        ?? null,

                    'role' =>
                        'tenant_owner',

                    'status' =>
                        true,

                    'password' =>
                        Hash::make(
                            $validated['owner_password']
                        ),
                ]);

                /*
                |--------------------------------------------------------------------------
                | ASSIGN OWNER ROLE
                |--------------------------------------------------------------------------
                */

                $owner->assignRole(
                    $ownerRole
                );

                /*
                |--------------------------------------------------------------------------
                | RESET TEAM CONTEXT
                |--------------------------------------------------------------------------
                */

                app(
                    PermissionRegistrar::class
                )->setPermissionsTeamId(null);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.tenants.index'
            )
            ->with(
                'success',
                'Tenant and owner account created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| SHOW
|--------------------------------------------------------------------------
*/

public function show(
    Tenant $tenant
) {

    /*
    |--------------------------------------------------------------------------
    | OWNER ACCOUNT
    |--------------------------------------------------------------------------
    */

    $owner = User::where(
        'tenant_id',
        $tenant->id
    )
        ->where(
            'role',
            'tenant_owner'
        )
        ->first();


    /*
    |--------------------------------------------------------------------------
    | ACTIVE SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    $activeSubscription =
        $tenant->activeSubscription;


    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTION OUTSTANDING
    |--------------------------------------------------------------------------
    */

    $subscriptionOutstanding = 0;

    if ($activeSubscription) {

        $subscriptionOutstanding =
            SubscriptionDue::where(
                'tenant_id',
                $tenant->id
            )
                ->where(
                    'subscription_id',
                    $activeSubscription->id
                )
                ->sum(
                    'remaining_amount'
                );
    }


    /*
    |--------------------------------------------------------------------------
    | TENANT USERS
    |--------------------------------------------------------------------------
    */

    $tenantUsers = User::where(
        'tenant_id',
        $tenant->id
    )
        ->orderBy(
            'id',
            'asc'
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | SHOW TENANT
    |--------------------------------------------------------------------------
    */

    return view(
        'tenants.show',
        compact(
            'tenant',
            'owner',
            'activeSubscription',
            'subscriptionOutstanding',
            'tenantUsers'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Tenant $tenant
    ) {
        return view(
            'tenants.edit',
            compact('tenant')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Tenant $tenant
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'business_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'owner_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'ntn_number' => [
                'nullable',
                'string',
                'max:15',
            ],

            'nic_number' => [
                'nullable',
                'string',
                'max:12',
            ],

            'business_address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'home_address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:72',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $request,
                $validated,
                $tenant
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOGO UPDATE
                |--------------------------------------------------------------------------
                */

                if ($request->hasFile('logo')) {

                    if ($tenant->logo) {

                        Storage::disk('public')
                            ->delete(
                                $tenant->logo
                            );
                    }

                    $validated['logo'] =
                        $request
                            ->file('logo')
                            ->store(
                                'tenants/logos',
                                'public'
                            );
                }

                /*
                |--------------------------------------------------------------------------
                | LEGACY ADDRESS
                |--------------------------------------------------------------------------
                */

                $validated['address'] =
                    $validated['business_address']
                    ?? null;

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $validated['status'] =
                    $request->has('status')
                        ? $request->boolean('status')
                        : $tenant->status;

                /*
                |--------------------------------------------------------------------------
                | UPDATE TENANT
                |--------------------------------------------------------------------------
                */

                $tenant->update(
                    $validated
                );

                /*
                |--------------------------------------------------------------------------
                | OWNER PASSWORD
                |--------------------------------------------------------------------------
                */

                if (
                    $request->filled(
                        'password'
                    )
                ) {

                    $owner = $tenant
                        ->users()
                        ->where(
                            'role',
                            'tenant_owner'
                        )
                        ->first();

                    if ($owner) {

                        $owner->password =
                            Hash::make(
                                $request->password
                            );

                        $owner->save();
                    }
                }
            }
        );

        return redirect()
            ->route(
                'admin.tenants.index'
            )
            ->with(
                'success',
                'Tenant updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Tenant $tenant
    ) {

        /*
        |--------------------------------------------------------------------------
        | DELETE LOGO
        |--------------------------------------------------------------------------
        */

        if ($tenant->logo) {

            Storage::disk('public')
                ->delete(
                    $tenant->logo
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE TENANT
        |--------------------------------------------------------------------------
        */

        $tenant->delete();

        return redirect()
            ->route(
                'admin.tenants.index'
            )
            ->with(
                'success',
                'Tenant deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        $tenant = $user->tenant;

        return view(
            'tenant.profile',
            compact(
                'tenant',
                'user'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TENANT PROFILE
    |--------------------------------------------------------------------------
    */

    public function updateProfile(
        Request $request
    ) {

        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,'
                    . $user->id,
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'current_password' => [
                'nullable',
                'required_with:password',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:72',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | USER UPDATE
        |--------------------------------------------------------------------------
        */

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->phone =
            $validated['phone']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (
            ! empty(
                $validated['password']
            )
        ) {

            if (
                empty(
                    $validated['current_password']
                )
                ||
                ! Hash::check(
                    $validated['current_password'],
                    $user->password
                )
            ) {

                return back()
                    ->withErrors([
                        'current_password' =>
                            'Current password is incorrect.',
                    ])
                    ->withInput();
            }

            $user->password =
                Hash::make(
                    $validated['password']
                );
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | SYNC OWNER INFORMATION
        |--------------------------------------------------------------------------
        */

        $tenant = $user->tenant;

        if (
            $tenant
            &&
            $user->isOwner()
        ) {

            $tenant->update([
                'owner_name' =>
                    $user->name,

                'phone' =>
                    $user->phone,
            ]);
        }

        return redirect()
            ->route(
                'tenant.settings'
            )
            ->with(
                'profile_success',
                'Your profile has been updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Tenant $tenant
    ) {

        $tenant->status =
            ! $tenant->status;

        $tenant->save();

        return redirect()
            ->back()
            ->with(
                'success',
                $tenant->business_name
                    . ' status changed to '
                    . (
                        $tenant->status
                            ? 'Active'
                            : 'Inactive'
                    )
                    . '.'
            );
    }
}