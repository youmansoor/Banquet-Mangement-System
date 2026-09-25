<?php

namespace App\Http\Controllers;

use App\Models\TenantBank;
use App\Models\TenantFinanceMaster;
use App\Models\TenantFinanceTransaction;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorPaymentController extends Controller
{
    public function create()
    {
        $tenantId = (int) Auth::user()->tenant_id;

        $masters = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('parent_key', 'vendor_payment')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $banks = TenantBank::where('tenant_id', $tenantId)
            ->orderBy('bank_name')
            ->get();

        $vendors = Vendor::where('tenant_id', $tenantId)
            ->where('status', true)
            ->orderBy('vendor_name')
            ->get();

        $beneficiaries = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('master_type', 'beneficiary')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('finance.vendor-payments.create', compact(
            'masters',
            'banks',
            'vendors',
            'beneficiaries'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'payment_category_id' => 'required|integer|exists:tenant_finance_masters,id',
            'beneficiary_id' => 'nullable|integer|exists:tenant_finance_masters,id',
            'tenant_bank_id' => 'nullable|integer|exists:tenant_banks,id',
            'item_description' => 'nullable|string|max:1000',
            'vendor_invoice' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0.01|max:999999999999.99',
            'transaction_date' => 'required|date',
            'remarks' => 'nullable|string|max:5000',
        ]);

        $tenantId = Auth::user()->tenant_id;

        TenantFinanceTransaction::create([
            'tenant_id' => $tenantId,
            'transaction_type' => 'vendor_payment',
            'payment_category_id' => $validated['payment_category_id'],
            'vendor_id' => $validated['vendor_id'],
            'beneficiary_id' => $validated['beneficiary_id'] ?? null,
            'tenant_bank_id' => $validated['tenant_bank_id'] ?? null,
            'item_description' => $validated['item_description'] ?? null,
            'vendor_invoice' => $validated['vendor_invoice'] ?? null,
            'cheque_number' => $validated['cheque_number'] ?? null,
            'cheque_date' => $validated['cheque_date'] ?? null,
            'transaction_direction' => 'dr',
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('vendor.payments.create')
            ->with('success', 'Vendor payment recorded successfully.');
    }
}
