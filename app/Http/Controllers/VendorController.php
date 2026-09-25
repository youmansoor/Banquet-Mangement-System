<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        $tenantId = $user->tenant_id;

        $query = Vendor::where(
            'tenant_id',
            $tenantId
        );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'vendor_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'contact_person',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'ntn_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'vendor_type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'city',
                        'like',
                        "%{$search}%"
                    );

                if (is_numeric($search)) {

                    $q->orWhere(
                        'id',
                        $search
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
            $request->has('status') &&
            $request->status !== ''
        ) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VENDORS
        |--------------------------------------------------------------------------
        */

        $vendors = $query
            ->latest('id')
            ->get();

        return view(
            'tenant.vendors.index',
            compact('vendors')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        return view(
            'tenant.vendors.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'vendor_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'contact_person' => [
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

            'vendor_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'opening_balance' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

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
                    'vendors/logos',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE VENDOR
        |--------------------------------------------------------------------------
        */

        Vendor::create([

            'tenant_id' =>
                $user->tenant_id,

            'vendor_name' =>
                $validated['vendor_name'],

            'contact_person' =>
                $validated['contact_person']
                ?? null,

            'email' =>
                $validated['email']
                ?? null,

            'phone' =>
                $validated['phone']
                ?? null,

            'ntn_number' =>
                $validated['ntn_number']
                ?? null,

            'vendor_type' =>
                $validated['vendor_type']
                ?? null,

            'address' =>
                $validated['address']
                ?? null,

            'city' =>
                $validated['city']
                ?? null,

            'opening_balance' =>
                $validated['opening_balance']
                ?? 0,

            'logo' =>
                $logo,

            'notes' =>
                $validated['notes']
                ?? null,

            'status' =>
                $request->has('status')
                    ? $request->boolean('status')
                    : true,
        ]);

        return redirect()
            ->route('tenant.vendors.index')
            ->with(
                'success',
                'Vendor created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Vendor $vendor
    ) {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | TENANT SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $vendor->tenant_id === $user->tenant_id,
            404
        );

        return view(
            'tenant.vendors.show',
            compact('vendor')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Vendor $vendor
    ) {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        abort_unless(
            $vendor->tenant_id === $user->tenant_id,
            404
        );

        return view(
            'tenant.vendors.edit',
            compact('vendor')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Vendor $vendor
    ) {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | TENANT SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $vendor->tenant_id === $user->tenant_id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'vendor_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'contact_person' => [
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

            'vendor_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'opening_balance' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            if ($vendor->logo) {

                Storage::disk('public')
                    ->delete(
                        $vendor->logo
                    );
            }

            $validated['logo'] =
                $request
                    ->file('logo')
                    ->store(
                        'vendors/logos',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            $request->has('status')
                ? $request->boolean('status')
                : $vendor->status;

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['tenant_id']
        );

        $vendor->update(
            $validated
        );

        return redirect()
            ->route(
                'tenant.vendors.index'
            )
            ->with(
                'success',
                'Vendor updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Vendor $vendor
    ) {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        abort_unless(
            $vendor->tenant_id === $user->tenant_id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | DELETE LOGO
        |--------------------------------------------------------------------------
        */

        if ($vendor->logo) {

            Storage::disk('public')
                ->delete(
                    $vendor->logo
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE VENDOR
        |--------------------------------------------------------------------------
        */

        $vendor->delete();

        return redirect()
            ->route(
                'tenant.vendors.index'
            )
            ->with(
                'success',
                'Vendor deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Vendor $vendor
    ) {
        $user = auth()->user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        abort_unless(
            $vendor->tenant_id === $user->tenant_id,
            404
        );

        $vendor->status =
            ! $vendor->status;

        $vendor->save();

        return back()
            ->with(
                'success',
                $vendor->vendor_name .
                ' status changed to ' .
                (
                    $vendor->status
                        ? 'Active'
                        : 'Inactive'
                ) .
                '.'
            );
    }
}