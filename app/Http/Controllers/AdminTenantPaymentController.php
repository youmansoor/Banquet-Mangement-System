<?php

namespace App\Http\Controllers;

use App\Models\AdminTenantPayment;
use App\Models\Invoice;
use App\Models\SubscriptionDue;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTenantPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PAYMENT LIST
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $tenants = Tenant::query()
            ->where('status', true)
            ->orderBy('business_name')
            ->get();

        return view('payments.index', compact('tenants'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| CREATE PAYMENT
|--------------------------------------------------------------------------
*/

    public function create()
    {
        $tenants = Tenant::with('activeSubscription')
            ->where('status', true)
            ->orderBy('business_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Generate Monthly Subscription Dues
        |--------------------------------------------------------------------------
        */

        foreach ($tenants as $tenant) {

            $subscription = $tenant->activeSubscription;

            if ($subscription) {
                $this->generateMonthlyDues($subscription);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Current Outstanding
        |--------------------------------------------------------------------------
        */

        foreach ($tenants as $tenant) {

            $subscription = $tenant->activeSubscription;

            $outstanding = 0;

            if ($subscription) {

                $outstanding = SubscriptionDue::where(
                    'tenant_id',
                    $tenant->id
                )
                    ->where(
                        'subscription_id',
                        $subscription->id
                    )
                    ->sum('remaining_amount');
            }

            $tenant->current_outstanding = (float) $outstanding;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Only Active Tenant IDs
        |--------------------------------------------------------------------------
        */

        $tenantIds = $tenants
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Get Admin Created Invoices
        |--------------------------------------------------------------------------
        |
        | Sirf un tenants ki invoices show hongi jo active hain.
        | Unpaid aur partial invoices show hongi.
        |
        */

        $invoices = Invoice::query()
            ->whereIn('tenant_id', $tenantIds)
            ->whereIn('status', [
                'unpaid',
                'partial',
            ])
            ->where('remaining_amount', '>', 0)
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Banks
        |--------------------------------------------------------------------------
        |
        | Agar abhi banks ka model/controller setup nahi hai,
        | to empty collection pass kar rahe hain.
        |
        */

        $banks = collect();

        return view(
            'payments.create',
            compact(
                'tenants',
                'invoices',
                'banks'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE MONTHLY DUES
    |--------------------------------------------------------------------------
    */
    private function generateMonthlyDues($subscription): void
    {
        if (! $subscription) {
            return;
        }

        $amount = (float) ($subscription->amount ?? 0);

        if ($amount <= 0) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Billing Start
        |--------------------------------------------------------------------------
        | Subscription created_at ko billing start maana gaya hai.
        */
        $startDate = $subscription->created_at->copy()->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | Current Month
        |--------------------------------------------------------------------------
        */
        $currentMonth = now()->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Start Month
        |--------------------------------------------------------------------------
        */
        $month = $startDate->copy()->startOfMonth();

        while ($month->lte($currentMonth)) {

            $billingMonth = $month->copy()->startOfMonth();

            $dueDay = min(
                $startDate->day,
                $billingMonth->daysInMonth
            );

            $dueDate = $billingMonth->copy()
                ->day($dueDay);

            $exists = SubscriptionDue::where(
                'subscription_id',
                $subscription->id
            )
                ->whereDate(
                    'billing_month',
                    $billingMonth->toDateString()
                )
                ->exists();

            if (! $exists) {

                SubscriptionDue::create([
                    'tenant_id' => $subscription->tenant_id,

                    'subscription_id' => $subscription->id,

                    'billing_month' => $billingMonth->toDateString(),

                    'due_date' => $dueDate->toDateString(),

                    'amount' => $amount,

                    'paid_amount' => 0,

                    'remaining_amount' => $amount,

                    'status' => 'unpaid',
                ]);
            }

            $month->addMonth();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'tenant_id' => [
                'required',
                'exists:tenants,id',
            ],

            'subscription_id' => [
                'required',
                'exists:subscriptions,id',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'required',
                'in:cash,bank,online,card',
            ],

            'payment_direction' => [
                'required',
                'in:in,out',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $tenant = Tenant::with('activeSubscription')
            ->findOrFail($validated['tenant_id']);

        $subscription = $tenant->activeSubscription;

        if (! $subscription) {
            return back()
                ->withErrors([
                    'tenant_id' => 'This tenant does not have an active subscription.',
                ])
                ->withInput();
        }

        if (
            (int) $subscription->id !==
            (int) $validated['subscription_id']
        ) {
            return back()
                ->withErrors([
                    'subscription_id' => 'Selected subscription does not belong to this tenant.',
                ])
                ->withInput();
        }

        $subscriptionAmount =
            (float) ($subscription->amount ?? 0);

        if ($subscriptionAmount <= 0) {
            return back()
                ->withErrors([
                    'amount' => 'Subscription amount is not valid.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Generate all monthly dues up to current month
        |--------------------------------------------------------------------------
        */

        $this->generateMonthlyDues($subscription);

        /*
        |--------------------------------------------------------------------------
        | Current Outstanding Balance
        |--------------------------------------------------------------------------
        */

        $outstanding =
            (float) SubscriptionDue::where(
                'tenant_id',
                $tenant->id
            )
                ->where(
                    'subscription_id',
                    $subscription->id
                )
                ->sum('remaining_amount');

        $paymentAmount =
            (float) $validated['amount'];

        /*
        |--------------------------------------------------------------------------
        | Payment IN
        |--------------------------------------------------------------------------
        */

        if (
            $validated['payment_direction'] === 'in'
            && $paymentAmount > $outstanding
        ) {
            return back()
                ->withErrors([
                    'amount' => 'Payment amount cannot be greater than outstanding balance '
                        .number_format($outstanding, 2),
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $validated,
            $tenant,
            $subscription,
            $paymentAmount,
            $subscriptionAmount
        ) {

            /*
            |--------------------------------------------------------------------------
            | PAYMENT RECORD
            |--------------------------------------------------------------------------
            */

            $newOutstanding =
                (float) SubscriptionDue::where(
                    'tenant_id',
                    $tenant->id
                )
                    ->where(
                        'subscription_id',
                        $subscription->id
                    )
                    ->sum('remaining_amount');

            if (
                $validated['payment_direction'] === 'in'
            ) {

                $newOutstanding = max(
                    $newOutstanding - $paymentAmount,
                    0
                );

            } else {

                $newOutstanding =
                    $newOutstanding + $paymentAmount;
            }

            AdminTenantPayment::create([

                'tenant_id' => $tenant->id,

                'subscription_id' => $subscription->id,

                'payment_date' => $validated['payment_date'],

                'payment_type' => $validated['payment_method'],

                'payment_direction' => $validated['payment_direction'],

                'subscription_amount' => $subscriptionAmount,

                'payment_amount' => $paymentAmount,

                'remaining_amount' => $newOutstanding,

                'transaction_reference' => $validated['transaction_reference'] ?? null,

                'notes' => $validated['notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | PAYMENT IN
            |--------------------------------------------------------------------------
            | Oldest dues first.
            */

            if (
                $validated['payment_direction'] === 'in'
            ) {

                $remainingPayment =
                    $paymentAmount;

                $dues = SubscriptionDue::where(
                    'tenant_id',
                    $tenant->id
                )
                    ->where(
                        'subscription_id',
                        $subscription->id
                    )
                    ->where(
                        'remaining_amount',
                        '>',
                        0
                    )
                    ->orderBy('due_date')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                foreach ($dues as $due) {

                    if ($remainingPayment <= 0) {
                        break;
                    }

                    $dueRemaining =
                        (float) $due->remaining_amount;

                    $allocate = min(
                        $remainingPayment,
                        $dueRemaining
                    );

                    $newPaid =
                        (float) $due->paid_amount
                        + $allocate;

                    $newRemaining =
                        max(
                            (float) $due->amount
                            - $newPaid,
                            0
                        );

                    if ($newRemaining <= 0) {

                        $status = 'paid';

                    } elseif ($newPaid > 0) {

                        $status = 'partial';

                    } else {

                        $status = 'unpaid';
                    }

                    $due->update([
                        'paid_amount' => $newPaid,

                        'remaining_amount' => $newRemaining,

                        'status' => $status,
                    ]);

                    $remainingPayment -=
                        $allocate;
                }
            }
        });

        return redirect()
            ->route('admin.tenant-payments.index')
            ->with(
                'success',
                'Tenant payment recorded successfully.'
            );
    }

    public function history(Tenant $tenant)
    {
        /*
        |--------------------------------------------------------------------------
        | Generate monthly dues first
        |--------------------------------------------------------------------------
        */

        $subscription = $tenant->activeSubscription;

        if ($subscription) {
            $this->generateMonthlyDues($subscription);
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly Dues
        |--------------------------------------------------------------------------
        */

        $dues = SubscriptionDue::where(
            'tenant_id',
            $tenant->id
        )
            ->when(
                $subscription,
                function ($query) use ($subscription) {

                    $query->where(
                        'subscription_id',
                        $subscription->id
                    );
                }
            )
            ->orderBy('billing_month')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */

        $payments = AdminTenantPayment::where(
            'tenant_id',
            $tenant->id
        )
            ->when(
                $subscription,
                function ($query) use ($subscription) {

                    $query->where(
                        'subscription_id',
                        $subscription->id
                    );
                }
            )
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Total Outstanding
        |--------------------------------------------------------------------------
        */

        $totalDue = $dues->sum('amount');

        $totalPaid = $dues->sum('paid_amount');

        $totalRemaining = $dues->sum('remaining_amount');

        return view(
            'payments.history',
            compact(
                'tenant',
                'subscription',
                'dues',
                'payments',
                'totalDue',
                'totalPaid',
                'totalRemaining'
            )
        );
    }

    public function createInvoice()
    {
        $tenants = Tenant::with('activeSubscription')
            ->where('status', true)
            ->orderBy('business_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Generate monthly dues
        |--------------------------------------------------------------------------
        */

        foreach ($tenants as $tenant) {
            $subscription = $tenant->activeSubscription;

            if ($subscription) {
                $this->generateMonthlyDues($subscription);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Current outstanding amount
        |--------------------------------------------------------------------------
        */

        foreach ($tenants as $tenant) {
            $subscription = $tenant->activeSubscription;

            $outstanding = 0;

            if ($subscription) {
                $outstanding = SubscriptionDue::where(
                    'tenant_id',
                    $tenant->id
                )
                    ->where(
                        'subscription_id',
                        $subscription->id
                    )
                    ->sum('remaining_amount');
            }

            $tenant->current_outstanding = (float) $outstanding;
        }

        return view(
            'admin.invoices.create',
            compact('tenants')
        );
    }

    public function invoices()
    {
        $payments = AdminTenantPayment::with([
            'tenant.activeSubscription',
        ])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.invoices.index',
            compact('payments')
        );
    }

    public function printInvoice(
        AdminTenantPayment $payment
    ) {
        $payment->load([
            'tenant.activeSubscription',
        ]);

        return view(
            'admin.invoices.print',
            compact('payment')
        );
    }
}
