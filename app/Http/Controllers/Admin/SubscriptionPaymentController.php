<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubscriptionPaymentController extends Controller
{
    /**
     * Display all subscription payments.
     */
    public function index()
    {
        $payments = SubscriptionPayment::with([
            'tenant',
            'subscription',
            'invoice',
        ])
            ->latest()
            ->get();

        return view(
            'admin.subscription-payments.index',
            compact('payments')
        );
    }

    /**
     * Show create payment form.
     */
    public function create()
    {
        $tenants = Tenant::query()
            ->with('activeSubscription')
            ->where('status', true)
            ->orderBy('business_name')
            ->get();

        $tenantIds = $tenants
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        $invoices = Invoice::query()
            ->whereIn('tenant_id', $tenantIds)
            ->whereNotNull('subscription_id')
            ->whereIn('status', [
                'unpaid',
                'partial',
            ])
            ->where('remaining_amount', '>', 0)
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->get();

        $subscriptions = Subscription::query()
            ->with([
                'tenant',
            ])
            ->whereIn('tenant_id', $tenantIds)
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Banks
        |--------------------------------------------------------------------------
        |
        | Bank model confirm hone tak empty collection.
        |
        */

        $banks = collect();

        return view(
            'payments.create',
            compact(
                'tenants',
                'subscriptions',
                'invoices',
                'banks'
            )
        );
    }

    /**
     * Get tenant subscription and latest invoice details.
     */
    public function getTenantDetails(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|integer|exists:tenants,id',
        ]);

        $tenant = Tenant::query()
            ->with('activeSubscription')
            ->findOrFail($validated['tenant_id']);

        $activeSubscription = $tenant->activeSubscription;

        $latestInvoice = Invoice::query()
            ->where('tenant_id', $tenant->id)
            ->whereNotNull('subscription_id')
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->first();

        return response()->json([
            'subscription' => $activeSubscription ? [
                'id' => $activeSubscription->id,
                'amount' => $activeSubscription->amount,
                'starts_at' => $activeSubscription->starts_at?->format('Y-m-d'),
                'ends_at' => $activeSubscription->ends_at?->format('Y-m-d'),
                'status' => $activeSubscription->status,
            ] : null,
            'latest_invoice' => $latestInvoice ? [
                'id' => $latestInvoice->id,
                'subscription_id' => $latestInvoice->subscription_id,
                'invoice_number' => $latestInvoice->invoice_number,
                'invoice_date' => $latestInvoice->invoice_date?->format('d M Y'),
                'due_date' => $latestInvoice->due_date?->format('d M Y'),
                'grand_total' => $latestInvoice->grand_total,
                'paid_amount' => $latestInvoice->paid_amount,
                'remaining_amount' => $latestInvoice->remaining_amount,
                'status' => $latestInvoice->status,
            ] : null,
        ]);
    }

    /**
     * Store payment.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePayment($request);

        DB::transaction(function () use ($validated) {

            $tenant = Tenant::query()
                ->where('status', true)
                ->findOrFail($validated['tenant_id']);

            $subscription = Subscription::query()
                ->where('tenant_id', $tenant->id)
                ->findOrFail($validated['subscription_id']);

            $invoice = Invoice::query()
                ->where('tenant_id', $tenant->id)
                ->lockForUpdate()
                ->findOrFail($validated['invoice_id']);

            /*
            |--------------------------------------------------------------------------
            | Validate Invoice Amount
            |--------------------------------------------------------------------------
            */

            $this->validatePaymentAmount(
                $invoice,
                (float) $validated['amount'],
                0
            );

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            SubscriptionPayment::create([
                'tenant_id' => $tenant->id,
                'subscription_id' => $subscription->id,
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'paid_at' => $validated['paid_at'] ?? now(),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Invoice
            |--------------------------------------------------------------------------
            |
            | Sirf paid payment invoice ko affect karegi.
            |
            */

            if ($validated['status'] === 'paid') {
                $this->recalculateInvoice(
                    $invoice,
                    (float) $validated['amount']
                );
            }
        });

        return redirect()
            ->route('admin.subscription-payments.index')
            ->with(
                'success',
                'Subscription payment recorded successfully.'
            );
    }

    /**
     * Show payment.
     */
    public function show(
        SubscriptionPayment $subscriptionPayment
    ) {
        $subscriptionPayment->load([
            'tenant',
            'subscription',
            'invoice',
        ]);

        return view(
            'admin.subscription-payments.show',
            compact('subscriptionPayment')
        );
    }

    /**
     * Show edit payment form.
     */
    public function edit(
        SubscriptionPayment $subscriptionPayment
    ) {
        $subscriptions = Subscription::with([
            'tenant',
        ])
            ->latest()
            ->get();

        $invoices = Invoice::with([
            'tenant',
        ])
            ->whereNotNull('tenant_id')
            ->latest('invoice_date')
            ->get();

        return view(
            'admin.subscription-payments.edit',
            compact(
                'subscriptionPayment',
                'subscriptions',
                'invoices'
            )
        );
    }

    /**
     * Update payment.
     */
    public function update(
        Request $request,
        SubscriptionPayment $subscriptionPayment
    ) {
        $validated = $this->validatePayment($request);

        DB::transaction(function () use (
            $validated,
            $subscriptionPayment
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Old Payment and Old Invoice
            |--------------------------------------------------------------------------
            */

            $oldPayment = SubscriptionPayment::query()
                ->lockForUpdate()
                ->findOrFail($subscriptionPayment->id);

            $oldInvoice = Invoice::query()
                ->lockForUpdate()
                ->findOrFail($oldPayment->invoice_id);

            /*
            |--------------------------------------------------------------------------
            | Validate New Tenant and Subscription
            |--------------------------------------------------------------------------
            */

            $tenant = Tenant::query()
                ->where('status', true)
                ->findOrFail($validated['tenant_id']);

            $subscription = Subscription::query()
                ->where('tenant_id', $tenant->id)
                ->findOrFail($validated['subscription_id']);

            /*
            |--------------------------------------------------------------------------
            | Lock New Invoice
            |--------------------------------------------------------------------------
            */

            $newInvoice = Invoice::query()
                ->where('tenant_id', $tenant->id)
                ->lockForUpdate()
                ->findOrFail($validated['invoice_id']);

            /*
            |--------------------------------------------------------------------------
            | Reverse Old Payment
            |--------------------------------------------------------------------------
            */

            if (
                $oldPayment->status === 'paid'
                && $oldInvoice->id
            ) {
                $this->recalculateInvoice(
                    $oldInvoice,
                    -(float) $oldPayment->amount
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate New Payment Amount
            |--------------------------------------------------------------------------
            |
            | If old and new invoice are the same, the old payment has already
            | been reversed above.
            |
            */

            $this->validatePaymentAmount(
                $newInvoice,
                (float) $validated['amount'],
                0
            );

            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $oldPayment->update([
                'tenant_id' => $tenant->id,
                'subscription_id' => $subscription->id,
                'invoice_id' => $newInvoice->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'paid_at' => $validated['paid_at'] ?? now(),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Apply New Payment
            |--------------------------------------------------------------------------
            */

            if ($validated['status'] === 'paid') {
                $this->recalculateInvoice(
                    $newInvoice,
                    (float) $validated['amount']
                );
            }
        });

        return redirect()
            ->route('admin.subscription-payments.index')
            ->with(
                'success',
                'Subscription payment updated successfully.'
            );
    }

    /**
     * Delete payment.
     */
    public function destroy(
        SubscriptionPayment $subscriptionPayment
    ) {
        DB::transaction(function () use ($subscriptionPayment) {

            $payment = SubscriptionPayment::query()
                ->lockForUpdate()
                ->findOrFail($subscriptionPayment->id);

            if ($payment->status === 'paid') {

                $invoice = Invoice::query()
                    ->lockForUpdate()
                    ->findOrFail($payment->invoice_id);

                $this->recalculateInvoice(
                    $invoice,
                    -(float) $payment->amount
                );
            }

            $payment->delete();
        });

        return redirect()
            ->route('admin.subscription-payments.index')
            ->with(
                'success',
                'Subscription payment deleted successfully.'
            );
    }

    /**
     * Validate payment request.
     */
    private function validatePayment(
        Request $request
    ): array {
        return $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],

            'subscription_id' => [
                'required',
                'integer',
                'exists:subscriptions,id',
            ],

            'invoice_id' => [
                'required',
                'integer',
                'exists:invoices,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_method' => [
                'required',
                'in:cash,bank_transfer,cheque,online',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paid_at' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:pending,paid,failed,refunded',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);
    }

    /**
     * Validate payment amount against invoice remaining amount.
     */
    private function validatePaymentAmount(
        Invoice $invoice,
        float $amount,
        float $availableAdjustment = 0
    ): void {
        $remainingAmount =
            (float) $invoice->remaining_amount
            + $availableAdjustment;

        if ($amount > $remainingAmount) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount cannot be greater than the invoice remaining amount.',
            ]);
        }
    }

    /**
     * Recalculate invoice paid amount, remaining amount and status.
     *
     * Positive amount = Add payment.
     * Negative amount = Reverse payment.
     */
    private function recalculateInvoice(
        Invoice $invoice,
        float $amount
    ): void {
        $grandTotal = (float) $invoice->grand_total;

        $oldPaidAmount = (float) $invoice->paid_amount;

        $newPaidAmount = max(
            0,
            min(
                $grandTotal,
                $oldPaidAmount + $amount
            )
        );

        $newRemainingAmount = max(
            0,
            $grandTotal - $newPaidAmount
        );

        if ($newRemainingAmount <= 0) {
            $newStatus = 'paid';
        } elseif ($newPaidAmount > 0) {
            $newStatus = 'partial';
        } else {
            $newStatus = 'unpaid';
        }

        $invoice->update([
            'paid_amount' => $newPaidAmount,
            'remaining_amount' => $newRemainingAmount,
            'status' => $newStatus,
        ]);
    }
}
