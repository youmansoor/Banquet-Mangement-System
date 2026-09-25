<?php

namespace App\Http\Controllers;

use App\Models\TenantFinanceMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TenantFinanceMasterController extends Controller
{
    /**
     * Display Finance Masters index page.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $masters = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('master_type')
            ->orderBy('name')
            ->get()
            ->groupBy('master_type');

        return view('tenant.finance-masters.index', compact('masters'));
    }


    /**
     * Show create form.
     */
    public function create(string $type)
    {
        $allowedTypes = [
            'transaction_type',
            'payment_category',
            'beneficiary',
            'vendor',
        ];

        abort_unless(
            in_array($type, $allowedTypes, true),
            404
        );

        return view('tenant.finance-masters.create', compact('type'));
    }


    /**
     * Store a new Finance Master.
     */
    public function store(Request $request, string $type)
    {
        $allowedTypes = [
            'transaction_type',
            'payment_category',
            'beneficiary',
            'vendor',
        ];

        abort_unless(
            in_array($type, $allowedTypes, true),
            404
        );

        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_key' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $alreadyExists = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('master_type', $type)
            ->where('name', $validated['name'])
            ->where('is_active', true)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This name already exists for this Finance Master type.',
                ]);
        }

        TenantFinanceMaster::create([
            'tenant_id' => $tenantId,
            'master_type' => $type,
            'name' => $validated['name'],
            'parent_key' => $validated['parent_key'] ?? null,
            'is_active' => true,
        ]);

        return $this->redirectToFinanceMasters(
            ucfirst(str_replace('_', ' ', $type))
            . ' created successfully.'
        );
    }


    /**
     * Display a specific Finance Master.
     */
    public function show(TenantFinanceMaster $master)
    {
        $this->checkTenant($master);

        return view(
            'tenant.finance-masters.show',
            compact('master')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(TenantFinanceMaster $master)
    {
        $this->checkTenant($master);

        return view(
            'tenant.finance-masters.edit',
            compact('master')
        );
    }


    /**
     * Update a single Finance Master.
     */
    public function update(
        Request $request,
        TenantFinanceMaster $master
    ) {
        $this->checkTenant($master);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_key' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $alreadyExists = TenantFinanceMaster::where('tenant_id', $master->tenant_id)
            ->where('master_type', $master->master_type)
            ->where('name', $validated['name'])
            ->where('id', '!=', $master->id)
            ->where('is_active', true)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This name already exists for this Finance Master type.',
                ]);
        }

        $master->update([
            'name' => $validated['name'],
            'parent_key' => $validated['parent_key'] ?? null,
        ]);

        return $this->redirectToFinanceMasters(
            ucfirst(
                str_replace('_', ' ', $master->master_type)
            ) . ' updated successfully.'
        );
    }


    /**
     * Deactivate a Finance Master.
     *
     * Soft delete is implemented using is_active = false.
     */
    public function destroy(TenantFinanceMaster $master)
    {
        $this->checkTenant($master);

        $master->update([
            'is_active' => false,
        ]);

        return $this->redirectToFinanceMasters(
            ucfirst(
                str_replace('_', ' ', $master->master_type)
            ) . ' deleted successfully.'
        );
    }


    /**
     * Bulk update Finance Masters.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'transaction_types' => [
                'nullable',
                'array',
            ],

            'transaction_types.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_categories' => [
                'nullable',
                'array',
            ],

            'payment_categories.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'beneficiaries' => [
                'nullable',
                'array',
            ],

            'beneficiaries.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vendors' => [
                'nullable',
                'array',
            ],

            'vendors.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $tenantId = Auth::user()->tenant_id;

        DB::transaction(function () use ($validated, $tenantId) {
            $this->syncType(
                tenantId: $tenantId,
                type: 'transaction_type',
                values: $validated['transaction_types'] ?? []
            );

            $this->syncType(
                tenantId: $tenantId,
                type: 'payment_category',
                values: $validated['payment_categories'] ?? []
            );

            $this->syncType(
                tenantId: $tenantId,
                type: 'beneficiary',
                values: $validated['beneficiaries'] ?? []
            );

            $this->syncType(
                tenantId: $tenantId,
                type: 'vendor',
                values: $validated['vendors'] ?? []
            );
        });

        return $this->redirectToFinanceMasters(
            'Finance Master settings updated successfully.'
        );
    }


    /**
     * Synchronize one Finance Master type.
     */
    private function syncType(
        int $tenantId,
        string $type,
        array $values
    ): void {
        $cleanValues = collect($values)
            ->map(function ($value) {
                return trim((string) $value);
            })
            ->filter(function ($value) {
                return $value !== '';
            })
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Deactivate existing records of this type
        |--------------------------------------------------------------------------
        */

        TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('master_type', $type)
            ->update([
                'is_active' => false,
            ]);

        /*
        |--------------------------------------------------------------------------
        | Create or activate submitted records
        |--------------------------------------------------------------------------
        */

        foreach ($cleanValues as $value) {
            $master = TenantFinanceMaster::withTrashed()
                ->where('tenant_id', $tenantId)
                ->where('master_type', $type)
                ->where('name', $value)
                ->first();

            if ($master) {
                $master->update([
                    'is_active' => true,
                ]);
            } else {
                TenantFinanceMaster::create([
                    'tenant_id' => $tenantId,
                    'master_type' => $type,
                    'name' => $value,
                    'parent_key' => null,
                    'is_active' => true,
                ]);
            }
        }
    }


    /**
     * Redirect to Finance Masters tab inside Settings.
     */
    private function redirectToFinanceMasters(string $message)
    {
        return redirect()
            ->to(url('/tenant/settings') . '#financeMasters')
            ->with('success', $message);
    }


    /**
     * Verify that the Finance Master belongs to the logged-in tenant.
     */
    private function checkTenant(TenantFinanceMaster $master): void
    {
        $tenantId = Auth::user()->tenant_id;

        if ((int) $master->tenant_id !== (int) $tenantId) {
            abort(403, 'Unauthorized access.');
        }
    }
}