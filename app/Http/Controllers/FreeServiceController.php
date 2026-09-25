<?php

namespace App\Http\Controllers;

use App\Models\FreeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FreeServiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $query = FreeService::where(
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
                    'service_name',
                    'like',
                    '%'.$search.'%'
                )
                    ->orWhere(
                        'service_unit',
                        'like',
                        '%'.$search.'%'
                    );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
        ) {

            $query->where(
                'status',
                $request->status
            );

        }

        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        $freeServices = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'services.free-services',
            compact('freeServices')
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
            'services.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {

        $tenantId =
            auth()->user()->tenant_id;

        $validated = $request->validate([

            'service_name' => [
                'required',
                'string',
                'max:255',

                Rule::unique(
                    'free_services',
                    'service_name'
                )->where(function ($query) use ($tenantId) {

                    return $query->where(
                        'tenant_id',
                        $tenantId
                    );

                }),
            ],

            'service_unit' => [
                'required',
                'string',
                'max:100',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE FREE SERVICE
        |--------------------------------------------------------------------------
        */

        FreeService::create([

            'tenant_id' => $tenantId,

            'service_name' => trim(
                $validated['service_name']
            ),

            'service_unit' => trim(
                $validated['service_unit']
            ),

            'status' => true,
        ]);

        return redirect()
            ->route(
                'free-services.index'
            )
            ->with(
                'success',
                'Free service created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        FreeService $freeService
    ) {

        $this->checkTenant(
            $freeService
        );

        return view(
            'free_services.show',
            compact('freeService')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        FreeService $freeService
    ) {

        $this->checkTenant(
            $freeService
        );

        return view(
            'free_services.edit',
            compact('freeService')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        FreeService $freeService
    ) {

        $this->checkTenant(
            $freeService
        );

        $tenantId =
            auth()->user()->tenant_id;

        $validated = $request->validate([

            'service_name' => [
                'required',
                'string',
                'max:255',

                Rule::unique(
                    'free_services',
                    'service_name'
                )
                    ->ignore($freeService->id)
                    ->where(function ($query) use ($tenantId) {

                        return $query->where(
                            'tenant_id',
                            $tenantId
                        );

                    }),
            ],

            'service_unit' => [
                'required',
                'string',
                'max:100',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

        ]);

        $freeService->update([

            'service_name' => trim(
                $validated['service_name']
            ),

            'service_unit' => trim(
                $validated['service_unit']
            ),

            'status' => $request->boolean(
                'status',
                true
            ),

        ]);

        return redirect()
            ->route(
                'free-services.index'
            )
            ->with(
                'success',
                'Free service updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        FreeService $freeService
    ) {

        $this->checkTenant(
            $freeService
        );

        $freeService->delete();

        return redirect()
            ->route(
                'free-services.index'
            )
            ->with(
                'success',
                'Free service deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT SECURITY
    |--------------------------------------------------------------------------
    */

    private function checkTenant(
        FreeService $freeService
    ): void {

        abort_unless(
            $freeService->tenant_id ===
                auth()->user()->tenant_id,
            403
        );
    }
}
