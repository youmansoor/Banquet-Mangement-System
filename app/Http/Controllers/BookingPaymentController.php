<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\TenantBank;
use App\Models\TenantFinanceMaster;
use App\Models\TenantFinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingPaymentController extends Controller
{
    public function create()
    {
        $tenantId = (int) Auth::user()->tenant_id;

        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();

        $banks = TenantBank::where('tenant_id', $tenantId)
            ->orderBy('bank_name')
            ->get();

        $masters = TenantFinanceMaster::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('parent_key', 'customer_payment')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('finance.booking-payments.create', compact(
            'customers',
            'banks',
            'masters'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'booking_id' => 'required|integer|exists:bookings,id',
            'invoice_id' => 'required|integer|exists:invoices,id',
            'payment_category_id' => 'required|integer|exists:tenant_finance_masters,id',
            'tenant_bank_id' => 'nullable|integer|exists:tenant_banks,id',
            'cheque_number' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0.01|max:999999999999.99',
            'transaction_date' => 'required|date',
            'remarks' => 'nullable|string|max:5000',
        ]);

        $tenantId = Auth::user()->tenant_id;

        $customer = Customer::where('tenant_id', $tenantId)
            ->findOrFail($validated['customer_id']);

        $booking = Booking::where('tenant_id', $tenantId)
            ->findOrFail($validated['booking_id']);

        if ((int) $booking->customer_id !== (int) $customer->id) {
            return back()
                ->withErrors(['booking_id' => 'Selected booking does not belong to the selected customer.'])
                ->withInput();
        }

        $invoice = Invoice::where('tenant_id', $tenantId)
            ->findOrFail($validated['invoice_id']);

        if ((int) $invoice->booking_id !== (int) $booking->id) {
            return back()
                ->withErrors(['invoice_id' => 'Selected invoice does not belong to the selected booking.'])
                ->withInput();
        }

        $transactionReference = trim($validated['cheque_number'] ?? '') ?: null;

        if (! empty($transactionReference)) {
            $paymentMethod = 'cheque';
        } elseif (! empty($validated['tenant_bank_id'])) {
            $paymentMethod = 'bank';
        } else {
            $paymentMethod = 'cash';
        }

        TenantFinanceTransaction::create([
            'tenant_id' => $tenantId,
            'transaction_type' => 'customer_payment',
            'payment_category_id' => $validated['payment_category_id'],
            'customer_id' => $validated['customer_id'],
            'booking_id' => $validated['booking_id'],
            'invoice_id' => $validated['invoice_id'],
            'tenant_bank_id' => $validated['tenant_bank_id'] ?? null,
            'cheque_number' => $validated['cheque_number'] ?? null,
            'cheque_date' => $validated['cheque_date'] ?? null,
            'transaction_direction' => 'cr',
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('booking.payments.create')
            ->with('success', 'Booking payment recorded successfully.');
    }
}
