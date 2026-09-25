<?php

namespace App\Http\Controllers;

use App\Models\LawnType;
use Illuminate\Http\Request;

class LawnTypeController extends Controller
{
    /**
     * Display lawn types.
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $query = LawnType::where(
            'tenant_id',
            $tenantId
        );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        | Search by:
        | - Lawn Type
        | - Lawn Type ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $searchTerm = trim($request->search);

            $query->where(function ($q) use ($searchTerm) {

                $q->where(
                    'lawn_type',
                    'like',
                    "%{$searchTerm}%"
                );

                /*
                | Allow searching by ID
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
        | CREATED DATE
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
        | SORT
        |--------------------------------------------------------------------------
        */

        $sort = $request->get(
            'sort',
            'latest'
        );

        switch ($sort) {

            case 'oldest':

                $query->oldest();

                break;

            case 'name_asc':

                $query->orderBy(
                    'lawn_type',
                    'asc'
                );

                break;

            case 'name_desc':

                $query->orderBy(
                    'lawn_type',
                    'desc'
                );

                break;

            case 'latest':
            default:

                $query->latest();

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $lawnTypes = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'lawn_types.index',
            compact('lawnTypes')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'lawn_types.create'
        );
    }

    /**
     * Store lawn type.
     */
    public function store(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([

            'lawn_type' => [
                'required',
                'string',
                'max:255',

                'unique:lawn_types,lawn_type,NULL,id,tenant_id,'.$tenantId,
            ],

        ]);

        LawnType::create([

            'tenant_id' => $tenantId,

            'lawn_type' => $validated['lawn_type'],

        ]);

        return redirect()
            ->route('lawn_types.index')
            ->with(
                'success',
                'Lawn type added successfully.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(LawnType $lawnType)
    {
        $this->checkTenant(
            $lawnType
        );

        return view(
            'lawn_types.edit',
            compact('lawnType')
        );
    }

    /**
     * Update lawn type.
     */
    public function update(
        Request $request,
        LawnType $lawnType
    ) {

        $this->checkTenant(
            $lawnType
        );

        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([

            'lawn_type' => [

                'required',

                'string',

                'max:255',

                'unique:lawn_types,lawn_type,'
                .$lawnType->id
                .',id,tenant_id,'
                .$tenantId,

            ],

        ]);

        $lawnType->update([

            'lawn_type' => $validated['lawn_type'],

        ]);

        return redirect()
            ->route('lawn_types.index')
            ->with(
                'success',
                'Lawn type updated successfully.'
            );
    }

    /**
     * Delete lawn type.
     */
    public function destroy(
        LawnType $lawnType
    ) {

        $this->checkTenant(
            $lawnType
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent deletion if bookings exist
        |--------------------------------------------------------------------------
        */

        if (
            $lawnType
                ->bookings()
                ->exists()
        ) {

            return redirect()
                ->route('lawn_types.index')
                ->with(
                    'error',
                    'This lawn type cannot be deleted because it has bookings.'
                );
        }

        $lawnType->delete();

        return redirect()
            ->route('lawn_types.index')
            ->with(
                'success',
                'Lawn type deleted successfully.'
            );
    }

    /**
     * Ensure user can only access own tenant data.
     */
    private function checkTenant(
        LawnType $lawnType
    ): void {

        if (
            $lawnType->tenant_id
            !== auth()->user()->tenant_id
        ) {

            abort(403);
        }
    }
}
