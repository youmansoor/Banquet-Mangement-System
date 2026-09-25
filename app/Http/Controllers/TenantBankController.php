<?php

namespace App\Http\Controllers;

use App\Models\TenantBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantBankController extends Controller
{
    /**
     * Display all tenant banks.
     */
    public function index()
    {
        $tenantId = (int) Auth::user()->tenant_id;

        $banks = TenantBank::where('tenant_id', $tenantId)
            ->orderBy('bank_name')
            ->get();

        return view('tenant.banks.index', compact('banks'));
    }

    /**
     * Show create bank form.
     */
    public function create()
    {
        return view('tenant.banks.create');
    }

    /**
     * Store a new bank.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],
        ]);

        $tenantId = (int) Auth::user()->tenant_id;

        TenantBank::create([
            'tenant_id' => $tenantId,
            'bank_name' => $validated['bank_name'],
            'opening_balance' => $validated['opening_balance'],
            'current_balance' => $validated['opening_balance'],
        ]);

        return $this->redirectToBankSettings(
            'Bank added successfully.'
        );
    }

    /**
     * Display bank details.
     */
    public function show(TenantBank $bank)
    {
        $this->checkTenant($bank);

        return view('tenant.banks.show', compact('bank'));
    }

    /**
     * Show edit bank form.
     */
    public function edit(TenantBank $bank)
    {
        $this->checkTenant($bank);

        return view('tenant.banks.edit', compact('bank'));
    }

    /**
     * Update an existing bank.
     */
    public function update(Request $request, TenantBank $bank)
    {
        $this->checkTenant($bank);

        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],
        ]);

        $bank->update([
            'bank_name' => $validated['bank_name'],
            'opening_balance' => $validated['opening_balance'],
        ]);

        return $this->redirectToBankSettings(
            'Bank updated successfully.'
        );
    }

    /**
     * Delete a bank.
     */
    public function destroy(TenantBank $bank)
    {
        $this->checkTenant($bank);

        $bank->delete();

        return $this->redirectToBankSettings(
            'Bank deleted successfully.'
        );
    }

    /**
     * Redirect to the Banks tab in Settings.
     */
    private function redirectToBankSettings(string $message)
    {
        return redirect()
            ->to(url('/tenant/settings') . '#bankSettings')
            ->with('success', $message);
    }

    /**
     * Ensure the bank belongs to the logged-in tenant.
     */
    private function checkTenant(TenantBank $bank): void
    {
        $tenantId = (int) Auth::user()->tenant_id;

        if ((int) $bank->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access.');
        }
    }
}