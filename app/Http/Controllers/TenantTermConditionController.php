<?php

namespace App\Http\Controllers;

use App\Models\TenantTermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantTermConditionController extends Controller
{
    /**
     * Display all Terms & Conditions
     * created by the logged-in Tenant.
     */
    public function index()
    {
        $tenantId = $this->tenantId();

        $terms = TenantTermCondition::where('tenant_id', $tenantId)
            ->latest('id')
            ->get();

        return view(
            'tenant.terms-conditions.index',
            compact('terms')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view('tenant.terms-conditions.create');
    }


    /**
     * Store a new Term & Condition.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'heading' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],
        ]);

        TenantTermCondition::create([
            'tenant_id' => $this->tenantId(),
            'heading' => $validated['heading'],
            'description' => $validated['description'],
        ]);

        return $this->redirectToTermsConditions(
            'Term & Condition added successfully.'
        );
    }


    /**
     * Display a single Term & Condition.
     */
    public function show(TenantTermCondition $term)
    {
        $this->checkTenant($term);

        return view(
            'tenant.terms-conditions.show',
            compact('term')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(TenantTermCondition $term)
    {
        $this->checkTenant($term);

        return view(
            'tenant.terms-conditions.edit',
            compact('term')
        );
    }


    /**
     * Update a Term & Condition.
     */
    public function update(
        Request $request,
        TenantTermCondition $term
    ) {
        $this->checkTenant($term);

        $validated = $request->validate([
            'heading' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],
        ]);

        $term->update([
            'heading' => $validated['heading'],
            'description' => $validated['description'],
        ]);

        return $this->redirectToTermsConditions(
            'Term & Condition updated successfully.'
        );
    }


    /**
     * Delete a Term & Condition.
     */
    public function destroy(TenantTermCondition $term)
    {
        $this->checkTenant($term);

        $term->delete();

        return $this->redirectToTermsConditions(
            'Term & Condition deleted successfully.'
        );
    }


    /**
     * Redirect to Terms & Conditions tab
     * inside Tenant Settings.
     */
    private function redirectToTermsConditions(string $message)
    {
        return redirect()
            ->to(url('/tenant/settings') . '#terms-pane')
            ->with('success', $message);
    }


    /**
     * Get logged-in Tenant ID.
     */
    private function tenantId(): int
    {
        $user = Auth::user();

        abort_unless($user, 403, 'User is not authenticated.');

        $tenantId = (int) $user->tenant_id;

        abort_unless(
            $tenantId > 0,
            403,
            'Tenant is not assigned to this user.'
        );

        return $tenantId;
    }


    /**
     * Verify that the Term & Condition
     * belongs to the logged-in Tenant.
     */
    private function checkTenant(TenantTermCondition $term): void
    {
        if ((int) $term->tenant_id !== $this->tenantId()) {
            abort(403, 'Unauthorized access.');
        }
    }
}