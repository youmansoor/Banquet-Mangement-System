<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ServiceController extends Controller
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

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $query = Service::with('vendor')
            ->where(
                'tenant_id',
                $tenantId
            );

        /*
        |--------------------------------------------------------------------------
        | SERVICE NAME SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('service_name')) {

            $serviceName = trim(
                $request->service_name
            );

            $query->where(
                'service_name',
                'like',
                '%'.$serviceName.'%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SERVICE UNIT SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('service_unit')) {

            $serviceUnit = trim(
                $request->service_unit
            );

            $query->where(
                'service_unit',
                'like',
                '%'.$serviceUnit.'%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MINIMUM AMOUNT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_amount')) {

            $query->where(
                'amount',
                '>=',
                $request->min_amount
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MAXIMUM AMOUNT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_amount')) {

            $query->where(
                'amount',
                '<=',
                $request->max_amount
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VENDOR FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('vendor_id')) {

            $query->where(
                'vendor_id',
                $request->vendor_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GET SERVICES
        |--------------------------------------------------------------------------
        */

        $services = $query
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VENDORS
        |--------------------------------------------------------------------------
        |
        | Sirf current tenant ke vendors.
        |
        */

        $vendors = Vendor::where(
            'tenant_id',
            $tenantId
        )
            ->where('status', true)
            ->orderBy('vendor_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'services.index',
            compact(
                'services',
                'vendors'
            )
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

        $vendors = Vendor::where(
            'tenant_id',
            $user->tenant_id
        )
            ->where('status', true)
            ->orderBy('vendor_name')
            ->get();

        return view(
            'services.create',
            compact('vendors')
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

        $tenantId = $user->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'service_name' => [
                'required',
                'string',
                'max:255',
            ],

            'service_unit' => [
                'required',
                'string',
                'max:100',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'vendor_id' => [
                'nullable',
                'integer',
                'exists:vendors,id',
            ],

            'purchase_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_status' => [
                'required',
                'in:paid,unpaid',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFY VENDOR BELONGS TO CURRENT TENANT
        |--------------------------------------------------------------------------
        */

        $vendor = null;

        if (! empty($validated['vendor_id'])) {

            $vendor = Vendor::where(
                'id',
                $validated['vendor_id']
            )
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->where(
                    'status',
                    true
                )
                ->first();

            if (! $vendor) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'vendor_id' => 'Selected vendor does not belong to your tenant.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE SERVICE
        |--------------------------------------------------------------------------
        */

        Service::create([

            'tenant_id' => $tenantId,

            'vendor_id' => $validated['vendor_id'] ?? null,

            'service_name' => trim(
                $validated['service_name']
            ),

            'service_unit' => trim(
                $validated['service_unit']
            ),

            'amount' => $validated['amount'],

            'purchase_amount' => $validated['purchase_amount'] ?? 0,

            'payment_status' => $validated['payment_status'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('services.index')
            ->with(
                'success',
                'Service created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Service $service)
    {
        $this->checkTenant($service);

        $service->load('vendor');

        return view(
            'services.show',
            compact('service')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Service $service)
    {
        $this->checkTenant($service);

        $vendors = Vendor::where(
            'tenant_id',
            auth()->user()->tenant_id
        )
            ->where('status', true)
            ->orderBy('vendor_name')
            ->get();

        return view(
            'services.edit',
            compact(
                'service',
                'vendors'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Service $service
    ) {

        $this->checkTenant($service);

        $tenantId = auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'service_name' => [
                'required',
                'string',
                'max:255',
            ],

            'service_unit' => [
                'required',
                'string',
                'max:100',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'vendor_id' => [
                'nullable',
                'integer',
                'exists:vendors,id',
            ],

            'purchase_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_status' => [
                'required',
                'in:paid,unpaid',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFY VENDOR
        |--------------------------------------------------------------------------
        */

        if (! empty($validated['vendor_id'])) {

            $vendorExists = Vendor::where(
                'id',
                $validated['vendor_id']
            )
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->where(
                    'status',
                    true
                )
                ->exists();

            if (! $vendorExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'vendor_id' => 'Selected vendor does not belong to your tenant.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $service->update([

            'vendor_id' => $validated['vendor_id'] ?? null,

            'service_name' => trim(
                $validated['service_name']
            ),

            'service_unit' => trim(
                $validated['service_unit']
            ),

            'amount' => $validated['amount'],

            'purchase_amount' => $validated['purchase_amount'] ?? 0,

            'payment_status' => $validated['payment_status'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('services.index')
            ->with(
                'success',
                'Service updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Service $service)
    {
        $this->checkTenant($service);

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with(
                'success',
                'Service deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT SECURITY
    |--------------------------------------------------------------------------
    */

    private function checkTenant(
        Service $service
    ): void {

        if (
            (int) $service->tenant_id
            !== (int) auth()->user()->tenant_id
        ) {

            abort(403);
        }
    }
}
