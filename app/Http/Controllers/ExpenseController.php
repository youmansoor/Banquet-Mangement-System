<?php

namespace App\Http\Controllers;

use App\Models\TenantBank;
use App\Models\TenantFinanceMaster;
use App\Models\TenantFinanceTransaction;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    private function findExpenseOrFail(int $expenseId): TenantFinanceTransaction
    {
        $tenantId = (int) Auth::user()->tenant_id;

        return TenantFinanceTransaction::where([
            'id' => $expenseId,
            'tenant_id' => $tenantId,
            'transaction_type' => 'banquet_expense',
        ])->firstOrFail();
    }

    public function index(Request $request)
    {
        $tenantId = (int) Auth::user()->tenant_id;

        $query = TenantFinanceTransaction::with([
            'paymentCategory',
            'beneficiary',
            'vendor',
            'tenantBank',
        ])
            ->where('tenant_id', $tenantId)
            ->where('transaction_type', 'banquet_expense');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('item_description', 'like', "%{$search}%")
                    ->orWhere('vendor_invoice', 'like', "%{$search}%")
                    ->orWhere('cheque_number', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")
                    ->orWhereHas('vendor', function ($vq) use ($search) {
                        $vq->where('vendor_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('paymentCategory', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->to_date);
        }

        $expenses = $query
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(25);

        $totalAmount = $query->sum('amount');

        return view('finance.expenses.index', compact(
            'expenses',
            'totalAmount'
        ));
    }

    public function create()
    {
        $tenantId = (int) Auth::user()->tenant_id;

        $masters = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('parent_key', 'banquet_expense')
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

        return view('finance.expenses.create', compact(
            'masters',
            'banks',
            'vendors',
            'beneficiaries'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_category_id' => 'required|integer|exists:tenant_finance_masters,id',
            'beneficiary_id' => 'nullable|integer|exists:tenant_finance_masters,id',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
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
            'transaction_type' => 'banquet_expense',
            'payment_category_id' => $validated['payment_category_id'],
            'beneficiary_id' => $validated['beneficiary_id'] ?? null,
            'vendor_id' => $validated['vendor_id'] ?? null,
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
            ->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function show(int|string $expense)
    {
        $expenseModel = $this->findExpenseOrFail((int) $expense);

        $expenseModel->load([
            'paymentCategory',
            'beneficiary',
            'vendor',
            'tenantBank',
        ]);

        return view('finance.expenses.show', compact('expenseModel'));
    }

    public function edit(int|string $expense)
    {
        $expenseModel = $this->findExpenseOrFail((int) $expense);

        $tenantId = (int) Auth::user()->tenant_id;

        $masters = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('parent_key', 'banquet_expense')
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

        return view('finance.expenses.edit', compact(
            'expenseModel',
            'masters',
            'banks',
            'vendors',
            'beneficiaries'
        ));
    }

    public function update(Request $request, int|string $expense)
    {
        $expenseModel = $this->findExpenseOrFail((int) $expense);

        $validated = $request->validate([
            'payment_category_id' => 'required|integer|exists:tenant_finance_masters,id',
            'beneficiary_id' => 'nullable|integer|exists:tenant_finance_masters,id',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'tenant_bank_id' => 'nullable|integer|exists:tenant_banks,id',
            'item_description' => 'nullable|string|max:1000',
            'vendor_invoice' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0.01|max:999999999999.99',
            'transaction_date' => 'required|date',
            'remarks' => 'nullable|string|max:5000',
        ]);

        $expenseModel->update([
            'payment_category_id' => $validated['payment_category_id'],
            'beneficiary_id' => $validated['beneficiary_id'] ?? null,
            'vendor_id' => $validated['vendor_id'] ?? null,
            'tenant_bank_id' => $validated['tenant_bank_id'] ?? null,
            'item_description' => $validated['item_description'] ?? null,
            'vendor_invoice' => $validated['vendor_invoice'] ?? null,
            'cheque_number' => $validated['cheque_number'] ?? null,
            'cheque_date' => $validated['cheque_date'] ?? null,
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(int|string $expense)
    {
        $expenseModel = $this->findExpenseOrFail((int) $expense);

        $expenseModel->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
